<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	YPSlideMenu by Youngpup.net (original code)/ Jalist (Convert for e107)/ Lisa (Submenus displayed with relative position and not function of the mouse position) and Lolo Irie (Javascript and PHP fix, plugins features)
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = "ypslide_menu";
$eplug_version = "1.01";
$eplug_author = "Youngpup.net / Jalist / Lolo Irie";
$eplug_logo = "images/ico_button.png";
$eplug_url = "http://touchatou.org";
$eplug_email = "lolo_irie@touchatou.org";
$eplug_description = "This plugin will allow you to use the beautiful DHTML Menu from youngpup.net with your e107 System (like on e107.org few months ago).<br /><br />";
$eplug_compatible = "e107v6.12";
$eplug_readme = "ypslidemenu_README.php";	// leave blank if no readme file

// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "ypslide_menu";

// Mane of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = "ypslide_menu.php";

// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = "config.php";

// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/icon.png";
$eplug_caption =  "Configure Ypslide Menu";

// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array(
	"ypmenu_pos" => 1,
	"ypmenu_posx" => 0,
	"ypmenu_posy" => 0,
	"ypmenu_slidedir" => "down",
	"ypmenu_subwidth" => 200,
	"ypmenu_totalwidth" => 750,
	"ypmenu_confpro" => 0,
	"ypmenu_aspect" => 0,
	"ypmenu_subpos" => 1,
	"ypmenu_subposx" => 5,
	"ypmenu_subposy" => 18
);

// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = "ypslide_cfsaved";

// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = array("CREATE TABLE ".$mySQLprefix."ypslide_cfsaved (
  ypslide_cfsaved_name varchar(20) NOT NULL default '',
  ypslide_cfsaved_value text NOT NULL,
  PRIMARY KEY  (ypslide_cfsaved_name)
) TYPE=MyISAM;");


// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = FALSE;
$eplug_link_name = "";
$eplug_link_url = "";


// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = "
This plugin can be used since version .612 but for versions .612 and .613 the color picker is NOT running. Ok for all new versions.
<br />
<br />
To use this DHTML menu, I recommend you to use the eDynamicMenu (plugin downloadable on e107coders.org or touchatou.org) the easiest way to  add DHTML menus on your website.<br />
<br />
If you want to use this DHTML menu without eDynamicMenu, you will need to alter your core system. Ask on e107.org, how to do";


// upgrading ... //

$upgrade_add_prefs = "";

$upgrade_remove_prefs = "";

$upgrade_alter_tables = "";
			
$eplug_upgrade_done = "OK you are now using the version 1.01 of this plugin.";

?>