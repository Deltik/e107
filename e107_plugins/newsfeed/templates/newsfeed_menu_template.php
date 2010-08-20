<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ?Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/newsfeed/templates/newsfeed_menu_template.php,v $
|     $Revision: 11346 $
|     $Date: 2010-02-17 12:56:14 -0600 (Wed, 17 Feb 2010) $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$truncate = 100;
$truncate_string = " ...";
// $items = 2;

$NEWSFEED_MENU_CAPTION = NFLAN_38;

$NEWSFEED_MENU_START = "\n\n<!-- Start NewsFeed Menu -->
<div style='text-align: center; margin-left: auto; margin-right: auto;'>{FEEDIMAGE}<br /><b>{FEEDTITLE}</b></div>\n<br />
<table class='newsfeed_menu_table' style='width:100%'>
";

$NEWSFEED_MENU = "
<tr><td style='vertical-align:top;width:5%'><b>&raquo;</b></td><td class='newsfeed_menu_cell' >{FEEDITEMLINK}<br /><span class='smalltext'>{FEEDITEMTEXT}</span></td></tr>\n";

$NEWSFEED_MENU_END = "</table><br />

<div style='text-align: center;'><hr /><span class='smalltext'>{FEEDLASTBUILDDATE}<br />{LINKTOMAIN}</span></div>\n
<!-- End News Feed Menu -->\n\n";


?>
