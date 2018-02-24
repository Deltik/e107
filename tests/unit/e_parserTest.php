<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2018 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */


	class e_parserTest extends \Codeception\Test\Unit
	{
/*
		public function testAddAllowedTag()
		{

		}

		public function testAddAllowedAttribute()
		{

		}

		public function testSetAllowedTags()
		{

		}

		public function testSetScriptAccess()
		{

		}

		public function testGetAllowedTags()
		{

		}

		public function testGetScriptAccess()
		{

		}

		public function testSetAllowedAttributes()
		{

		}

		public function testSetScriptTags()
		{

		}

		public function testLeadingZeros()
		{

		}

		public function testLanVars()
		{

		}

		public function testGetTags()
		{

		}

		public function testToGlyph()
		{

		}

		public function testToBadge()
		{

		}

		public function testToLabel()
		{

		}

		public function testToFile()
		{

		}

		public function testToAvatar()
		{

		}

		public function testToIcon()
		{

		}

		public function testToImage()
		{

		}

		public function testIsBBcode()
		{

		}

		public function testIsHtml()
		{

		}

		public function testIsJSON()
		{

		}

		public function testIsUTF8()
		{

		}

		public function testIsVideo()
		{

		}

		public function testIsImage()
		{

		}

		public function testToVideo()
		{

		}*/

		public function testToDate()
		{
			try
			{
				$class = $this->make('e_parser');
			}
			catch (Exception $e)
			{
				$this->assertTrue(false, "Couldn't load e_parser object");
			}

			$time = 1519512067; //  Saturday 24 February 2018 - 22:41:07

			$long = $class->toDate($time, 'long');
			$this->assertContains('<span data-livestamp="1519512067">Saturday 24 February 2018',$long);

			$short = $class->toDate($time, 'short');
			$this->assertContains('Feb 2018', $short);

			$rel = $class->toDate($time, 'relative');
			$this->assertContains('ago', $rel);
			

		}
/*
		public function testParseBBTags()
		{

		}

		public function testFilter()
		{

		}

		public function testCleanHtml()
		{

		}

		public function testSecureAttributeValue()
		{

		}

		public function testInvalidAttributeValue()
		{

		}
*/
	}
