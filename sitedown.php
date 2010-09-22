<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ©Steve Dunstan 2001-2002
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/sitedown.php $
|     $Revision: 11678 $
|     $Id: sitedown.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once('class2.php');

if (!varset($pref['maintainance_flag']))
{
	header('location: '.SITEURL);
	exit();
}

header('Content-type: text/html; charset='.CHARSET);

include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/lan_'.e_PAGE);

require_once(e_FILE.'shortcode/batch/sitedown_shortcodes.php');

if (!$SITEDOWN_TABLE)
{
	if (file_exists(THEME.'sitedown_template.php'))
	{
		require_once(THEME.'sitedown_template.php');
	}
	else
	{
		require_once(e_THEME.'templates/sitedown_template.php');
	}
}

echo $tp->parseTemplate($SITEDOWN_TABLE, TRUE, $sitedown_shortcodes);
