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


use e107\Configuration\InMemoryConfiguration;

class V3SiteConfigurationStorageTest extends AbstractSiteConfigurationStorageTest
{
	protected $sampleFileName = "e107_config.sample.v3.php";
	protected $storageClass   = V3SiteConfigurationStorage::class;

	const CONFIG_FILE_SIGNATURE = 'e107_config.php format revision: 3';

	public function testReadDatabaseCredentials()
	{
		parent::testReadDatabaseCredentials();
		$this->assertEquals('utf8mb4', $this->config->get('database/charset'));
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
			$this->assertEquals(strrev($configKey), $afterConfig->get($configKey));
		}
	}

	public function testWriteToCorruptSource()
	{
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		file_put_contents($path, "<?php // " . self::CONFIG_FILE_SIGNATURE . "\nNOT PHP CODE");

		$this->testWrite();
	}

	public function testWriteWithRemovedKeys()
	{
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		file_put_contents($path, '<?php // ' . self::CONFIG_FILE_SIGNATURE . '
		$database = ["fake_key" => "123 Fake Street, Springfield, USA"];
		');
		$this->assertStringContainsString("fake_key", file_get_contents($path));

		$this->testWrite();

		$this->assertStringNotContainsString("fake_key", file_get_contents($path));
	}

	public function testWriteWithMissingSection()
	{
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		file_put_contents($path, '<?php // ' . self::CONFIG_FILE_SIGNATURE . '
		$database = ["fake_key" => "123 Fake Street, Springfield, USA"];
		');
		$this->assertStringNotContainsString('$paths', file_get_contents($path));

		$this->testWrite();

		$this->assertStringContainsString('$paths', file_get_contents($path));
	}

	public function testWriteFixesHeader()
	{
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		file_put_contents($path, '<?php // Wrong header
		$database = [];
		$paths = array();
		');
		$this->assertStringNotContainsString(self::CONFIG_FILE_SIGNATURE,
			explode("\n", file_get_contents($path))[0]
		);

		$this->testWrite();

		$this->assertStringContainsString(self::CONFIG_FILE_SIGNATURE,
			explode("\n", file_get_contents($path))[0]
		);
	}
}
