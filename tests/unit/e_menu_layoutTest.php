<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2019 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */


	class e_menu_layoutTest extends \Codeception\Test\Unit
	{

		/** @var e_menu_layout */
		protected $menu;

		protected function _before()
		{
			require_once(e_HANDLER."menumanager_class.php");
		}

		private function copydir( $src, $dst )
		{
			if(!is_dir($src) || is_dir($dst))
			{
				return false;
			}


			$dir = opendir($src);
			@mkdir(dirname($dst));

			$DS = DIRECTORY_SEPARATOR ;

			while(false !== ($file = readdir($dir)))
			{
				if($file != '.' && $file != '..')
				{
					if(is_dir($src . $DS . $file))
					{
						$this->copydir($src . $DS . $file, $dst . $DS . $file);
					}
					else
					{
						copy($src . $DS . $file, $dst . $DS . $file);
					}
				}
			}

			closedir($dir);
		}



/*
		public function testMenuSelector()
		{

		}

		*/


		public function testGetLayouts()
		{
			$src1 = codecept_data_dir()."testcore";
			$dest1 = e_THEME."testcore";

			$this->copydir($src1,$dest1);

			$src2 = codecept_data_dir()."testkubrick";
			$dest2 = e_THEME."testkubrick";

			$this->copydir($src2,$dest2);

			$src3 = codecept_data_dir()."basic-light";
			$dest3 = e_THEME."basic-light";

			$this->copydir($src3,$dest3);

			$tests = array(

				'bootstrap3'   => array (
					'templates' => array( // template key and string length
						'jumbotron_home'            => 2940,
						'modern_business_home'      => 3746,
						'jumbotron_full'            => 1949,
						'jumbotron_sidebar_right'   => 2765
					),
					'menus'     => array (
						'jumbotron_home'            => array ('1','2','3','4','5','6','7','8','9','10','11','12','13','14','100','101','102','103','104','105','106','107',),
						'modern_business_home'      => array ('10','100','101','102','103','104','105','106','107',),
						'jumbotron_full'            => array ('1','100','101','102','103','104','105','106','107',),
						'jumbotron_sidebar_right'   => array ('1','2','3','4','5','6','7','8','100','101','102','103','104','105','106','107',),
					),
				),


				'testkubrick'   => array (
					'templates' => array(
						'legacyCustom' => 283,
						'legacyDefault' => 328
					),
					'menus'     => array(
						'legacyCustom' => array(),
						'legacyDefault' => array('1', '2')
					),
				),

				'testcore'      => array (
					'templates' => array (
						'HOME' => 1635,
						'FULL' => 1378,
						'legacyDefault'=> 1807
					),
					'menus'     => array(
						'HOME' => array('2', '3', '4'),
						'FULL' => array(),
						'legacyDefault'=> array('1', '2', '3', '4','5','6')
					),
				),

				'basic-light' => array(
					'templates' => array(
						'default'       => 3359,
						'default-home'  => 3359,
						'simple-page'   => 1604,
						'wide-page'     => 1272
					),
					'menus' => array(
						'default'       => array('1', '2', '3', '4'),
						'default-home'  => array('1', '2', '3', '4'),
						'simple-page'   => array('1', '2', '3', '4'),
						'wide-page'     => array(),
					),


				),
			);

			foreach($tests as $theme=>$vars)
			{
				$result = e_menu_layout::getLayouts($theme);

			/*	if($theme === 'basic-light')
				{
					var_dump($result['templates']);
					var_dump($result['menus']);
				}*/


				foreach($vars['templates'] as $key=>$length)
				{

					$expectedLength = $length;
					$actualLength = strlen($result['templates'][$key]);

					$this->assertEquals($expectedLength, $actualLength, $key. " is different");
				}

				foreach($vars['menus'] as $key=>$arr)
				{
					$this->assertEquals($arr, $result['menus'][$key], $key." is different");
				}


			}




		}




	}
