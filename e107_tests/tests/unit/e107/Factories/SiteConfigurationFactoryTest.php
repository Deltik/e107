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

class SiteConfigurationFactoryTest extends \Codeception\Test\Unit
{
	public function _before()
	{
		rename(APP_PATH . '/e107_config.php', APP_PATH . '/e107_config.php~');
		set_error_handler(function ()
		{
		});
	}

	public function _after()
	{
		rename(APP_PATH . '/e107_config.php~', APP_PATH . '/e107_config.php');
		restore_error_handler();
	}

	public function testGetDefaultImplementationChoosesFallback()
	{
		copy(codecept_data_dir() . "e107_config.sample.v2.php", APP_PATH . '/e107_config.php');

		$this->assertEquals(
			V2SiteConfigurationStorage::class,
			SiteConfigurationFactory::getDefaultImplementation()
		);
	}

	public function testGetDefaultImplementationChoosesV3()
	{
		copy(codecept_data_dir() . "e107_config.sample.v3.php", APP_PATH . '/e107_config.php');

		$this->assertEquals(
			V3SiteConfigurationStorage::class,
			SiteConfigurationFactory::getDefaultImplementation()
		);
	}

	public function testGetDefaultImplementationFailsWhenFileInaccessible()
	{
		$this->expectException(\RuntimeException::class);
		SiteConfigurationFactory::getDefaultImplementation();
	}

	public function testMakeDefault()
	{
		copy(codecept_data_dir() . "e107_config.sample.v3.php", APP_PATH . '/e107_config.php');

		$object = SiteConfigurationFactory::make();
		$this->assertInstanceOf(V3SiteConfigurationStorage::class, $object);
	}

	public function testMakeWithFilePath()
	{
		$object = SiteConfigurationFactory::make(
			V2SiteConfigurationStorage::class,
			codecept_data_dir() . "/e107_config.sample.v2.php"
		);
		$this->assertInstanceOf(V2SiteConfigurationStorage::class, $object);

		$read = $object->read();
		$this->assertEquals('{{ mySQLdefaultdb }}', $read['database']['name']);
	}
}
