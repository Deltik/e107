<?
/*
+---------------------------------------------------------------+
|	e107 website system
|	/ypslide_menu.php
|
|	Based on original javascript code from http://youngpup.net, converted in PHP and merged in e107 by Jalist, js and PHP features enhanced by Lolo Irie
|		used with permission
|
|	©Steve Dunstan 2001-2002 / ©Lolo Irie 2004
|	http://e107.org - http://touchatou.org
|	jalist@e107.org - lolo_irie@e107coders.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../../class2.php");

require_once(HEADERF);
$caption = "Youngpup slide Menu / Readme File (Version 1)";
$text = "This plugin \"ypslide_menu\" from <b>Youngpup.net</b> (original javascript for simple slide effect with absolute position ONLY for main menus and submenus), <b>Jalist</b> (Converted for PHP and merged with e107), and completed by <b>Lolo Irie</b> (all other features) is the first version and runs for e107 v0.612+.
<br />
Special thanks to AcidFire, UpChuck (from the e107Community) and Lisa for the big help for tests. High appreciated !!! :)
<br /><br />
<b>This easy and beautiful DHTML script from Youngpup.net for the main menu finally for e107</b>
<br />
<br />
From admin area, you are able to configure it like required for your site :
<br />
- Define general options like position (absolute or in a menu area), slide direction, total width, width for submenus...
<br />
- Define design options like colors of links, background color, background-image and more...
<br />
<br />
IMPORTANT NOTE : If you need to translate the 'ADMINISTRATION' link in your menu, please edit your file ypslide_menu.php, line 198.
<br />
<br />
To report bugs, please use the bugtracker on <a href=\"www.touchatou.org/bugtracker.php\">www.touchatou.org</a>

<br />
<br />
<br />
<br />
<b>TO INSTALL IT :</b>
<br />
- You need to extract all files in your plugin folder keeping the folders structure (ypslide_menu should be a subfolder of your plugin folder).
<br />
- Go to your plugin manager in admin area and install it.
<br />
- If you are using eDynamicMenu, use this last one to display this DHTML menu on your site and activate eDynamic menu in an activated menu area if not yet used. If you are not using eDynamicMenu, go to your menus configuration page to display this menu.
<br />
<br />
That'all.
<br /><br />
<br />
Actually that's only available in English, but... ;)
<br />
If you need an other language, just translate english files... and think to send me these new files for next releases. ;)
<br /><br />
<br />
<b>TO UNINSTALL IT :</b>
<br />
Temporary : change the type of menu displayed with eDynamicMenu or unactivate this menu in your admin area.
<br />
For ever : use the plugin manager, and delete all files from server..
<br />
<br />
<br />
<b>Lolo Irie</b> (03.2004)<br />
Support on <a href=\"http://www.touchatou.org\">Touchatou.org</a>
";

$ns -> tablerender($caption, $text);
require_once(FOOTERF);
?>