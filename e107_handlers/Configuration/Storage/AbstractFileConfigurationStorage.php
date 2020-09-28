<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2018 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration\Storage;


use e107\Configuration\ConfigurationInterface;

/**
 * Persistent storage that reads and writes Configuration data from and to a regular file
 * @package e107\Configuration\Storage
 */
abstract class AbstractFileConfigurationStorage implements ConfigurationStorageInterface
{
	/** @var string */
	protected $filePath;

	/**
	 * AbstractFileConfigurationStorage constructor
	 * @param string $filePath
	 *  Absolute path to the configuration file
	 */
	public function __construct($filePath)
	{
		$this->filePath = $filePath;
	}

	/**
	 * @inheritdoc
	 */
	public function read()
	{
		$fileContents = file_get_contents($this->filePath);
		if ($fileContents === FALSE)
		{
			throw new \RuntimeException(
				"Could not read from file: {$this->filePath}"
			);
		}
		return $this->fromFile($fileContents);
	}

	/**
	 * @inheritdoc
	 */
	public function write($config)
	{
		$fileContents = $this->toFile($config);
		$bytesWritten = file_put_contents($this->filePath, $fileContents);
		if ($bytesWritten === FALSE)
		{
			throw new \RuntimeException(
				"Could not write to file: {$this->filePath}"
			);
		}
	}

	/**
	 * Convert the raw data from the configuration file
	 * @param $data
	 * @return string The parsed configuration data that can be imported by {@see ConfigurationInterface::populate()}
	 */
	abstract protected function fromFile($data);

	/**
	 * Convert the configuration data into the raw configuration file format
	 * @param ConfigurationInterface $config A {@link ConfigurationInterface} to read from
	 * @return string The exact contents to write into the configuration file
	 */
	abstract protected function toFile($config);
}
