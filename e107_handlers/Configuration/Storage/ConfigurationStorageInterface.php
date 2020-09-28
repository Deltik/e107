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
 * Persistent storage for the Configuration data structure
 * @package e107\Configuration\Storage
 *
 * @see ConfigurationInterface The Configuration data structure
 */
interface ConfigurationStorageInterface
{
	/**
	 * Reads configuration data from storage backend
	 *
	 * @return mixed
	 *  Data that can be parsed into a {@link ConfigurationInterface}
	 * @throws \RuntimeException If there was an error reading
	 */
	public function read();

	/**
	 * Writes configuration data to storage backend
	 *
	 * @param ConfigurationInterface $config
	 *  A {@link ConfigurationInterface} to serialize into the persistent storage format
	 * @return void
	 * @throws \RuntimeException If there was an error writing
	 */
	public function write($config);
}