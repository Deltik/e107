<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2020 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration\Storage;


use e107\Configuration\ConfigurationInterface;
use e107\Configuration\InMemoryConfiguration;

class V2SiteConfigurationStorageTest extends AbstractSiteConfigurationStorageTest
{
	protected $sampleFileName = "e107_config.sample.v2.php";
	protected $storageClass   = V2SiteConfigurationStorage::class;

	public function _before()
	{
		$inputSource = codecept_data_dir() . "/e107_config.sample.v2.php";
		$this->tmpfile = tmpfile();
		$data = file_get_contents($inputSource);
		fwrite($this->tmpfile, $data);
		fflush($this->tmpfile);
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		$this->storage = new V2SiteConfigurationStorage($path);
		$data = @$this->storage->read();
		$this->config = new InMemoryConfiguration();
		$this->config->populate($data);
	}

	public function testReadDatabaseCredentials()
	{
		parent::testReadDatabaseCredentials();
		$this->assertEquals('utf8', $this->config->get('database/charset'));
	}

	public function testReadBasePaths()
	{
		parent::testReadBasePaths();
	}

	public function testWrite()
	{
		$config = $this->config;
		$configKeys = array_keys(self::arraySlash($config->get()));
		foreach ($configKeys as $configKey)
		{
			$config->set($configKey, strrev($configKey));
		}

		$this->storage->write($config);

		$path = stream_get_meta_data($this->tmpfile)['uri'];
		$storage = new $this->storageClass($path);
		$data = @$storage->read();
		$afterConfig = new InMemoryConfiguration();
		$afterConfig->populate($data);
		foreach ($configKeys as $configKey)
		{
			switch ($configKey)
			{
				case "database/driver":
					$this->assertEquals("mysql", $afterConfig->get($configKey));
					break;
				default:
					$this->assertEquals(strrev($configKey), $afterConfig->get($configKey));
			}
		}
	}

	public function testWriteToCorruptSource()
	{
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		file_put_contents($path, "<?php NOT PHP CODE");

		$this->testWrite();
	}
}
