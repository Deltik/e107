<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/sitelinks_class.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
include(e_LANGUAGEDIR.e_LAN."/lan_sitelinks.php");
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function sitelinks(){
	/*
	# Render style links
	# - parameters		none
	# - return				parsed text
	# - scope					null
	*/
	global $pref;
	$text = PRELINK;
	if(defined("LINKCLASS")){
		$linkadd = " class='".LINKCLASS."' ";
	}
	if(ADMIN == TRUE){
		$linkstart = (file_exists(e_IMAGE."link_icons/admin.png") ? preg_replace("/\<img.*\>/si", "", LINKSTART)." " : LINKSTART);
		$text .= $linkstart.(file_exists(e_IMAGE."link_icons/admin.png") ? "<img src='".e_IMAGE."link_icons/admin.png' alt='' style='vertical-align:middle' /> " : "")."<a".$linkadd." href=\"".e_ADMIN.(!$pref['adminstyle'] || $pref['adminstyle'] == "default" ? "admin.php" : $pref['adminstyle'].".php")."\">Admin Area</a>".LINKEND."\n";
	}
	$sql = new db; $sql2 = new db;
	$sql -> db_Select("links", "*", "link_category='1' && link_name NOT REGEXP('submenu') ORDER BY link_order ASC");
	while($row = $sql -> db_Fetch()){
		extract($row);
		if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){

			$linkstart = ($link_button ? preg_replace("/\<img.*\>/si", "", LINKSTART)." " : LINKSTART);
			switch ($link_open) { 
				case 1:
					$link_append = " onclick=\"window.open('$link_url'); return false;\"";

				break; 
				case 2:
				   $link_append = " target=\"_parent\"";
				break;
				case 3:
				   $link_append = " target=\"_top\"";
				break;
				default:
				   unset($link_append);
			}
			if(!strstr($link_url, "http:")){ $link_url = e_BASE.$link_url; }
			if($link_open == 4){
				$text .=  $linkstart.($link_button ? "<img src='".e_IMAGE."link_icons/$link_button' alt='' style='vertical-align:middle' />" : "").($link_url ? "<a".$linkadd." href=\"javascript:open_window('".$link_url."')\">".$link_name."</a>" : $link_name).LINKEND."\n";
			}else{
				$text .=  $linkstart.($link_button ? "<img src='".e_IMAGE."link_icons/$link_button' alt='' style='vertical-align:middle' />" : "").($link_url ? "<a".$linkadd." href=\"".$link_url."\"".$link_append.">".$link_name."</a>" : $link_name).LINKEND."\n";
			}

			if($sql2 -> db_Select("links", "*", "link_name REGEXP('submenu.".$link_name."') ORDER BY link_order ASC")){
				$main_linkname = $link_name;
				while($row = $sql2 -> db_Fetch()){
					extract($row);
					$link_name = str_replace("submenu.".$main_linkname.".", "", $link_name);
					if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
						$linkstart = ($link_button ? preg_replace("/\<img.*\>/si", "", LINKSTART)." " : LINKSTART);
						switch ($link_open) { 
							case 1:
								$link_append = " onclick=\"window.open('$link_url'); return false;\"";

							break; 
							case 2:
							   $link_append = " target=\"_parent\"";
							break;
							case 3:
							   $link_append = " target=\"_top\"";
							break;
							default:
							   unset($link_append);
						}

						if(!strstr($link_url, "http:")){ $link_url = e_BASE.$link_url; }
						if($link_open == 4){
							$text .=  $linkstart."&nbsp;&nbsp;".($link_button ? "<img src='".e_IMAGE."link_icons/$link_button' alt='' style='vertical-align:middle' />" : "")."<a".$linkadd." href=\"javascript:open_window('".$link_url."')\">".$link_name."</a>".LINKEND."\n";
						}else{
							$text .=  $linkstart."&nbsp;&nbsp;".($link_button ? "<img src='".e_IMAGE."link_icons/$link_button' alt='' style='vertical-align:middle' />" : "")."<a".$linkadd." href=\"".$link_url."\"".$link_append.">".$link_name."</a>".LINKEND."\n";
						}
					}

				}

			}

		}
		
	}
	$text .= POSTLINK;
	if(LINKDISPLAY == 2){
		$ns = new e107table;
		$ns -> tablerender(LAN_183, $text);
	}else{
		echo $text;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
?>