<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2021 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

namespace e107\Configuration\Storage;


use e107\Configuration\InMemoryConfiguration;

abstract class AbstractSiteConfigurationStorageTest extends \Codeception\Test\Unit
{
	/**
	 * @var string
	 */
	protected $sampleFileName;
	/**
	 * @var string
	 */
	protected $storageClass;

	/**
	 * @var InMemoryConfiguration
	 */
	protected InMemoryConfiguration $config;
	/**
	 * @var AbstractSiteConfigurationStorage
	 */
	protected AbstractSiteConfigurationStorage $storage;
	/**
	 * @var resource
	 */
	protected $tmpfile;

	public function _before()
	{
		$inputSource = codecept_data_dir() . "/" . $this->sampleFileName;
		$this->tmpfile = tmpfile();
		$data = file_get_contents($inputSource);
		fwrite($this->tmpfile, $data);
		fflush($this->tmpfile);
		$path = stream_get_meta_data($this->tmpfile)['uri'];
		$this->storage = new $this->storageClass($path);
		$data = @$this->storage->read();
		$this->config = new InMemoryConfiguration();
		$this->config->populate($data);
	}

	public function testReadDatabaseCredentials()
	{
		$config = $this->config;

		$this->assertEquals('mysql', $config->get('database/driver'));
		$this->assertEquals('{{ mySQLprefix }}', $config->get('database/prefix'));
		$this->assertEquals('{{ mySQLserver }}', $config->get('database/host'));
		$this->assertEquals(3306, $config->get('database/port'));
		$this->assertEquals('{{ mySQLdefaultdb }}', $config->get('database/name'));
		$this->assertEquals('{{ mySQLuser }}', $config->get('database/username'));
		$this->assertEquals('{{ mySQLpassword }}', $config->get('database/password'));
		$this->assertEmpty($config->get('database/path'));
		$this->assertEmpty($config->get('database/socket'));
	}

	public function testReadBasePaths()
	{
		$config = $this->config;

		$this->assertEquals('e107_admin/', $config->get('paths/admin'));
		$this->assertEquals('e107_core/', $config->get('paths/core'));
		$this->assertEquals('e107_docs/', $config->get('paths/docs'));
		$this->assertEquals('e107_files/', $config->get('paths/files'));
		$this->assertEquals('e107_handlers/', $config->get('paths/handlers'));
		$this->assertEquals('e107_images/', $config->get('paths/images'));
		$this->assertEquals('e107_languages/', $config->get('paths/languages'));
		$this->assertEquals('e107_media/', $config->get('paths/media'));
		$this->assertEquals('e107_plugins/', $config->get('paths/plugins'));
		$this->assertEquals('e107_system/', $config->get('paths/system'));
		$this->assertEquals('e107_themes/', $config->get('paths/themes'));
		$this->assertEquals('e107_web/', $config->get('paths/web'));
	}

	/**
	 * Based on Illuminate\Support\Arr::dot()
	 *
	 * @param array  $array
	 * @param string $prepend
	 * @return string[]
	 * @license   https://github.com/illuminate/support/blob/master/LICENSE.md MIT License
	 * @copyright Copyright (c) Taylor Otwell
	 */
	protected static function arraySlash($array, $prepend = '')
	{
		$results = [];

		foreach ($array as $key => $value)
		{
			if (is_array($value) && !empty($value))
			{
				$results = array_merge($results, static::arraySlash($value, $prepend . $key . '/'));
			}
			else
			{
				$results[$prepend . $key] = $value;
			}
		}

		return $results;
	}
}