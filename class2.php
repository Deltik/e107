<?php
/*
+---------------------------------------------------------------+
|	e107 website system   .-:*'``'*:-.,_,.-:*'``'*:-.
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
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class db{

	//	 global variables
	var $mySQLserver;
	var $mySQLuser;
	var $mySQLpassword;
	var $mySQLdefaultdb;
	var $mySQLaccess;
	var $mySQLresult;
	var $mySQLrows;
	var $mySQLerror;
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb){
		/*
		# Connect to mySQL server and select database
		#
		# - parameters #1:		string $mySQLserver, mySQL server
		# - parameters #2:		string $mySQLuser, mySQL username
		# - parameters #3:		string $mySQLpassword, mySQL password
		# - parameters #4:		string mySQLdefaultdb, mySQL default database
		# - return				error if encountered
		# - scope					public
		*/

		$this->mySQLserver = $mySQLserver;
		$this->mySQLuser = $mySQLuser;
		$this->mySQLpassword = $mySQLpassword;
		$this->mySQLdefaultdb = $mySQLdefaultdb;
		$temp = $this->mySQLerror;
		$this->mySQLerror = FALSE;
		$this->mySQL_access = @mysql_connect($this->mySQLserver, $this->mySQLuser, $this->mySQLpassword);
		@mysql_select_db($this->mySQLdefaultdb);
		$this->dbError("dbConnect/SelectDB");
		return $this->mySQLerror = $temp;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Select($table, $fields="*", $arg="", $mode="default"){
		/*
		# Select with args
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $fields, table fields to be retrieved, default *
		# - parameter #3:	string $arg, query arguaments, default null
		# - parameter #4:	string $mode, arguament has WHERE or not, default=default (WHERE)
		# - return				affected rows
		# - scope					public
		*/
		global $dbq;
		$dbq++;

		$debug = 0;
		$debugtable = "links";
		if($arg != "" && $mode=="default"){
			if($debug == TRUE && $debugtable == $table){ echo "SELECT ".$fields." FROM ".MPREFIX.$table." WHERE ".$arg."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MPREFIX.$table." WHERE ".$arg)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("db_Select (SELECT $fields FROM ".MPREFIX."$table WHERE $arg)");
				return FALSE;
			}
		}else if($arg != "" && $mode != "default"){
			if($debug == TRUE && $debugtable == $table){ echo "@@SELECT ".$fields." FROM ".MPREFIX.$table." ".$arg."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MPREFIX.$table." ".$arg)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("db_Select (SELECT $fields FROM ".MPREFIX."$table $arg)");
				return FALSE;
			}
		}else{
			if($debug == TRUE && $debugtable == $table){ echo "SELECT ".$fields." FROM ".MPREFIX.$table."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MPREFIX.$table)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("db_Select (SELECT $fields FROM ".MPREFIX."$table)");
				return FALSE;
			}		
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Insert($table, $arg){
		/*
		# Insert with args
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $arg, insert string
		# - return				sql identifier, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/

		if($table == "news"){
//			echo "INSERT INTO ".MPREFIX.$table." VALUES (".$arg.")";
		}
		if($result = $this->mySQLresult = @mysql_query("INSERT INTO ".MPREFIX.$table." VALUES (".$arg.")" )){
			update_cache($table);
			return $result;
		}else{
			$this->dbError("db_Insert ($query)");
			return FALSE;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Update($table, $arg){
		/*
		# Update with args
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $arg, update string
		# - return				sql identifier, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
		$debug = 0;
//		$debugtable = "download";
		if($debug == TRUE){ echo "UPDATE ".MPREFIX.$table." SET ".$arg."<br />"; }	
		if($result = $this->mySQLresult = @mysql_query("UPDATE ".MPREFIX.$table." SET ".$arg)){
			update_cache($table);
			return $result;
		}else{
			$this->dbError("db_Update ($query)");
			return FALSE;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Fetch($mode = "strip"){
		/*
		# Retrieve table row
		#
		# - parameters		none
		# - return				result array, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
		if($row = @mysql_fetch_array($this->mySQLresult)){
			if($mode == strip){
				while (list($key,$val) = each($row)){
					$row[$key] = stripslashes($val);
				}
			}
			$this->dbError("db_Fetch");
			return $row;
		}else{
			$this->dbError("db_Fetch");
			return FALSE;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Count($table, $fields="(*)", $arg=""){
		/*
		# Retrieve result count
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $fields, count fields, default (*)
		# - parameter #3:	string $arg, count string, default null
		# - return				result array, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
//		echo "SELECT COUNT".$fields." FROM ".MPREFIX.$table." ".$arg;

		if($fields == "generic"){
			if($this->mySQLresult = @mysql_query($table)){
				$rows = $this->mySQLrows = @mysql_fetch_array($this->mySQLresult);
				return $rows[0];
			}else{
				$this->dbError("dbCount ($query)");
			}
		}

		if($this->mySQLresult = @mysql_query("SELECT COUNT".$fields." FROM ".MPREFIX.$table." ".$arg)){
			$rows = $this->mySQLrows = @mysql_fetch_array($this->mySQLresult);
			return $rows[0];
		}else{
			$this->dbError("dbCount ($query)");
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Close(){
		/*
		# Close mySQL server connection
		#
		# - parameters		none
		# - return				null
		# - scope					public
		*/
		mysql_close();
		$this->dbError("dbClose");
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Delete($table, $arg=""){
		/*
		# Delete with args
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $arg, delete string
		# - return				result array, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
//		if($table == "forum_t" || $table == "poll"){
//			echo "DELETE FROM ".MPREFIX.$table." WHERE ".$arg."<br />";			// debug
//		}
		update_cache($table);

		if(!$arg){
			if($result = $this->mySQLresult = @mysql_query("DELETE FROM ".MPREFIX.$table)){
				return $result;
			}else{
				$this->dbError("db_Delete ($arg)");
				return FALSE;
			}
		}else{
			if($result = $this->mySQLresult = @mysql_query("DELETE FROM ".MPREFIX.$table." WHERE ".$arg)){
				return mysql_affected_rows();
			}else{
				$this->dbError("db_Delete ($arg)");
				return FALSE;
			}
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Rows(){
		/*
		# Return affected rows
		#
		# - parameters		none
		# - return				affected rows, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
		$rows = $this->mySQLrows = @mysql_num_rows($this->mySQLresult);
		return $rows;
		$this->dbError("db_Rows");
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function dbError($from){
		/*
		# Return affected rows
		#
		# - parameter #1		string $from, routine that called this function
		# - return				error message on mySQL error
		# - scope					private
		*/
		if($error_message = @mysql_error()){
			if($this->mySQLerror == TRUE){
				require_once(e_BASE."classes/message_handler.php");
				message_handler("ADMIN_MESSAGE", "<b>mySQL Error!</b> Function: $from. [".@mysql_errno()." - $error_message]",  __LINE__, __FILE__);
				return $error_message;
			}
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_SetErrorReporting($mode){
		$this->mySQLerror = $mode;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function db_Select_gen($arg){
		if($this->mySQLresult = @mysql_query($arg)){
			$this->dbError("db_Select_gen");
			return $this->db_Rows();
		}else{
			$this->dbError("dbQuery ($query)");
			return FALSE;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function db_Fieldname($offset){

		$result = @mysql_field_name($this->mySQLresult, $offset);
		return $result;
	}

	function db_Num_fields(){
		$result = @mysql_num_fields($this->mySQLresult);
		return $result;
	}


}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
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
class userlogin{
	function userlogin($username, $userpass, $autologin){
		/* Constructor
		# Class called when user attempts to log in
		#
		# - parameters #1:		string $username, $_POSTED user name
		# - parameters #2:		string $userpass, $_POSTED user password
		# - return				boolean
		# - scope					public
		*/
		global $pref;
		$sql = new db;

		if($username != "" && $userpass != ""){
			$userpass = md5($userpass);
			if(!$sql -> db_Select("user",  "*", "user_name='$username' ")){
				define("LOGINMESSAGE", LAN_300);
				return FALSE;
			}else if(!$sql -> db_Select("user", "*", "user_name='$username' AND user_password='$userpass'")){
				define("LOGINMESSAGE", LAN_301);
				return FALSE;
			}else if(!$sql -> db_Select("user", "*", "user_name='$username' AND user_password='$userpass' AND user_ban!=2 ")){
				define("LOGINMESSAGE", LAN_302);
				return FALSE;
			}else{
				list($user_id) = $sql-> db_Fetch();

				if($pref['user_tracking'][1] == "session"){
					$_SESSION['userkey'] = $user_id.".".$userpass;
				}else{
					if($autologin == 1){
						setcookie('userkey', $user_id.".".$userpass, time()+3600*24*30, '/', '', 0);
					}else{
						setcookie('userkey', $user_id.".".$userpass, time()+3600, '/', '', 0);
					}
				}

				$redir = (e_QUERY ? e_SELF."?".e_QUERY : e_SELF);
				echo "<script type='text/javascript'>document.location.href='$redir'</script>\n";

/*
				if(!eregi("Apache", $_SERVER['SERVER_SOFTWARE'])){
					header("Refresh: 0; URL: ".$redir);
					exit;
				}else{
					header("Location: ".$redir);
					exit;
				}
*/
			}
		}else{
			define("LOGINMESSAGE", LAN_27."<br /><br />");
			return FALSE;
		}
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
			$sql -> db_Select($table, "*", "ORDER BY ".$orderfield." DESC LIMIT 1", $mode = "no_where");
			$row = $sql -> db_Fetch();
			if($row[$orderfield] > (time() - FLOODTIMEOUT)){
				return FALSE;
			}else{
				return TRUE;
			}
		}else{
			return TRUE;
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function init_session(){

		/*
		# Validate session if exists
		#
		# - parameters		none
		# - return				boolean
		# - scope				public
		*/
	global $pref, $user_pref, $sql;

	if(!$_COOKIE['userkey'] && !$_SESSION['userkey']){
		define("USER", FALSE); define("USERTHEME", FALSE); define("ADMIN", FALSE);
	}else{
		$tmp = ($_COOKIE['userkey'] ? explode(".", $_COOKIE['userkey']) : explode(".", $_SESSION['userkey'])); $uid = $tmp[0]; $upw = $tmp[1];
		if(Empty($upw)){	 // corrupt cookie?
			setcookie('userkey', '', 0, '/', '', 0);
			$_SESSION["userkey"] = "";
			session_destroy();
			define("ADMIN", FALSE); define("USER", FALSE); define("LOGINMESSAGE", "Corrupted cookie detected - logged out.<br /><br />");
			return(FALSE);
		}
		$sql = new db;
		if($sql -> db_Select("user", "*", "user_id='$uid' AND user_password='$upw' ")){
			$result = $sql -> db_Fetch(); extract($result);
			define("USERID", $user_id); define("USERNAME", $user_name); define("USERURL", $user_website); define("USEREMAIL", $user_email); define("USER", TRUE); define("USERLV", $user_lastvisit); define("USERVIEWED", $user_viewed); define("USERCLASS", $user_class); define("USERREALM", $user_realm);

			if($user_ban == 1){ exit; }

			$user_pref = unserialize($user_prefs);

			if(IsSet($_POST['settheme'])){
				$user_pref['sitetheme'] = ($pref['sitetheme'][1] == $_POST['sitetheme'] ? "" : $_POST['sitetheme']);
				save_prefs($user);
			}

			if(IsSet($_POST['setlanguage'])){
				$user_pref['sitelanguage'] = ($pref['sitelanguage'][1] == $_POST['sitelanguage'] ? "" : $_POST['sitelanguage']);
				save_prefs($user);
			}

			if($user_pref['sitetheme'] && @fopen(e_BASE."themes/".$user_pref['sitetheme']."/theme.php","r")){
				define("USERTHEME", $user_pref['sitetheme']);
			}else{
				define("USERTHEME", FALSE);
			}

			if($user_pref['sitelanguage'] && @fopen(e_BASE."languages/lan_".$user_pref['sitelanguage'].".php","r")){
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
	if($sql -> db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' || banlist_ip='".USEREMAIL."' ")){exit;}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//initialise

//			@include("classes/errorhandler_class.php");
//			set_error_handler("error_handler");

			ob_start();
			$timing_start = explode(' ', microtime());
//			ini_set("display_errors", "1");
//			error_reporting(E_ALL & ~E_NOTICE);
//			ini_set("include_path", "/");

//			if(!get_magic_quotes_runtime ()){ set_magic_quotes_runtime(1); }
//			ini_set("magic_quotes_gpc", "1");

			$admin_directory = "admin";

			@include("config.php");
			$a=0;
			while(!defined("e_HTTP") && $a<5){
				$a++;
				$p.="../";
				@include($p."config.php");
			}
			if(!defined("e_HTTP")){ header("Location:install.php"); exit; }

			$url_prefix=substr($_SERVER['PHP_SELF'],strlen(e_HTTP),strrpos($_SERVER['PHP_SELF'],"/")+1-strlen(e_HTTP));
			$tmp=explode("?",$url_prefix);
			$num_levels=substr_count($tmp[0],"/");
			for($i=1;$i<=$num_levels;$i++){ 
				$link_prefix.="../";
			}

			define("e_ADMIN", e_HTTP.$admin_directory."/");
			define("e_SELF", "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
			define("e_QUERY", eregi_replace("&|/?PHPSESSID.*", "", $_SERVER['QUERY_STRING']));
			define('e_BASE',$link_prefix);

			if($mySQLuser == ""){ header("location:install.php"); exit; }
			define("MPREFIX", $mySQLprefix);
			define("MUSER", $mySQLprefix); // depracated, please use MPREFIX

			$sql = new db;
			$sql -> db_SetErrorReporting(TRUE);
			$sql -> db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);

			$sql -> db_Select("core", "*", "e107_name='pref' ");
			$row = $sql -> db_Fetch();

			require_once(e_BASE."classes/message_handler.php");

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
			if($pref['user_tracking'][1] == "session"){ require_once(e_BASE."classes/session_handler.php"); }

			$sql -> db_Select("core", "*", "e107_name='menu_pref' ");
			$row = $sql -> db_Fetch();
			$tmp = stripslashes($row['e107_value']);
			$menu_pref=unserialize($tmp);

			$page = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
			if($pref['frontpage'][1] && $pref['frontpage_type'][1] == "splash"){
				$ip = getip();
				if(!$sql -> db_Select("online", "*", "online_ip='$ip' ")){

					online(e_SELF);
					if(is_numeric($pref['frontpage'][1])){
						header("location: article.php?".$pref['frontpage'][1].".255");
						exit;
					}else if(eregi("http", $pref['frontpage'][1])){
						header("location: ".$pref['frontpage'][1]);
						exit;
					}else{
						header("location: ".e_BASE.$pref['frontpage'][1].".php");
						exit;
					}
				}
			}

			init_session();
//			if(!USER && !eregi("customlogin.php", e_SELF)){ header("location:".e_BASE."customlogin.php"); }
			online(e_SELF);

			$sql -> db_Delete("tmp", "tmp_time < '".(time()-300)."' AND tmp_ip!='data' ");

			if($pref['flood_protect'][1] == 1){
				$sql -> db_Delete("flood", "flood_time+'".$pref['flood_time'][1]."'<'".time()."' ");
				$sql -> db_Insert("flood", " '".$_SERVER['PHP_SELF']."', '".time()."' ");
				$hits = $sql -> db_Count("flood", "(*)", "WHERE flood_url = '".$_SERVER['PHP_SELF']."' ");
				if($hits > $pref['flood_hits'][1] && $pref['flood_hits'][1] != ""){
					die();
				}
			}

			define("SITENAME", $pref['sitename'][1]);
			define("SITEURL", $pref['siteurl'][1]);
//			if(eregi("http:", $pref['sitebutton'][1]) ? define ("SITEBUTTON", $pref['sitebutton'][1]) : define("SITEBUTTON", e_HTTP.$pref['sitebutton'][1]));
			define ("SITEBUTTON", $pref['sitebutton'][1]);
			define("SITETAG", $pref['sitetag'][1]);
			define("SITEDESCRIPTION", $pref['sitedescription'][1]);
			define("SITEADMIN", $pref['siteadmin'][1]);
			define("SITEADMINEMAIL", $pref['siteadminemail'][1]);
			define("SITEDISCLAIMER", str_replace("�", "&#169;", $pref['sitedisclaimer'][1]));

			$language = $pref['sitelanguage'][1]; if(!$language){$language = "English";}
			if(!USERLAN || !defined("USERLAN")){
				require_once(e_BASE."languages/lan_".$language.".php");
				define("e_LANGUAGE", $language);
			}else{
				require_once(e_BASE."languages/lan_".USERLAN.".php");
				define("e_LANGUAGE", USERLAN);
			}

			if(IsSet($_POST['userlogin'])){
				$sql -> db_Delete("cache");
				$usr = new userlogin($_POST['username'], $_POST['userpass'], $_POST['autologin']);
			}
			

			if(e_QUERY == "logout"){
				if($pref['user_tracking'][1] == "session"){ session_destroy(); $_SESSION["userkey"] = ""; }
				setcookie('userkey', '', 0, '/', '', 0);
				$sql -> db_Delete("cache");
				echo "<script type='text/javascript'>document.location.href='".e_BASE."index.php'</script>\n";
			}
			ban();
			
			define("TIMEOFFSET", $pref['time_offset'][1]);
			define("FLOODTIME", $pref['flood_time'][1]);
			define("FLOODHITS", $pref['flood_hits'][1]);

			if(USERTHEME != FALSE && USERTHEME != "USERTHEME"){
				define("THEME", (@fopen(e_BASE."themes/".USERTHEME."/theme.php", r) ? e_BASE."themes/".USERTHEME."/" : e_BASE."themes/e107/"));
			}else{
				define("THEME", (@fopen(e_BASE."themes/".$pref['sitetheme'][1]."/theme.php", r) ? e_BASE."themes/".$pref['sitetheme'][1]."/" : e_BASE."themes/e107/"));
			}
			require_once(THEME."theme.php");

			if($pref['anon_post'][1] ? define("ANON", TRUE) : define("ANON", FALSE));
			if(Empty($pref['newsposts'][1]) ? define("ITEMVIEW", 15) : define("ITEMVIEW", $pref['newsposts'][1]));
			if($pref['flood_protect'][1]){  define(FLOODPROTECT, TRUE); define(FLOODTIMEOUT, $pref['flood_timeout'][1]); }

			if($layout != "_default"){
				define ("HEADERF", e_BASE."themes/templates/header".$layout.".php");
				define ("FOOTERF", e_BASE."themes/templates/footer".$layout.".php");
			}else{
				define ("HEADERF", e_BASE."themes/templates/header_default.php");
				define ("FOOTERF", e_BASE."themes/templates/footer_default.php");
			}

			define("LOGINMESSAGE", "");
			if($pref['maintainance_flag'][1] && ADMIN == FALSE && !eregi("admin", e_SELF)){
				header("location:".e_BASE."sitedown.php"); exit;
			}
			$ns = new table;

			define("OPEN_BASEDIR", (ini_get('open_basedir') ? TRUE : FALSE));
			define("SAFE_MODE", (ini_get('safe_mode') ? TRUE : FALSE));
			define("MAGIC_QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class table{
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
	function tp($text, $mode="off"){
		/*
		# Pre parse
		# - parameter #1:		string $text, text to parse
		# - parameter #2:		string $mode, on=HTML allowed, default off
		# - return					parsed text
		# - scope					public
		*/

		if($mode == "off"){
			$text = strip_tags($text);
		}

		$search = array();
		$replace = array();
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
		$text = preg_replace($search, $replace, $text);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

	if(defined("MQ")){
		$search = array("\"", "'", "\\");
		$replace = array("&quot;", "&#39;", "&#92;");
		$text = str_replace($search, $replace, $text);
		$text = str_replace("<a href=&quot;", "<a href=\"", $text);
		$text = str_replace("&quot;>", "\">", $text);
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

		stripslashes($text);
		$text = trim(chop($text));
		return($text);
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
//		$text = preg_quote($text);
		if($pref['profanity_filter'][1] == 1){
			$prof = LAN_24;
			$text = eregi_replace($prof, $pref['profanity_replace'][1], $text);
		}
		if($pref['smiley_activate'][1] == 1){
			require_once(e_BASE."plugins/emoticons.php");
			$text = emoticons($text);
		}

//		$text = str_replace("<br>","<br />", $text);
		
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
		$replace[13] = '<img src=\'\1\' alt=\'\' />';
		$search[14] = "#\[center\](.*?)\[/center\]#si";
		$replace[14] = '<div style=\'text-align:center\'>\1</div>';
		$search[15] = "#\[left\](.*?)\[/left\]#si";
		$replace[15] = '<div style=\'text-align:left\'>\1</div>';
		$search[16] = "#\[right\](.*?)\[/right\]#si";
		$replace[16] = '<div style=\'text-align:right\'>\1</div>';
		$search[17] = "#\[blockquote\](.*?)\[/blockquote\]#si";
		$replace[17] = '<div class=\'indent\'>\1</div>';
		$search[18] = "#\[code\](.*?)\[/code\]#si";
		$replace[18] = '<code>\1</code>';
		$search[19] = "/\[color=(.*?)\](.*?)\[\/color\]/si";
		$replace[19] = '<span style=\'color:\1\'>\2</span>';
		$search[20] = "/\[size=([1-2]?[0-9])\](.*?)\[\/size\]/si";
		$replace[20] = '<span style=\'font-size:\1px\'>\2</span>';
		$search[16] = "#\[edited\](.*?)\[/edited\]#si";
		$replace[16] = '<span class=\'smallblacktext\'>[ \1 ]</span>';
		$text = preg_replace($search, $replace, $text);

		if($mode == "off"){
			$text = nl2br($text);
			$text = str_replace("<br /><br />", "<br />", $text);
		}
		// sent in by 'Anon' - thanks
		$text = " " . $text;
		$text = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3">\2://\3</a>', $text);
		$text = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3">\2.\3</a>', $text);
		$text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);
		$text = substr($text, 1);
		if($mode != "off"){
			$text = stripslashes($text);
		}
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
			return strftime($pref['longdate'][1], $datestamp);
		}else if($mode == "short"){
			return strftime($pref['shortdate'][1], $datestamp);
		}else{
			return strftime($pref['forumdate'][1], $datestamp);
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function check_class($var, $userclass=USERCLASS){
	if(ADMIN == TRUE){ $debug=0; }else{ $debug=0; }
	if($debug){ echo "USERCLASS: ".$userclass.", \$var = $var : "; }
	if(!defined("USERCLASS") || $userclass == ""){
		if($debug){ echo "FALSE<br />"; }
		return FALSE;
	}
	// user has classes set - continue
	if(is_numeric($var[0])){
		$tmp = explode(".", $userclass);
		for($c=0; $c<=(count($tmp)-1); $c++){
			if($tmp[$c] && preg_match("/".$var."(|\$)/", $tmp[$c])){
				if($debug){ echo "TRUE<br />"; }
				return TRUE;
			}
		}
	}else{
		// var is name of class ...
		$sql = new db;
		if($sql -> db_Select("userclass_classes", "*", "userclass_name='$var' ")){
			$row = $sql -> db_Fetch(); extract($row);
			if(ereg($userclass_id, $userclass)){
				if($debug){ echo "TRUE<br />"; }
				return TRUE;
			}
		}
	}
	if($debug){  echo "NOTNUM! FALSE<br />"; }
	return FALSE;
}
function getperms($arg, $ap = ADMINPERMS){
	if(ereg($arg.".", $ap) || $ap == "0"){
		return TRUE;
	}else{
		return FALSE;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function save_prefs($table = "core"){
	global $pref, $user_pref;
	$sql = new db;
	if($table == "core"){
		$tmp = addslashes(serialize($pref));
		$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='pref' ");
	}else{
		$tmp = addslashes(serialize($user_pref));
		$sql -> db_Update("user", "user_prefs='$tmp' WHERE user_id='".USERID."' ");
		return $tmp;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function online($page){
	if($page == 'log2.php'){return;}
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
			$tmp = explode(".", $online_user_id);
			$member_list .= "<a href=\"".e_BASE."user.php?id.".$tmp[0]."\">".$tmp[1]."</a> ";
		}
	}
	define("TOTAL_ONLINE", $total_online);
	define("MEMBERS_ONLINE", $members_online);
	define("GUESTS_ONLINE", $total_online - $members_online);
	define("ON_PAGE", $sql -> db_Select("online", "*", "online_location='$page' "));
	define("MEMBER_LIST", $member_list);
}

function update_cache($table){
	$sql = new db;
	switch ($table){ 
		case "news":
			$sql -> db_Delete("cache", "cache_url REGEXP('news.php') OR cache_URL REGEXP('comment.php')");
		break;
		case "comments":
			$sql -> db_Delete("cache", "cache_url REGEXP('news.php') OR cache_URL REGEXP('comment.php')");
		break; 
		case "forum_t": 
			$tmp = explode(".", e_QUERY);
			$sql -> db_Delete("cache", "cache_url REGEXP('forum.php') OR cache_URL REGEXP('forum_viewforum.php?".$tmp[0]."') OR cache_url REGEXP('forum_viewtopic.php?".$tmp[0].".".$tmp[1]."')");
		break; 
		case "register": 
			$sql -> db_Delete("cache", "cache_url REGEXP('register.php')");
		break; 
	}
}

?>