<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Factories;


use e107\Configuration\Storage\V2SiteConfigurationStorage;
use e107\Configuration\Storage\V3SiteConfigurationStorage;

/**
 * Factory for e107_config.php reader/writer objects
 * @see V2SiteConfigurationStorage e107 v1/v2 e107_config.php format handler
 * @see V3SiteConfigurationStorage e107 v3 e107_config.php format handler
 * @package e107\Factories
 */
class SiteConfigurationFactory extends BaseFactory
{
	protected const V3_SIGNATURE = 'e107_config.php format revision: 3';

	/**
	 * @inheritDoc
	 */
	public static function getImplementations()
	{
		return [
			V2SiteConfigurationStorage::class,
			V3SiteConfigurationStorage::class,
		];
	}

	/**
	 * @inheritDoc
	 * @throws \RuntimeException if the root site configuration file cannot be opened
	 */
	public static function getDefaultImplementation()
	{
		$root = self::getAppRoot();
		$handle = fopen($root . '/e107_config.php', 'r');
		if ($handle === false)
		{
			throw new \RuntimeException("Unable to open site configuration file");
		}
		$buffer = fgets($handle);
		fclose($handle);
		if (strpos($buffer, self::V3_SIGNATURE) !== false)
		{
			return V3SiteConfigurationStorage::class;
		}
		return V2SiteConfigurationStorage::class;
	}

	/**
	 * {@inheritDoc}
	 * @return \e107\Configuration\Storage\AbstractSiteConfigurationStorage
	 */
	public static function make($type = null, ...$args)
	{
		if (count($args) != 1)
		{
			$args = [self::getAppRoot()];
		}
		return parent::make($type, ...$args);
	}

	/**
	 * @return string
	 */
	private static function getAppRoot()
	{
		if (defined('e_ROOT'))
		{
			$root = e_ROOT;
		}
		else
		{
			$root = __DIR__ . '/../../';
		}
		return $root;
	}
}