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
 * General-purpose nested key-value data structure stored in a native PHP array
 * @package e107\Configuration
 */
class InMemoryConfiguration implements ConfigurationInterface
{
	const KEY_SEPARATOR = '/';
	const NOT_SAVED = 0;
	const SAVED = 1;
	protected $data = [];
	protected $saveState = self::NOT_SAVED;
	/** @var ConfigurationStorageInterface */
	protected $storageBackend;

	/**
	 * @inheritdoc
	 */
	public function setStorage($storage)
	{
		$this->storageBackend = $storage;
	}

	/**
	 * @inheritdoc
	 */
	public function add($key = '', $value = NULL)
	{
		$existingValue = $this->get($key);
		if (is_scalar($existingValue))
		{
			return $this->addScalar($key, $value, $existingValue);
		}
		$part = $this->formatForConfiguration($key, (array)$value);

		$this->setData(array_merge_recursive($this->data, $part));

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function get($key = '')
	{
		$current_key = strtok($key, self::KEY_SEPARATOR);
		return $this->getRecursive($key, $current_key, $this->data);
	}

	private function getRecursive($key, $carry, $part)
	{
		if (!$carry) return $part;
		if (!is_array($part))
			throw new ConfigurationKeyException(
				"Key \"$carry\" of path \"$key\" " .
				"cannot be accessed because its parent is not traversable."
			);

		if (array_key_exists($carry, $part))
		{
			$next_key = strtok(self::KEY_SEPARATOR);
			return $this->getRecursive($key, $next_key, $part[$carry]);
		}
		return NULL;
	}

	private function addScalar($key, $value, $existingValue)
	{
		if (is_string($existingValue))
		{
			return $this->set($key, $existingValue . $value);
		}
		return $this->set($key, $existingValue + $value);
	}

	/**
	 * @inheritdoc
	 */
	public function set($key = '', $value = NULL)
	{
		$part = $this->formatForConfiguration($key, $value);

		if (is_array($part))
		{
			$this->setData(array_replace_recursive($this->data, $part));
		}
		else
		{
			$this->setData($part);
		}

		return $this;
	}

	private function formatForConfiguration($key, $value)
	{
		$this->validateSetInput($key, $value);

		$part = $value;
		if (!empty($key))
		{
			$nested_keys = explode(self::KEY_SEPARATOR, $key);
			$part = [array_pop($nested_keys) => $value];
			while ($nested_key = array_pop($nested_keys))
			{
				$part = array($nested_key => $part);
			}
		}
		return $part;
	}

	private function validateSetInput($key, $value)
	{
		$type = gettype($value);
		if (!self::isValueTypeAcceptable($type))
		{
			throw new ConfigurationValueException(
				"Value provided for path \"$key\" is of an unacceptable type: $type"
			);
		}
		$this->validateKeyIsReachable($key);
		$this->validateValueArrayHasValidKeys($value);
		$this->validateValueArrayValuesAreAcceptable($value);
		return true;
	}

	private static function isValueTypeAcceptable($type)
	{
		switch ($type)
		{
			case "object":
			case "callable":
			case "iterable":
			case "resource":
				return false;
			default:
		}
		return true;
	}

	private function validateKeyIsReachable($key)
	{
		$this->get($key);
		return true;
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private function validateValueArrayHasValidKeys($candidate)
	{
		if (!is_array($candidate)) return true;
		array_walk_recursive($candidate, function ($value, $key)
		{
			if (strpos($key, self::KEY_SEPARATOR) !== false)
			{
				throw new ConfigurationValueException(
					"Value provided is an array that contains an invalid key: $key"
				);
			}
		});
		return true;
	}

	private function validateValueArrayValuesAreAcceptable($candidate)
	{
		if (!is_array($candidate)) return true;
		array_walk_recursive($candidate, function ($value, $key)
		{
			$type = gettype($value);
			if (!self::isValueTypeAcceptable($type))
			{
				throw new ConfigurationValueException(
					"Value is an array with a key \"$key\" that has an unacceptable value of type $type"
				);
			}
		});
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function clear($key = '')
	{
		$keyHierarchy = explode(self::KEY_SEPARATOR, $key);
		$this->unlinkNestedKey($this->data, $keyHierarchy, $key);
		return $this;
	}

	private function unlinkNestedKey(&$part, array $keyHierarchy, $fullPath)
	{
		$nextKey = array_shift($keyHierarchy);
		if (!is_array($part))
		{
			throw new ConfigurationKeyException(
				"Key \"$nextKey\" of path \"$fullPath\" " .
				"cannot be accessed because its parent is not traversable."
			);
		}
		if (array_key_exists($nextKey, $part))
		{
			if (count($keyHierarchy) == 0)
			{
				unset($part[$nextKey]);
				$this->saveState = self::NOT_SAVED;
				return;
			}
			$this->unlinkNestedKey($part[$nextKey], $keyHierarchy, $fullPath);
			return;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function populate($data)
	{
		if ($data instanceof ConfigurationInterface)
		{
			return $this->set('', $data->get());
		}
		return $this->set('', $data);
	}

	/**
	 * @inheritdoc
	 */
	public function isSaved()
	{
		if ($this->saveState == self::SAVED)
		{
			return true;
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function save()
	{
		$this->storageBackend->write($this);
		$this->saveState = self::SAVED;
	}

	/**
	 * @inheritdoc
	 */
	public function load()
	{
		$this->populate($this->storageBackend->read());
		$this->saveState = self::SAVED;
		return $this;
	}

	private function setData($data)
	{
		$this->data = $data;
		$this->saveState = self::NOT_SAVED;
	}
}