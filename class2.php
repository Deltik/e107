<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/class2.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

// If you need to change the names of any of your directories, change the value here then rename the respective folder on your server ...
$ADMIN_DIRECTORY = "e107_admin/";
$FILES_DIRECTORY = "e107_files/";
$IMAGES_DIRECTORY = "e107_images/";
$THEMES_DIRECTORY = "e107_themes/";
$PLUGINS_DIRECTORY = "e107_plugins/";
$HANDLERS_DIRECTORY = "e107_handlers/";
$LANGUAGES_DIRECTORY = "e107_languages/";
$HELP_DIRECTORY = "e107_docs/help/";

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//initialise
//ob_start ("ob_gzhandler");
ob_start ();
$timing_start = explode(' ', microtime());

if(!$mySQLserver){
	@include("e107_config.php");
	$a=0;
	while(!$mySQLserver && $a<5){
		$a++;
		$p.="../";
		@include($p."e107_config.php");
	}
	if(!defined("e_HTTP")){ header("Location:install.php"); exit; }
}

$url_prefix=substr($_SERVER['PHP_SELF'],strlen(e_HTTP),strrpos($_SERVER['PHP_SELF'],"/")+1-strlen(e_HTTP));
$tmp=explode("?",$url_prefix);
$num_levels=substr_count($tmp[0],"/");
for($i=1;$i<=$num_levels;$i++){ 
	$link_prefix.="../";
}
$e_BASE = $link_prefix;
define("e_QUERY", eregi_replace("(.?)([a-zA-Z]*\(.*\))(.*)", "\\1\\3", eregi_replace("&|/?PHPSESSID.*", "", $_SERVER['QUERY_STRING'])));
$_SERVER['QUERY_STRING'] = e_QUERY;
define("e_IMAGE", $e_BASE.$IMAGES_DIRECTORY);
define("e_THEME", $e_BASE.$THEMES_DIRECTORY);
define("e_PLUGIN", (defined("CORE_PATH") ? $e_BASE.SUBDIR_SITE."/".$PLUGINS_DIRECTORY : $e_BASE.$PLUGINS_DIRECTORY));
define("e_FILE", $e_BASE.$FILES_DIRECTORY);
define("e_HANDLER", $e_BASE.$HANDLERS_DIRECTORY);
define("e_LANGUAGEDIR", $e_BASE.$LANGUAGES_DIRECTORY);
define("e_DOCS", $e_BASE.$HELP_DIRECTORY);
define("e_DOCROOT",$_SERVER['DOCUMENT_ROOT']."/");
define("e_UC_PUBLIC", 0);
define("e_UC_READONLY", 251);
define("e_UC_MEMBER", 253);
define("e_UC_ADMIN", 254);
define("e_UC_NOBODY", 255);
define("ADMINDIR", $ADMIN_DIRECTORY);

include(e_HANDLER."errorhandler_class.php");
set_error_handler("error_handler");

if(!$mySQLuser){ header("location:install.php"); exit; }
define("MPREFIX", $mySQLprefix);

require_once(e_HANDLER."message_handler.php");
require_once(e_HANDLER."mysql_class.php");

$sql = new db;
$sql -> db_SetErrorReporting(TRUE);
$merror = $sql -> db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);

if($merror == "e1"){ message_handler("CRITICAL_ERROR", 6,  ": generic, ", "class2.php"); exit;
}else if($merror == "e2"){ message_handler("CRITICAL_ERROR", 7,  ": generic, ", "class2.php"); exit;}

$sql -> db_Select("core", "*", "e107_name='pref' ");
$row = $sql -> db_Fetch();

$tmp = stripslashes($row['e107_value']);
$pref=unserialize($tmp);
if(!is_array($pref)){
	$pref=unserialize($row['e107_value']);
	if(!is_array($pref)){
		($sql -> db_Select("core", "*", "e107_name='pref' ") ? message_handler("CRITICAL_ERROR", 1,  __LINE__, __FILE__) : message_handler("CRITICAL_ERROR", 2,  __LINE__, __FILE__));
		if($sql -> db_Select("core", "*", "e107_name='pref_backup' ")){
			$row = $sql -> db_Fetch(); extract($row);
			$tmp = addslashes(serialize($e107_value ));
			$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='pref' ");
			message_handler("CRITICAL_ERROR", 3,  __LINE__, __FILE__);
		}else{
			message_handler("CRITICAL_ERROR", 4,  __LINE__, __FILE__);
			exit;
		}					
	}
}
if(!$pref['cookie_name']){ $pref['cookie_name'] = "e107cookie"; }
if($pref['user_tracking'] == "session"){ session_start(); }

