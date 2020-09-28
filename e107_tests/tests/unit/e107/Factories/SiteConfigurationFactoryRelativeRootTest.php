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

function isInRelativeRootTest()
{
	foreach (debug_backtrace(false) as $line)
	{
		if (isset($line['class']) && $line['class'] == SiteConfigurationFactoryRelativeRootTest::class)
		{
			return true;
		}
	}
	return false;
}

function defined($name)
{
	if (isInRelativeRootTest()) return false;
	return \defined($name);
}

class SiteConfigurationFactoryRelativeRootTest extends SiteConfigurationFactoryTest
{
	public function testGetDefaultImplementationChoosesV3()
	{
		parent::testGetDefaultImplementationChoosesV3();
	}
}