<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/themes/templates/templateh.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
$aj = new textparse;
echo "<?xml version='1.0' encoding='iso-8859-1' ?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>".SITENAME.(defined("PAGE_NAME") ? ": ".PAGE_NAME : "")."</title>
<link rel=\"stylesheet\" href=\"".THEME."style.css\" type=\"text/css\" />
<link rel=\"stylesheet\" href=\"".e_FILE."e107.css\" type=\"text/css\" />";
if(file_exists(e_BASE."favicon.ico")){echo "\n<link rel=\"shortcut icon\" href=\"favicon.ico\" />"; }
if(file_exists(e_FILE."style.css")){ echo "\n<link rel='stylesheet' href='".e_FILE."style.css' type=\"text/css\" />\n"; }
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".CHARSET."\" />
<meta http-equiv=\"content-style-type\" content=\"text/css\" />
".($pref['meta_tag'] ? $aj -> formtparev($pref['meta_tag'])."\n" : "");
/*
if(eregi("forum_post.php", e_SELF) && ($_POST['reply'] || $_POST['newthread'])){
	$tmp = explode(".", e_QUERY);
	echo "<meta http-equiv=\"refresh\" content=\"5;url='".e_BASE."forum_viewforum.php?".$tmp[1]."'>\n";
}
*/
echo "<script type='text/javascript' src='".e_FILE."e107.js'></script>";
if(file_exists(THEME."theme.js")){echo "<script type='text/javascript' src='".THEME."theme.js'></script>";}
if(file_exists(e_FILE."user.js")){echo "<script type='text/javascript' src='".e_FILE."user.js'></script>\n";}

echo "<script type=\"text/javascript\">
<!--\n";
if($pref['log_activate']){
	echo "
document.write( '<link rel=\"stylesheet\" type=\"text/css\" href=\"".e_PLUGIN."log/log.php?referer=' + ref + '&color=' + colord + '&eself=' + eself + '&res=' + res + '\">' );";
}
echo "var listpics = new Array();
";
$handle=opendir(THEME."images");
$nbrpic=0;
while ($file = readdir($handle)){
	if($file != "." && $file != ".."){
		$imagelist[] = $file;
		echo "listpics[".$nbrpic."]='".THEME."images/".$file."';";
		$nbrpic++;
	}
}

closedir($handle);
echo "\nfor(i=0;i<(".$nbrpic."-1);i++){ preloadimages(i,listpics[i]); }
// -->
</script>
</head>
<body>";


$custompage = explode(" ", $CUSTOMPAGES);

if(e_PAGE == "news.php" && $NEWSHEADER){
	parseheader($NEWSHEADER);
}else{
	while(list($key, $kpage) = each($custompage)){
		if(strstr(e_SELF, $kpage)){
			$ph = TRUE;
			break;
		}
	}
	parseheader(($ph ? $CUSTOMHEADER : $HEADER));
}