define("e_SELF", ($pref['ssl_enabled'] ? "https://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']) : "http://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME'])));

$sql -> db_Select("core", "*", "e107_name='menu_pref' ");
$row = $sql -> db_Fetch();
$tmp = stripslashes($row['e107_value']);
$menu_pref=unserialize($tmp);

$page = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
define("e_PAGE", $page);
if($pref['frontpage'] && $pref['frontpage_type'] == "splash"){
	$ip = getip();
	if(!$sql -> db_Select("online", "*", "online_ip='$ip' ")){
		online();
		if(is_numeric($pref['frontpage'])){
			header("location: article.php?".$pref['frontpage'].".255");
			exit;
		}else if(eregi("http", $pref['frontpage'])){
			header("location: ".$pref['frontpage']);
			exit;
		}else{
			header("location: ".$e_BASE.$pref['frontpage'].".php");
			exit;
		}
	}
}

init_session();
online();

$fp = ($pref['frontpage'] ? $pref['frontpage'].".php" : "news.php index.php");
if($pref['membersonly_enabled'] && !USER && !strstr($fp, e_PAGE) && e_PAGE != "signup.php" && e_PAGE != "customsignup.php"){
	echo "<br /><br /><div style='text-align:center; font: 12px Verdana, Tahoma'>This is a restricted area, to access it either log in or <a href='signup.php'>signup for an account</a>.<br /><a href='index.php'>Click here to return to front page</a>.</div>";
	exit;
}

$sql -> db_Delete("tmp", "tmp_time < '".(time()-300)."' AND tmp_ip!='data' AND tmp_ip!='adminlog' AND tmp_ip!='submitted_link' AND tmp_ip!='var_store' ");

if($pref['flood_protect'] == 1){
	$sql -> db_Delete("flood", "flood_time+'".$pref['flood_time']."'<'".time()."' ");
	$sql -> db_Insert("flood", " '".$_SERVER['PHP_SELF']."', '".time()."' ");
	$hits = $sql -> db_Count("flood", "(*)", "WHERE flood_url = '".$_SERVER['PHP_SELF']."' ");
	if($hits > $pref['flood_hits'] && $pref['flood_hits'] != ""){
		die();
	}
}

define("SITENAME", $pref['sitename']);
define("SITEURL", (substr($pref['siteurl'], -1) == "/" ? $pref['siteurl'] : $pref['siteurl']."/"));
define("SITEBUTTON", $pref['sitebutton']);
define("SITETAG", $pref['sitetag']);
define("SITEDESCRIPTION", $pref['sitedescription']);
define("SITEADMIN", $pref['siteadmin']);
define("SITEADMINEMAIL", $pref['siteadminemail']);

$search = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "�");
$replace =  array("\"", "'", "\\", '\"', "\'", "&#169;");
define("SITEDISCLAIMER", str_replace($search, $replace, $pref['sitedisclaimer']));

$language = ($pref['sitelanguage'] ? $pref['sitelanguage'] : "English");

define("e_LAN", $language);
define(e_LANGUAGE, (!USERLAN || !defined("USERLAN") ? $language : USERLAN));

@include(e_LANGUAGEDIR.$language."/".$language.".php");

if($pref['maintainance_flag'] && ADMIN == FALSE && !eregi("admin", e_SELF)){
	@include(e_LANGUAGEDIR.e_LANGUAGE."/lan_sitedown.php");
	require_once($e_BASE."sitedown.php"); exit;
}

if(defined("CORE_PATH") && ($page == "index.php" || !$page)){ $page = "news.php"; }

if(strstr(e_SELF, $ADMIN_DIRECTORY) || strstr(e_SELF, "admin.php")){
	(file_exists(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_".e_PAGE) ? @include(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_".e_PAGE) : @include(e_LANGUAGEDIR."English/admin/lan_".e_PAGE));
}else{
	(file_exists(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE) ? @include(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE) : @include(e_LANGUAGEDIR."English/lan_".e_PAGE));
}


if(IsSet($_POST['userlogin'])){
	require_once(e_HANDLER."login.php");
	$usr = new userlogin($_POST['username'], $_POST['userpass'], $_POST['autologin']);
}

if(e_QUERY == "logout"){
	if($pref['user_tracking'] == "session"){ session_destroy(); $_SESSION[$pref['cookie_name']] = ""; }
	cookie($pref['cookie_name'], "", (time()-2592000));
	echo "<script type='text/javascript'>document.location.href='".$e_BASE."index.php'</script>\n";
	exit;
}
ban();
			
define("TIMEOFFSET", $pref['time_offset']);
define("FLOODTIME", $pref['flood_time']);
define("FLOODHITS", $pref['flood_hits']);

if(strstr(e_SELF, $ADMIN_DIRECTORY) && $pref['admintheme'] && !$_POST['sitetheme']){
	if(strstr(e_SELF, "menus.php")){
		define("THEME", e_THEME.$pref['sitetheme']."/");
	}else if(strstr(e_SELF, "newspost.php")){
		define("MAINTHEME", e_THEME.$pref['sitetheme']."/");
		define("THEME", e_THEME.$pref['admintheme']."/");
	}else{
		define("THEME", e_THEME.$pref['admintheme']."/");
	}
}else{
	if(USERTHEME != FALSE && USERTHEME != "USERTHEME"){
		define("THEME", (@fopen(e_THEME.USERTHEME."/theme.php", r) ? e_THEME.USERTHEME."/" : e_THEME."e107/"));
	}else{
		define("THEME", (@fopen(e_THEME.$pref['sitetheme']."/theme.php", r) ? e_THEME.$pref['sitetheme']."/" : e_THEME."e107/"));
	}
}
require_once(THEME."theme.php");

if($pref['anon_post'] ? define("ANON", TRUE) : define("ANON", FALSE));
if(Empty($pref['newsposts']) ? define("ITEMVIEW", 15) : define("ITEMVIEW", $pref['newsposts']));
if($pref['flood_protect']){  define(FLOODPROTECT, TRUE); define(FLOODTIMEOUT, $pref['flood_timeout']); }

if($layout != "_default"){
	define ("HEADERF", e_THEME."templates/header".$layout.".php");
	define ("FOOTERF", e_THEME."templates/footer".$layout.".php");
}else{
	define ("HEADERF", e_THEME."templates/header_default.php");
	define ("FOOTERF", e_THEME."templates/footer_default.php");
}

define("LOGINMESSAGE", "");
$ns = new e107table;

define("OPEN_BASEDIR", (ini_get('open_basedir') ? TRUE : FALSE));
define("SAFE_MODE", (ini_get('safe_mode') ? TRUE : FALSE));
define("MAGIC_QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));
define("FILE_UPLOADS", (ini_get('file_uploads') ? TRUE : FALSE));
define("INIT", TRUE);

define("e_BASE", (defined("CORE_PATH") ? e_HTTP."/index.php?" : $e_BASE));

define("e_ADMIN", $e_BASE.$ADMIN_DIRECTORY);
define("e_ADMIN_L", (defined("CORE_PATH") ? e_HTTP."/admin.php?" : $e_BASE.$ADMIN_DIRECTORY));



//require_once(e_HANDLER."IPB_int.php");

//require_once(e_HANDLER."debug_handler.php");

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class e107table{
	function tablerender($caption, $text, $mode="default"){
		/*
		# Render style table
		# - parameter #1:		string $caption, caption text
		# - parameter #2:		string $text, body text
		# - return				null
		# - scope					public
		*/
		tablestyle($caption, $text, $mode);
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class textparse{

	var $emotes;
	var $searcha;
	var $searchb;
	var $replace;
	var $profan;

	function textparse(){
	// constructor
		global $pref;

		if($pref['profanity_filter']){
			$this->profan = str_replace(",", "|", $pref['profanity_words']);
		}

		if($pref['smiley_activate']){
			$sql = new db;
			$sql -> db_Select("core", "*", "e107_name='emote'");
			$row = $sql -> db_Fetch(); extract($row);
			$this->emotes = unserialize($e107_value);

			$c=0;
			while(list($code, $name) = each($this->emotes[$c])){
				$this->searcha[$c] = " ".$code;
				$this->searchb[$c] = "\n".$code;
				$this->replace[$c] = " <img src='".e_IMAGE."emoticons/$name' alt='' style='vertical-align:middle; border:0' /> ";
				$c++;
			}
		}
	}
	
	function editparse($text, $mode="off"){
		/*
		# Edit parse
		# - parameter #1:		string $text, text to parse
		# - parameter #2:		string $mode, on=links not parsed, default=off
		# - return				parsed text
		# - scope					public
		*/
//		$text = stripslashes($text);
		$search = array();
		$replace = array();
		$search[0] = "/\<div class=\"indent\"\>\<i\>Originally posted by (.*?)\<\/i\>\<br \/\>\"(.*?)\"\<\/div\>/si";
		$replace[0] = '[quote=\1]\2[/quote]';
		$search[1] = "/\<div class=\"indent\"\>\<i\>Originally posted by (.*?)\<\/i\> ...\<br \/\>\"(.*?)\"\<\/div\>/si";
		$replace[1] = '[quote=\1]\2[/quote]';
		$search[2] = "/\<div class=\"indent\"\>(.*?)\<\/div\>/si";
		$replace[2] = '[blockquote]\1[/blockquote]';
		$search[3] = "/\<b>(.*?)\<\/b\>/si";
		$replace[3] = '[b]\1[/b]';
		$search[4] = "/\<i>(.*?)\<\/i\>/si";
		$replace[4] = '[i]\1[/i]';
		$search[5] = "/\<u>(.*?)\<\/u\>/si";
		$replace[5] = '[u]\1[/u]';
		$search[6] = "/\<img alt=\"\" src=\"(.*?)\" \/>/si";
		$replace[6] = '[img]\1[/img]';
		$search[7] =  "/\<div style=\"text-align:center\"\>(.*?)\<\/div\>/si";
		$replace[7] = '[center]\1[/center]';
		$search[8] =  "/\<div style=\"text-align:left\"\>(.*?)\<\/div\>/si";
		$replace[8] = '[left]\1[/left]';
		$search[9] =  "/\<div style=\"text-align:right\"\>(.*?)\<\/div\>/si";
		$replace[9] = '[right]\1[/right]';
		$search[10] = "/\<code>(.*?)\<\/code\>/si";
		$replace[10] = '[code]\1[/code]';
		if($mode == "off"){
			$search[11] = "/\<a href=\"(.*?)\">(.*?)<\/a>/si";
			$replace[11] = '[link=\\1]\\2[/link]';
		}
		$search[12] = "#\[edited\](.*?)\[/edited\]#si";
		$replace[12] = '';
		$text = preg_replace($search, $replace, $text);
		return $text;
	}
	
	function tpa($text, $mode="off"){
		/*
		# Post parse
		# - parameter #1:		string $text, text to parse
		# - parameter #2:		string $mode, on=line breaks not replaced, default off
		# - return					parsed text
		# - scope					public
		*/
		global $pref;

		if($pref['profanity_filter'] && $this->profan){
			$text = eregi_replace($this->profan, $pref['profanity_replace'], $text);
		}
		if($pref['smiley_activate']){
			$text = str_replace($this->searcha, $this->replace, $text);
			$text = str_replace($this->searchb, $this->replace, $text);
		}
		$text = str_replace("$", "&#36;", $text);
		$search[0] = "#\[link\]([a-z]+?://){1}(.*?)\[/link\]#si";
		$replace[0] = '<a href="\1\2">\1\2</a>';
		$search[1] = "#\[link\](.*?)\[/link\]#si";
		$replace[1] = '<a href="http://\1">\1</a>';
		$search[2] = "#\[link=([a-z]+?://){1}(.*?)\](.*?)\[/link\]#si";
		$replace[2] = '<a href="\1\2">\3</a>';
		$search[3] = "#\[link=(.*?)\](.*?)\[/link\]#si";
		$replace[3] = '<a href="http://\1">\2</a>';
		$search[4] = "#\[email\](.*?)\[/email\]#si";
		$replace[4] = '<a href="mailto:\1">\1</a>';
		$search[5] = "#\[email=(.*?){1}(.*?)\](.*?)\[/email\]#si";
		$replace[5] = '<a href="mailto:\1\2">\3</a>';
		$search[6] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
		$replace[6] = '<a href="\1\2">\1\2</a>';
		$search[7] = "#\[url\](.*?)\[/url\]#si";
		$replace[7] = '<a href="http://\1">\1</a>';
		$search[8] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
		$replace[8] = '<a href="\1\2">\3</a>';
		$search[9] = "/\[quote=(.*?)\](.*?)\[\/quote\]/si";
		$replace[9] = '<div class=\'indent\'><i>Originally posted by \1</i> ...<br />"\2"</div>';
		$search[10] = "#\[b\](.*?)\[/b\]#si";
		$replace[10] = '<b>\1</b>';
		$search[11] = "#\[i\](.*?)\[/i\]#si";
		$replace[11] = '<i>\1</i>';
		$search[12] = "#\[u\](.*?)\[/u\]#si";
		$replace[12] = '<u>\1</u>';
		$search[13] = "#\[img\](.*?)\[/img\]#si";
		if($pref['image_post'] || $mode == "on" || ADMIN){
			$replace[13] = '<img src=\'\1\' alt=\'\' style=\'vertical-align:middle; border:0\' />';
		}else if(!$pref['image_post_disabled_method']){
			$replace[13] = '\1';
		}else{
			$replace[13] = '';
		}

		$search[14] = "#\[center\](.*?)\[/center\]#si";
		$replace[14] = '<div style=\'text-align:center\'>\1</div>';
		$search[15] = "#\[left\](.*?)\[/left\]#si";
		$replace[15] = '<div style=\'text-align:left\'>\1</div>';
		$search[16] = "#\[right\](.*?)\[/right\]#si";
		$replace[16] = '<div style=\'text-align:right\'>\1</div>';
		$search[17] = "#\[blockquote\](.*?)\[/blockquote\]#si";
		$replace[17] = '<div class=\'indent\'>\1</div>';
		$search[19] = "/\[color=(.*?)\](.*?)\[\/color\]/si";
		$replace[19] = '<span style=\'color:\1\'>\2</span>';
		$search[20] = "/\[size=([1-2]?[0-9])\](.*?)\[\/size\]/si";
		$replace[20] = '<span style=\'font-size:\1px\'>\2</span>';
		$search[21] = "#\[edited\](.*?)\[/edited\]#si";
		$replace[21] = '<span class=\'smallblacktext\'>[ \1 ]</span>';
		$search[22] = "#onmouseover|onclick|onmousedown|onmouseup|ondblclick|onmouseout|onmousemove|onload|iframe|expression#si";
		$replace[22] = '';
		$search[23] = "#\[br\]/si";
		$replace[23] = '<br />';

		if($pref['forum_attach'] && FILE_UPLOADS){
			$search[24] = "#\[file=(.*?)\](.*?)\[/file\]#si";
			$replace[24] = '<a href="\1"><img src="'.e_IMAGE.'generic/attach1.png" alt="" style="border:0; vertical-align:middle" /> \2</a>';
		}else{
			$search[24] = "#\[file=(.*?)\](.*?)\[/file\]#si";
			$replace[24] = '[ file attachment disabled ]';
		}

		$search[25] = "#\[quote\](.*?)\[/quote\]#si";
		$replace[25] = '<i>"\1"</i>';

		$text = preg_replace($search, $replace, $text);
		if(MAGIC_QUOTES_GPC){ $text = stripslashes($text); }
		$search = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;span", "&lt;/span");
		$replace =  array("\"", "'", "\\", '\"', "\'", "<span", "</span");
		$text = str_replace($search, $replace, $text);
		if($mode != "nobreak"){ $text = nl2br($text); }
		$text = str_replace("<br /><br />", "<br />", $text);
		$text = " " . $text;
		$text = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" onclick="window.open(\'\2://\3\'); return false;">\2://\3</a>', $text);
		$text = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" onclick="window.open(\'http://\2.\3\'); return false;">\2.\3</a>', $text);
		$text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "<script type=\"text/javascript\">document.write('<a href=\"mailto:'+'\\2'+'@'+'\\3\">'+'\\2'+'@'+'\\3'+'</a>')</script>", $text);
		$text = substr($text, 1);
		$text = code($text, "notdef");
		$text = html($text);
		return $text;
	}

	function formtpa($text, $mode="admin"){
		global $sql, $pref;

		if($mode != "admin"){
			for($r=0; $r<=strlen($text); $r++){
				$chars[$text[$r]] = 1;
			}
			$ch = array_count_values($chars);
			if((strlen($text) > 50 && $ch[1] < 10) || (strlen($text) > 10 && $ch[1] < 3) || (strlen($text) > 100 && $ch[1] < 20)){
				echo "<script type='text/javascript'>document.location.href='index.php'</script>\n";
				exit;
			}
			$text = code($text);
			if(!$pref['html_post']){ $text = str_replace("<", "&lt;", $text); str_replace(">", "&gt;", $text); }
			$text = str_replace("<script", "&lt;script", $text);
			$text = str_replace("<iframe", "&lt;iframe", $text);
			if(($pref['image_post_class'] == 253 && !USER) || ($pref['image_post_class'] == 254 && !ADMIN)){
				$text = preg_replace("#\[img\](.*?)\[/img\]#si", '', $text);
			}else if(!check_class($pref['image_post_class'])){
				$text = preg_replace("#\[img\](.*?)\[/img\]#si", '', $text);
			}
		}

		if(MAGIC_QUOTES_GPC){ $text = stripslashes($text); }
		$search = array("\"", "'", "\\", '\"', "\'", "$");
		$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&#036;");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function formtparev($text){
		$search = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;");
		$replace = array("\"", "'", "\\", '\"', "\'");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class convert{
	
	function convert_date($datestamp, $mode="long"){
		/*
		# Date convert
		# - parameter #1:		string $datestamp, unix stamp
		# - parameter #2:		string $mode, date format, default long
		# - return				parsed text
		# - scope					public
		*/
		global $pref;

		$datestamp += (TIMEOFFSET*3600);
		if($mode == "long"){
			return strftime($pref['longdate'], $datestamp);
		}else if($mode == "short"){
			return strftime($pref['shortdate'], $datestamp);
		}else{
			return strftime($pref['forumdate'], $datestamp);
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function check_class($var, $userclass=USERCLASS, $debug=FALSE){
	if(preg_match ("/^([0-9]+)$/", $var)){
		if($var == e_UC_MEMBER && USER==TRUE){return TRUE;}
		if($var == e_UC_PUBLIC){return TRUE;}
		if($var == e_UC_NOBODY) {return FALSE;}
		if($var == e_UC_ADMIN && ADMIN) {return TRUE;}
		if($var == e_UC_READONLY){return TRUE;}
	}
	if($debug){ echo "USERCLASS: ".$userclass.", \$var = $var : "; }
	if(!defined("USERCLASS") || $userclass == ""){
		if($debug){ echo "FALSE<br />"; }
		return FALSE;
	}
	// user has classes set - continue
	if(preg_match ("/^([0-9]+)$/", $var)){
		$tmp = explode(".", $userclass);
		if(is_numeric(array_search($var,$tmp))){
			if($debug){ echo "TRUE<br />"; }
			return TRUE;
		}
	}else{
		// var is name of class ...
		$sql = new db;
		if($sql -> db_Select("userclass_classes", "*", "userclass_name='$var' ")){
			$row = $sql -> db_Fetch();
			$tmp = explode(".", $userclass);
			if(is_numeric(array_search($row['userclass_id'],$tmp))){
				if($debug){ echo "TRUE<br />"; }
				return TRUE;
			}
		}
	}
	if($debug){  echo "NOTNUM! FALSE<br />"; }
	return FALSE;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function getperms($arg, $ap = ADMINPERMS){
	global $PLUGINS_DIRECTORY;
	if($ap == "0"){return TRUE;}
	if($ap == ""){return FALSE;}
	$ap = ".".$ap;
	if($arg == "P" && preg_match("#(.*?)/".$PLUGINS_DIRECTORY."(.*?)/(.*?)#",e_SELF,$matches)){
		$psql = new db;
		if($psql -> db_Select("plugin","plugin_id","plugin_path = '".$matches[2]."' ")){
			$row = $psql -> db_Fetch();
			$arg = "P".$row[0];
		}
    }
	return (preg_match("#\.".$arg."\.#", $ap) ? TRUE : FALSE);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function save_prefs($table = "core", $uid=USERID){
	global $pref, $user_pref;
	$sql = new db;
	if($table == "core"){
		$tmp = addslashes(serialize($pref));
		$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='pref'");
	}else{
		$tmp = addslashes(serialize($user_pref));
		$sql -> db_Update("user", "user_prefs='$tmp' WHERE user_id=$uid");
		return $tmp;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function online(){
	$page = e_SELF;
	$sql = new db;
	$ip = getip();
	$udata = (USER ? USERID.".".USERNAME : 0);
	if($sql -> db_Delete("online", "online_timestamp < '".(time()-300)."' OR online_ip='$ip' OR (online_user_id!=0 AND online_user_id='$udata') ")){
		define("NOSPLASH", TRUE); // first visit to site
	}
	$sql -> db_Insert("online", " '".time()."', 'null', '".$udata."', '$ip', '".$page."' ");

	$total_online = $sql -> db_Count("online");
	if($members_online = $sql -> db_Select("online", "*", "online_user_id!='0' ")){
		while($row = $sql -> db_Fetch()){
			extract($row);
			$oid = substr($online_user_id, 0, strpos($online_user_id, "."));
			$oname = substr($online_user_id, (strpos($online_user_id, ".")+1));
			$member_list .= "<a href='".e_BASE."user.php?id.$oid'>$oname</a> ";
		}
	}
	define("TOTAL_ONLINE", $total_online);
	define("MEMBERS_ONLINE", $members_online);
	define("GUESTS_ONLINE", $total_online - $members_online);
	define("ON_PAGE", $sql -> db_Select("online", "*", "online_location='$page' "));
	define("MEMBER_LIST", $member_list);
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function cachevars($id, $var){
	global $cachevar;
	$cachevar[$id] = $var;
}
function getcachedvars($id){
	global $cachevar;
	return ($cachevar[$id] ? $cachevar[$id] : FALSE);
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class gettime{
	function gettime(){
		/* Constructor
		# Get microtime
		#
		# - parameters		none
		# - return				microtime
		# - scope					public
		*/
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
    } 
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function getip(){
	/*
	# Get IP address
	#
	# - parameters		none
	# - return				valid IP address
	# - scope					public
	*/
	if(getenv('HTTP_X_FORWARDED_FOR')){
		$ip = $_SERVER['REMOTE_ADDR'];
		if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)){
			$ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
			$ip = preg_replace($ip2, $ip, $ip3[1]);
		}
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if($ip == ""){ $ip = "x.x.x.x"; }
	return $ip;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class floodprotect{
	function flood($table, $orderfield){
		/*
		# Test for possible flood
		#
		# - parameter #1		string $table, table being affected
		# - parameter #2		string $orderfield, date entry in respective table
		# - return				boolean
		# - scope					public
		*/
		$sql = new db;
		if(FLOODPROTECTION == TRUE){
			$sql -> db_Select($table, "*", "ORDER BY ".$orderfield." DESC LIMIT 1", "no_where");
			$row = $sql -> db_Fetch();
			return ($row[$orderfield] > (time() - FLOODTIMEOUT) ? FALSE : TRUE);
		}else{
			return TRUE;
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function init_session(){
		/*
		# Validate user
		#
		# - parameters		none
		# - return				boolean
		# - scope				public
		*/
	global $sql, $pref, $user_pref, $sql;

	if(!$_COOKIE[$pref['cookie_name']] && !$_SESSION[$pref['cookie_name']]){
		define("USER", FALSE); define("USERTHEME", FALSE); define("ADMIN", FALSE);
	}else{
		$tmp = ($_COOKIE[$pref['cookie_name']] ? explode(".", $_COOKIE[$pref['cookie_name']]) : explode(".", $_SESSION[$pref['cookie_name']])); $uid = $tmp[0]; $upw = $tmp[1];
		if(Empty($upw)){	 // corrupt cookie?
			cookie($pref['cookie_name'], "", (time()-2592000));
			$_SESSION[$pref['cookie_name']] = "";
			session_destroy();
			define("ADMIN", FALSE); define("USER", FALSE); define("LOGINMESSAGE", "Corrupted cookie detected - logged out.<br /><br />");
			return(FALSE);
		}
		if($sql -> db_Select("user", "*", "user_id='$uid' AND user_password='$upw' ")){
			$result = $sql -> db_Fetch(); extract($result);
			define("USERID", $user_id); define("USERNAME", $user_name); define("USERURL", $user_website); define("USEREMAIL", $user_email); define("USER", TRUE); define("USERLV", $user_lastvisit); define("USERVIEWED", $user_viewed); define("USERCLASS", $user_class); define("USERREALM", $user_realm);
			if($user_ban == 1){ exit; }
			$user_pref = unserialize($user_prefs);
			if(IsSet($_POST['settheme'])){
				$user_pref['sitetheme'] = ($pref['sitetheme'] == $_POST['sitetheme'] ? "" : $_POST['sitetheme']);
				save_prefs($user);
				$pref['cachestatus'] = 0;
				save_prefs();
			}
			if(IsSet($_POST['setlanguage'])){
				$user_pref['sitelanguage'] = ($pref['sitelanguage'] == $_POST['sitelanguage'] ? "" : $_POST['sitelanguage']);
				save_prefs($user);
			}
			if($user_pref['sitetheme'] && @fopen(e_THEME.$user_pref['sitetheme']."/theme.php","r")){
				define("USERTHEME", $user_pref['sitetheme']);
			}else{
				define("USERTHEME", FALSE);
			}
			if($user_pref['sitelanguage'] && @fopen(e_LANGUAGEDIR.$user_pref['sitelanguage']."/lan_".e_PAGE,"r")){
				define("USERLAN", $user_pref['sitelanguage']);
			}else{
				define("USERLAN", FALSE);
			}
			if($user_currentvisit + 3600 < time()){
				$sql -> db_Update("user", "user_visits=user_visits+1 WHERE user_name='".USERNAME."' ");
				$sql -> db_Update("user", "user_lastvisit='$user_currentvisit', user_currentvisit='".time()."', user_viewed='$r' WHERE user_name='".USERNAME."' ");
			}
			if($user_admin){
				define("ADMIN", TRUE); define("ADMINID", $user_id); define("ADMINNAME", $user_name); define("ADMINPERMS", $user_perms); define("ADMINEMAIL", $user_email); define("ADMINPWCHANGE", $user_pwchange);
			}else{
				define("ADMIN", FALSE);
			}
		}else{
			define("USER", FALSE); define("USERTHEME", FALSE); define("ADMIN", FALSE);
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function ban(){
	$sql = new db;
	$ip = getip();
	if($sql -> db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' OR banlist_ip='".USEREMAIL."' OR banlist_ip='$ip' ")){
		// enter a message here if you want some text displayed to banned users ...
		exit;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function retrieve_cache($query){
	global $sql;
	if(!is_object($sql)){
		$sql = new db;
	}
	if($sql -> db_Select("cache", "*", "cache_url='$query' ")){
		$row = $sql -> db_Fetch(); extract($row);
		return stripslashes($cache_data);
	}else{
		return FALSE;
	}
}

function set_cache($query, $text){
	global $pref, $sql;
	if($pref['cachestatus'] && !strstr(e_BASE, "../")){
		$sql -> db_Insert("cache", "'$query', '".time()."', '".mysql_escape_string($text)."' ");
	}
}

function clear_cache($query){
	global $pref, $sql;
	if($pref['cachestatus']){
		$sql -> db_Delete("cache", "cache_url LIKE '%".$query."%' ");
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function cookie($name, $value, $expire, $path="/", $domain="", $secure=0){
	setcookie($name, $value, $expire, $path, $domain, $secure);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function glte($table, $order, $amount, $element, $value, $mode){
	/*
	$table - db table to check
	$order - field to order by
	$amount - number of elements to check
	$element - field to check
	$value - entered value, check criteria
	$mode - 1 = full string match, 2 = string contains match
	*/

	$sqlc = new db;
	$sqlc -> db_Select($table, "*", "ORDER BY $order DESC LIMIT 0, $amount", "nowhere");
	while($row = $sqlc -> db_Fetch()){
		$result[] = $row[$element];
	}

	if($mode == 1){
		return (in_array($value, $result) ? TRUE : FALSE);
	}

	while(list($key, $var) = each($result)){
		if(strstr($var, $value)){
			return TRUE;
		}
	}
	return FALSE;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function html($string){
	$match_count = preg_match_all("#\[html\](.*?)\[/html\]#si", $string, $result);
	for ($a = 0; $a < $match_count; $a++){
		
		$after_replace = str_replace("<br />", "", $result[1][$a]);
		$string = str_replace("[html]".$result[1][$a]."[/html]", $after_replace, $string);
	}
	return $string;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function code($string, $mode="default"){

	$search = array("<", ">", "[", "]", " ");
	$replace = array("&lt;", "&gt;", "&#091;", "&#093;", "&nbsp;");

	if($mode == "default"){
		$match_count = preg_match_all("#\[code\](.*?)\[/code\]#si", $string, $result);
		for ($a = 0; $a < $match_count; $a++){
			$after_replace = str_replace($search, $replace, $result[1][$a]);
			$string = str_replace("[code]".$result[1][$a]."[/code]", "[code]".$after_replace."[/code]", $string);
		}
		return $string;
	}

	$match_count = preg_match_all("#\[code\](.*?)\[/code\]#si", $string, $result);
	for ($a = 0; $a < $match_count; $a++){
		$colourtext = str_replace($search, $replace, $result[1][$a]);
		$string = str_replace("[code]".$result[1][$a]."[/code]", "<div class='indent'>".$colourtext."</div>", $string);
	}

	$string = str_replace("&lt;br&nbsp;/&gt;", "<br />", $string);

	return $string;
}

?>
