<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2018 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration;


use e107\Configuration\Exceptions\ConfigurationKeyException;
use e107\Configuration\Exceptions\ConfigurationValueException;
use e107\Configuration\Storage\ConfigurationStorageInterface;

/**
 * General-purpose nested key-value data structure
 * @package e107\Configuration
 */
interface ConfigurationInterface
{
	/**
	 * Gets a value from this Configuration
	 *
	 * @param string $key
	 *  If a blank string, all data in this Configuration are returned.
	 *
	 *  If a string is specified, the Configuration associative array is traversed and the key's value is returned.
	 *  Nested keys are separated by a slash character ("/"). Example Configuration:
	 *
	 *  ```php
	 *  array(
	 *      'string_key' => 'string_value',
	 *      'array_key' => array(
	 *          'nested_key' => 'nested_value',
	 *      ),
	 *  );
	 *  ```
	 *
	 *  Calling this method with a key of "string_key" would return "string_value".
	 *
	 *  Calling this method with a key of "array_key" would return array('nested_key' => 'nested_value').
	 *
	 *  Calling this method with a key of "array_key/nested_key" would return "nested_value".
	 *
	 * @return mixed
	 *  The data corresponding to the specified key in this Configuration
	 * @throws ConfigurationKeyException If the key is nested and one or more of the parents is not an array
	 */
	public function get($key = '');

	/**
	 * Sets the key's value, replacing any existing value
	 *
	 * @param string $key
	 *  The key whose value should be replaced
	 *
	 *  If the key is blank, the entire Configuration will be replaced with the value.
	 * @param mixed $value
	 *  The replacement value for the key
	 *
	 *  If the value is `NULL`, the key will *not* be removed and the value will be set to `NULL`.
	 * @return $this
	 *  The updated Configuration
	 * @throws ConfigurationKeyException If the key is nested and one or more of the parents is not an array
	 * @throws ConfigurationValueException If the value is an array with any keys that have a slash
	 * @throws ConfigurationValueException If the value cannot be serialized into a valid part of a Configuration
	 * @see ConfigurationInterface::clear() How to remove a key from the Configuration
	 */
	public function set($key = '', $value = NULL);

	/**
	 * Adds the value to the key, either by appending or by merging
	 *
	 * @param string $key
	 *  The key to whose value should have a new value appended or merged
	 *
	 *  If the key is blank, the value will be added to or merged into the root Configuration.
	 * @param mixed $value
	 *  The value to add or merge
	 * @return $this
	 *  The updated Configuration
	 * @throws ConfigurationValueException If the value type is incompatible with appending to or merging into the key
	 * @throws ConfigurationKeyException If the key is nested and one or more of the parents is not an array
	 */
	public function add($key = '', $value = NULL);

	/**
	 * Removes the key and its value
	 *
	 * @param string $key
	 *  The key to unset
	 *
	 *  If the key is blank, the entire Configuration will be cleared.
	 * @return $this
	 *  The updated Configuration
	 * @throws ConfigurationKeyException If the key is nested and one or more of the parents is not an array
	 */
	public function clear($key = '');

	/**
	 * Replaces this entire Configuration with the provided data
	 *
	 * @param array|string|ConfigurationInterface $data
	 * @return $this
	 *  The updated Configuration
	 * @throws ConfigurationValueException If the provided data cannot be converted into a valid Configuration
	 */
	public function populate($data);

	/**
	 * Checks if the changes to this Configuration have been committed to persistent storage
	 *
	 * @return bool
	 *  `TRUE` if there are no unsaved changes
	 *
	 *  `FALSE` if changes have been made to this Configuration and the save() method has not been called.
	 */
	public function isSaved();

	/**
	 * Commits this Configuration to persistent storage
	 *
	 * @return void
	 * @throws \RuntimeException If there was an error writing to persistent storage
	 */
	public function save();

	/**
	 * Reads the persistent storage into this Configuration, replacing all existing data
	 *
	 * @return $this
	 *  The updated Configuration
	 * @throws \RuntimeException If there was an error reading from persistent storage
	 */
	public function load();

	/**
	 * Injects a ConfigurationStorage (persistent storage) object
	 *
	 * @param ConfigurationStorageInterface $object
	 *  An object that handles persistent storage for Configurations
	 * @return void
	 */
	public function setStorage($object);
}