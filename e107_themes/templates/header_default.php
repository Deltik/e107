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
echo "<?xml version='1.0' encoding='iso-8859-1' ?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITENAME; if(defined("PAGE_NAME")){ echo ": ".PAGE_NAME; }?></title>
<link rel="stylesheet" href="<?php echo THEME; ?>style.css" />
<link rel="stylesheet" href="<?php echo e_FILE; ?>e107.css" />
<?php
if(file_exists(e_FILE."style.css")){ echo "\n<link rel='stylesheet' href='".e_FILE."style.css' />\n"; }

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".CHARSET."\" />
<meta http-equiv=\"content-style-type\" content=\"text/css\" />
".$aj -> formtparev($pref['meta_tag'])."\n";
if(eregi("forum_post.php", e_SELF) && ($_POST['reply'] || $_POST['newthread'])){
	$tmp = explode(".", e_QUERY);
	echo "<meta http-equiv=\"refresh\" content=\"5;url='".e_BASE."forum_viewforum.php?".$tmp[1]."'>\n";
}


echo "<script type='text/javascript' src='".e_FILE."e107.js'></script>";

if(file_exists(THEME."theme.js")){echo "<script type='text/javascript' src='".THEME."theme.js'></script>";}
if(file_exists(e_FILE."user.js")){echo "<script type='text/javascript' src='".e_FILE."user.js'></script>\n";}

echo "\n
<script type=\"text/javascript\">
<!--
var listpics = new Array();
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
$page = substr(strrchr(e_SELF, "/"), 1);

if(in_array($page, $custompage)){
	parseheader($CUSTOMHEADER);
}else if($page == "news.php" && $NEWSHEADER){
	parseheader($NEWSHEADER);
}else{
	parseheader($HEADER);
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
	global $pref, $style, $userthemes, $udirs, $userclass, $dbq, $menu_pref;
	if(strstr($str, "LOGO")){
		echo "<img src='".e_IMAGE."logo.png' alt='Logo' />\n";
	}else if(strstr($str, "SITENAME")){
		echo SITENAME."\n";
	}else if(strstr($str, "SITETAG")){
		echo SITETAG."\n";
	}else if(strstr($str, "SITELINKS")){
		if(!$sql -> db_Select("menus", "*", "menu_name='tree_menu' AND menu_location!=0")){
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
		$sql9 -> db_Select("menus", "*",  "menu_location='$menu' ORDER BY menu_order");
		while($row = $sql9-> db_Fetch()){
			extract($row);
			$sm = FALSE;
			if(!$menu_class){
				$sm = TRUE;
			}else if($menu_class == 253 && USER){
				$sm = TRUE;
			}else if($menu_class == 254 && ADMIN){
				$sm = TRUE;
			}else if(check_class($menu_class)){
				$sm = TRUE;
			}
			if($sm == TRUE){
				if(strstr($menu_name, "custom_")){
					require_once(e_PLUGIN."custom/".str_replace("custom_", "", $menu_name).".php");
				}else{
					if(@fopen(e_PLUGIN.$menu_name."/languages/".e_LANGUAGE.".php","r")){
						@include(e_PLUGIN.$menu_name."/languages/".e_LANGUAGE.".php");
						require_once(e_PLUGIN.$menu_name."/".$menu_name.".php");
					}else{
						@include(e_PLUGIN.$menu_name."/languages/English.php");
						require_once(e_PLUGIN.$menu_name."/".$menu_name.".php");
					}
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

			include(e_PLUGIN."login_menu/languages/".e_LANGUAGE.".php");

			if($pref['user_reg'] == 1){
				if(USER == TRUE){
					echo "<table><tr>
					<td class='mediumtext'>".LOGIN_MENU_L5." ".USERNAME."&nbsp;&nbsp;&nbsp;</td>
					<td>.:.</td>";
					if(ADMIN == TRUE){
						echo "<td><a href='".e_ADMIN.(!$pref['adminstyle'] || $pref['adminstyle'] == "default" ? "admin.php" : $pref['adminstyle'].".php")."'>".LOGIN_MENU_L11."</a></td><td>.:.</td>";
					}
					echo "<td> <a href='" . e_BASE . "usersettings.php'>".LOGIN_MENU_L12."</a></td><td>.:.</td><td><a href='".e_BASE."index.php?logout'>".LOGIN_MENU_L8."</a></td><td>.:.</td></tr></table> ";
				}else{
					echo  "<form method='post' action='".e_SELF."'>
					<p>
					".LOGIN_MENU_L1."<input class='tbox' type='text' name='username' size='15' value='$username' maxlength='20' />&nbsp;&nbsp;
					".LOGIN_MENU_L2."<input class='tbox' type='password' name='userpass' size='15' value='' maxlength='20' />&nbsp;&nbsp;
					<input type='checkbox' name='autologin' value='1' />".LOGIN_MENU_L6."&nbsp;&nbsp;
					<input class='button' type='submit' name='userlogin' value='Login' />&nbsp;&nbsp;<a href='signup.php'>".LOGIN_MENU_L3."</a>
					</p>
					</form>";
				}
			}
		}else if($custom == "search"){
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
			echo "<a href='".e_BASE."banner.php?".$banner_id."'><img src='".e_IMAGE."banners/".$banner_image."' alt='".$banner_clickurl."' style='border:0' /></a>";
			$sql -> db_Update("banner", "banner_impressions=banner_impressions+1 WHERE banner_id='$banner_id' ");
		}
	}else if(strstr($str, "NEWS_CATEGORY")){
		$news_category = trim(chop(preg_replace("/\{NEWS_CATEGORY=(.*?)\}/si", "\\1", $str)));
		require_once(e_PLUGIN."alt_news/alt_news.php");
		alt_news($news_category);
	}

}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
?>