unset($text);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function parseheader($LAYOUT){
	$tmp = explode("\n", $LAYOUT);
	for($c=0; $c < count($tmp); $c++){ 
		if(preg_match("/[\{|\}]/", $tmp[$c])){
			$str = checklayout($tmp[$c]);
		}else{
			echo $tmp[$c];
		}
	}
}
function checklayout($str){
	$sql = new db;
	global $pref, $style, $userthemes, $udirs, $userclass, $dbq, $menu_pref, $dbq;
	if(strstr($str, "LOGO")){
		echo "<img src='".e_IMAGE."logo.png' alt='Logo' />\n";
	}else if(strstr($str, "SITENAME")){
		echo SITENAME."\n";
	}else if(strstr($str, "SITETAG")){
		echo SITETAG."\n";
	}else if(strstr($str, "SITELINKS")){
		if(!$sql -> db_Select("menus", "*", "(menu_name='edynamic_menu' OR menu_name REGEXP('tree_menu')) AND menu_location!=0")){
			$linktype = substr($str,(strpos($str, "=")+1), 4);
			define("LINKDISPLAY", ($linktype == "menu" ? 2 : 1));
			require_once(e_HANDLER."sitelinks_class.php");
			sitelinks();
		}
	}else if(strstr($str, "MENU")){
		$sql = new db;
		$ns = new e107table;
		$menu = trim(chop(preg_replace("/\{MENU=(.*?)\}/si", "\\1", $str)));
		$sql9 = new db;
		$sql9 -> db_Select("menus", "menu_name,menu_class",  "menu_location='$menu' ORDER BY menu_order");
		while($row = $sql9-> db_Fetch()){
			extract($row);
			if(check_class($menu_class)){
				if(strstr($menu_name, "custom_")){
					require_once(e_PLUGIN."custom/".str_replace("custom_", "", $menu_name).".php");
				} else {
					@include(e_PLUGIN.$menu_name."/languages/".e_LANGUAGE.".php");
					@include(e_PLUGIN.$menu_name."/languages/English.php");
					require_once(e_PLUGIN.$menu_name."/".$menu_name.".php");
				}
			}
			
		}
	}else if(strstr($str, "SETSTYLE")){
		$tmp = explode("=", $str);
		$style = trim(chop(preg_replace("/\{SETSTYLE=(.*?)\}/si", "\\1", $str)));
	}else if(strstr($str, "SITEDISCLAIMER")){
		echo SITEDISCLAIMER.(defined("THEME_DISCLAIMER") && $pref['displaythemeinfo'] ? THEME_DISCLAIMER : "");
	}else if(strstr($str, "CUSTOM")){
		$custom = trim(chop(preg_replace("/\{CUSTOM=(.*?)\}/si", "\\1", $str)));
		if($custom == "login"){
			@include(e_PLUGIN."login_menu/languages/".e_LANGUAGE.".php");
			@include(e_PLUGIN."login_menu/languages/English.php");
			
			if(USER == TRUE){
				echo "<table><tr>\n<td class='mediumtext'>".LOGIN_MENU_L5." ".USERNAME."&nbsp;&nbsp;&nbsp;</td>\n<td>.:.</td>";
				if(ADMIN == TRUE){
					echo "<td><a href='".e_ADMIN.(!$pref['adminstyle'] || $pref['adminstyle'] == "default" ? "admin.php" : $pref['adminstyle'].".php")."'>".LOGIN_MENU_L11."</a></td><td>.:.</td>";
				}
				echo "<td> 
				 <a href='".e_BASE."user.php?id.".USERID."'>".LOGIN_MENU_L13."</a>\n</td><td>.:.</td><td><td> <a href='" . e_BASE . "usersettings.php'>".LOGIN_MENU_L12."</a></td><td>.:.</td><td><a href='".e_BASE."?logout'>".LOGIN_MENU_L8."</a></td><td>.:.</td></tr></table> ";
			}else{
				echo  "<form method='post' action='".e_SELF."'>\n<p>\n".LOGIN_MENU_L1."<input class='tbox' type='text' name='username' size='15' value='$username' maxlength='20' />&nbsp;&nbsp;\n".LOGIN_MENU_L2."<input class='tbox' type='password' name='userpass' size='15' value='' maxlength='20' />&nbsp;&nbsp;\n<input type='checkbox' name='autologin' value='1' />".LOGIN_MENU_L6."&nbsp;&nbsp;\n<input class='button' type='submit' name='userlogin' value='Login' />";
				if($pref['user_reg']){
					echo "&nbsp;&nbsp;<a href='signup.php'>".LOGIN_MENU_L3."</a>";
				}
				echo "</p>\n</form>";
			}
			
		}else if($custom == "search" && (USER || $pref['search_restrict']!=1)){
			$searchflat = TRUE;
			include(e_PLUGIN."search_menu/search_menu.php");
		}else if($custom == "quote"){
			if(!file_exists(e_BASE."quote.txt")){
				$quote = "Quote file not found ($qotd_file)";
			}else{
				$quotes = file(e_BASE."quote.txt");
				$quote = stripslashes(htmlspecialchars($quotes[rand(0, count($quotes))]));
			}
			echo $quote;
		}else if($custom == "clock"){
			$clock_flat = TRUE;
			require_once(e_PLUGIN."clock_menu/clock_menu.php");
		}else if($custom == "welcomemessage"){
			$aj = new textparse;
			$sql -> db_Select("wmessage");
			list($wm_guest, $guestmessage, $wm_active1) = $sql-> db_Fetch();
			list($wm_member, $membermessage, $wm_active2) = $sql-> db_Fetch();
			list($wm_admin, $adminmessage, $wm_active3) = $sql-> db_Fetch();
			if(ADMIN == TRUE && $wm_active3){
				echo $aj -> tpa($adminmessage, "on");
			}else if(USER == TRUE && $wm_active2){
				echo $aj -> tpa($membermessage, "on");
			}else if(USER == FALSE && $wm_active1){
				echo $aj -> tpa($guestmessage, "on");
			}
			define("WMFLAG", TRUE);
		}

	}else if(strstr($str, "BANNER")){
		$campaign = trim(chop(preg_replace("/\{BANNER=(.*?)\}/si", "\\1", $str)));
		mt_srand ((double) microtime() * 1000000);
		$seed = mt_rand(1,2000000000);
		if($campaign != "{BANNER}"){
			$query = "banner_active=1 AND (banner_startdate=0 OR banner_startdate<=".time().") AND (banner_enddate=0 OR banner_enddate>".time().") AND (banner_impurchased=0 OR banner_impressions<=banner_impurchased) AND banner_campaign='$campaign' ORDER BY RAND($seed)";
		}else{
			$query = "banner_active=1 AND (banner_startdate=0 OR banner_startdate<=".time().") AND (banner_enddate=0 OR banner_enddate>".time().") AND (banner_impurchased=0 OR banner_impressions<=banner_impurchased) ORDER BY RAND($seed)";
		}
		$sql = new db;
		if($sql -> db_Select("banner", "*", $query)){
			$row = $sql -> db_Fetch(); extract($row);

			$fileext1 = substr(strrchr($banner_image, "."), 1);
			$fileext2 = substr(strrchr($banner_image, "."), 0);
			if ($fileext1 == swf) {
				echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"468\" height=\"60\">\n<param name=\"movie\" value=\"".e_IMAGE."banners/".$banner_image."\">\n<param name=\"quality\" value=\"high\"><param name=\"SCALE\" value=\"noborder\">\n<embed src=\"".e_IMAGE."banners/".$banner_image."\" width=\"468\" height=\"60\" scale=\"noborder\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed></object>";
			}else if($fileext1 == "php" || $fileext1 == "html" || $fileext1 == "js"){
				include(e_IMAGE."banners/".$banner_image);
			}else{
				echo "<a href='".e_BASE."banner.php?".$banner_id."' onclick=\"window.open('".e_BASE."banner.php?$banner_id'); return false;\"><img src='".e_IMAGE."banners/".$banner_image."' alt='".$banner_clickurl."' style='border:0' /></a>";
			}
			$sql -> db_Update("banner", "banner_impressions=banner_impressions+1 WHERE banner_id='$banner_id' ");
		}
	}else if(strstr($str, "NEWS_CATEGORY")){
		$news_category = trim(chop(preg_replace("/\{NEWS_CATEGORY=(.*?)\}/si", "\\1", $str)));
		require_once(e_PLUGIN."alt_news/alt_news.php");
		alt_news($news_category);
	}

}
?>