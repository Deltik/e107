<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/e107_plugins/newsfeed/templates/newsfeed_menu_template.php,v $
|     $Revision: 1.5 $
|     $Date: 2005/12/14 19:28:51 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$truncate = 100;
$truncate_string = "...[more]";
$items = 10;

$NEWSFEED_MENU_START = "
<div style='text-align: center; margin-left: auto; margin-right: auto;'>{FEEDIMAGE}<br /><b>{FEEDTITLE}</b></div>\n<br />\n";

$NEWSFEED_MENU = "
<b>&raquo;</b>{FEEDITEMLINK}<br /><span class='smalltext'>{FEEDITEMTEXT}</span><br />\n";


$NEWSFEED_MENU_END = "

<div style='text-align: right;'><span class='smalltext'>{FEEDLASTBUILDDATE}<br />{LINKTOMAIN}</span></div>\n<hr />";


?>