<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/class2.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
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
		$debug = false;
		$debugtable = "register";
		if($arg != "" && $mode=="default"){
			if($debug == TRUE && $debugtable == $table){ echo "!!SELECT ".$fields." FROM ".MUSER.$table." WHERE ".$arg."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MUSER.$table." WHERE ".$arg)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("dbQuery ($query)");
				return FALSE;
			}
		}else if($arg != "" && $mode != "default"){
			if($debug == TRUE && $debugtable == $table){ echo "@@SELECT ".$fields." FROM ".MUSER.$table." ".$arg."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MUSER.$table." ".$arg)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("dbQuery ($query)");
				return FALSE;
			}
		}else{
			if($debug == TRUE && $debugtable == $table){ echo "SELECT ".$fields." FROM ".MUSER.$table."<br />"; }
			if($this->mySQLresult = @mysql_query("SELECT ".$fields." FROM ".MUSER.$table)){
				$this->dbError("dbQuery");
				return $this->db_Rows();
			}else{
				$this->dbError("db_Query ($query)");
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
//		echo "INSERT INTO ".MUSER.$table." VALUES (".$arg.")";
		if($result = $this->mySQLresult = @mysql_query("INSERT INTO ".MUSER.$table." VALUES (".$arg.")" )){
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
		$debug = FALSE;
		$debugtable = "user";
		if($debug == TRUE && $debugtable == $table){ echo "UPDATE ".MUSER.$table." SET ".$arg."<br />"; }	
		if($result = $this->mySQLresult = @mysql_query("UPDATE ".MUSER.$table." SET ".$arg)){	
			return $result;
		}else{
			$this->dbError("db_Update ($query)");
			return FALSE;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_Fetch(){
		/*
		# Retrieve table row
		#
		# - parameters		none
		# - return				result array, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
		if($row = @mysql_fetch_array($this->mySQLresult)){
			while (list($key,$val) = each($row)) {
				$row[$key] = stripslashes($val);
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
		if($this->mySQLresult = @mysql_query("SELECT COUNT".$fields." FROM ".MUSER.$table." ".$arg)){
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
	function db_Delete($table, $arg){
		/*
		# Delete with args
		#
		# - parameter #1:	string $table, table name
		# - parameter #2:	string $arg, delete string
		# - return				result array, or error if (error reporting = on, error occured, boolean)
		# - scope					public
		*/
//		echo "DELETE FROM ".MUSER.$table." WHERE ".$arg;			// debug
		if($arg == ""){
			if($result = $this->mySQLresult = @mysql_query("DELETE FROM ".MUSER.$table)){
				return $result;
			}else{
				$this->dbError("db_Delete ($query)");
				return FALSE;
			}
		}else{
			if($result = $this->mySQLresult = @mysql_query("DELETE FROM ".MUSER.$table." WHERE ".$arg)){
				return $result;
			}else{
				$this->dbError("db_Delete ($query)");
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
				echo "<b>mySQL Error!</b> Function: $from. [".@mysql_errno()." - $error_message]<br />";
				return $error_message;
			}
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function db_SetErrorReporting($mode){
		$this->mySQLerror = $mode;
	}
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

		$autologin == 1;

		$sql = new db;
		if($username != "" && $userpass != ""){
			$userpass = md5($userpass);
			if(!$sql -> db_Select("user",  "*", "user_name='$username' ")){
				define("LOGINMESSAGE", "That username was not found in the database.<br /><br />");
				return FALSE;
			}else if(!$sql -> db_Select("user", "*", "user_name='$username' AND user_password='$userpass' ")){
				define("LOGINMESSAGE", "Incorrect password.<br /><br />");
				return FALSE;
			}else{
				list($user_id) = $sql-> db_Fetch();
				$_SESSION['userkey'] = $user_id.".".$userpass;
				if($autologin == 1){
					setcookie('userkey', $user_id.".".$userpass, time()+3600*24*30, '/', '', 0);
				}
				$sql -> db_Update("user", "user_sess='".session_id()."' WHERE user_id='$user_id' ");
				header("Location: http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
				exit;
			}
		}else{
			define("LOGINMESSAGE", "Field(s) left blank.<br /><br />");
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
		# - scope					public
		*/
	if(IsSet($_SESSION['userkey']) || IsSet($_COOKIE['userkey'])){
		if(IsSet($_SESSION['userkey'])){ $uk = $_SESSION['userkey']; }else{ $uk = $_COOKIE['userkey']; }
		$tmp = explode(".", $uk); $uid = $tmp[0]; $upw = $tmp[1];

		if(Empty($upw)){
			if(IsSet($_SESSION['userkey'])){ 
				session_destroy(); session_unregister();
			}else{
				setcookie('userkey', '', time()+3600*24*30, '/', '', 0);
			}
			return FALSE;
			define("ADMIN", FALSE);
			define("USER", FALSE);
		}

		$sql = new db;
		if($sql -> db_Select("user", "*", "user_id='$uid' AND user_password='$upw' ")){
			$result = $sql -> db_Fetch();
			extract($result);
			define("USERID", $user_id);
			define("USERNAME", $user_name);
			define("USER", TRUE);
			define("USERLV", $user_lastvisit);
			define("USERVIEWED", $user_viewed);

			$usertheme = str_replace("sitetheme=", "", $user_prefs); if($usertheme == "" || $usertheme == "none"){ define("USERTHEME", FALSE); }else{ define("USERTHEME", $usertheme); }

			$sql -> db_Update("user", "user_visits=user_visits+1 WHERE user_name='".USERNAME."' ");
			if($user_currentvisit + 3600 < time()){
				$sql -> db_Update("user", "user_lastvisit='$user_currentvisit', user_currentvisit='".time()."', user_viewed='$r' WHERE user_name='".USERNAME."' ");
			}

			if($user_admin == 1){
				if($sql -> db_Select("admin", "*", "admin_name='$user_name' AND admin_password='$upw' ")){
					$result = $sql -> db_Fetch();
					extract($result);
					define("ADMIN", TRUE);
					define("ADMINID", $admin_id);
					define("ADMINNAME", $admin_name);
					define("ADMINPERMS", $admin_permissions);
					define("ADMINEMAIL", $admin_email);
				}else{
					define("ADMIN", FALSE);
					define("LOGINMESSAGE", "Error: User and admin passwords do not match.");
				}
			}else{
				define("ADMIN", FALSE);
			}
		}else{
			define("USER", FALSE);
			define("USERTHEME", FALSE);
			define("ADMIN", FALSE);
		}
	}else{
		define("USER", FALSE);
		define("USERTHEME", FALSE);
		define("ADMIN", FALSE);
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function ban(){
	$sql = new db;
	if($sql -> db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' ")){ exit; }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//initialise
			
			timing_start();
			ob_start();
			$_SERVER['QUERY_STRING'] = eregi_replace("&|/?PHPSESSID.*", "", $_SERVER['QUERY_STRING']);
			session_start();
//			error_reporting(E_ERROR | E_WARNING);
			set_magic_quotes_runtime(0);
			ini_set("arg_separator.output", "&amp;");
			ini_set("url_rewriter.tags", "a=href,area=href,frame=src,input=src");
			if(eregi("admin", $_SERVER['PHP_SELF']) || eregi("plugins", $_SERVER['PHP_SELF'])){
				require_once("../config.php"); }else{ require_once("config.php"); }
			if($mySQLuser == ""){ header("location:install.php"); }
			define("MUSER", $mySQLprefix);
			$sql = new db;
			$sql -> db_SetErrorReporting(TRUE);
			$sql -> db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);

			$ns = new table;
			if($sql -> db_Select("prefs", "*", "", $mode="no_where")){
				$c = 0;
				while(list($preftemp, $pref[$c][1]) = $sql-> db_Fetch()){
					$pref[$preftemp][1] = $pref[$c][1];
					$c++;
				}
			}

			if($pref['flood_protect'][1] == 1){
				$sql -> db_Delete("flood", "flood_time+'".$pref['flood_time'][1]."'<'".time()."' ");
				$sql -> db_Insert("flood", " '".$_SERVER['PHP_SELF']."', '".time()."' ");
				$hits = $sql -> db_Count("flood", "(*)", "WHERE flood_url = '".$_SERVER['PHP_SELF']."' ");
				if($hits > $pref['flood_hits'][1] && $pref['flood_hits'][1] != ""){
					die("Flood protection activated");
				}
			}

			if(IsSet($_POST['settheme'])){
				if(IsSet($_SESSION['userkey'])){ $uk = $_SESSION['userkey']; }else{ $uk = $_COOKIE['userkey']; }
				$tmp = explode(".", $uk); $uid = $tmp[0]; $upw = $tmp[1];
				$sql -> db_Update("user", "user_prefs='sitetheme=".$_POST['sitetheme']."' WHERE user_id='$uid' ");
			}

			init_session();

			if($_SERVER['QUERY_STRING'] == "logout"){
				setcookie('userkey', '', time()+3600*24*30, '/', '', 0);
//				$sql -> db_Update("user", "user_sess='' WHERE user_sess='".session_id()."' "); 
				session_unset(); session_destroy(); 
				if(eregi("admin", $_SERVER['PHP_SELF'])){ header("Location:../index.php"); }else{  header("Location:index.php");}
			}
			ban();

			if(IsSet($_POST['userlogin'])){ $usr = new userlogin($_POST['username'], $_POST['userpass'], $_POST['autologin']); }

			define("SITENAME", $pref['sitename'][1]);
			define("SITEURL", $pref['siteurl'][1]);
			define("SITEBUTTON", $pref['sitebutton'][1]);
			define("SITETAG", $pref['sitetag'][1]);
			define("SITEDESCRIPTION", $pref['sitedescription'][1]);
			define("SITEADMIN", $pref['siteadmin'][1]);
			define("SITEADMINEMAIL", $pref['siteadminemail'][1]);
			$pref['sitedisclaimer'][1] = str_replace("�", "&copy;", $pref['sitedisclaimer'][1]);
			define("SITEDISCLAIMER", $pref['sitedisclaimer'][1]);
			define("TIMEOFFSET", $pref['time_offset'][1]);

			define("FLOODTIME", $pref['flood_time'][1]);
			define("FLOODHITS", $pref['flood_hits'][1]);


//			if($sql -> db_Select("menus", "*", "menu_name='usertheme_menu' AND menu_location!=0 ")){
				if(USERTHEME != FALSE){
					define("THEME", "themes/".USERTHEME."/"); 
				}else{
					define("THEME", "themes/".$pref['sitetheme'][1]."/");
				}
	//		}else{
	//			define("THEME", "themes/".$pref['sitetheme'][1]."/");
	//		}
			
			if(Empty($pref['newsposts'][1])){ define(ITEMVIEW, 10); }else{ define(ITEMVIEW, $pref['newsposts'][1]); }
			if($pref['flood_protect'][1] == 1){  define(FLOODPROTECT, TRUE); define(FLOODTIMEOUT, $pref['flood_timeout'][1]); }
			$language =  $pref['sitelanguage'][1]; if(!$language){ $language = "English"; }
			if(eregi("admin", $_SERVER['PHP_SELF']) || eregi("plugins", $_SERVER['PHP_SELF'])){ require_once("../".THEME."theme.php"); require_once("../languages/lan_".$language.".php"); }else{ require_once(THEME."theme.php"); require_once("languages/lan_".$language.".php"); }
			define ("HEADERF", "themes/templates/header".$layout.".php");
			define ("FOOTERF","themes/templates/footer".$layout.".php");
			define("LOGINMESSAGE", "");
			if($pref['sitelocale'][1] == ""){ setlocale (LC_ALL, 'en'); }else{ setlocale (LC_ALL, $pref['sitelocale'][1]); }
			if($pref['maintainance_flag'][1] == 1){
				if(ADMIN == FALSE){
					if(!eregi("admin", $_SERVER['PHP_SELF']) && !eregi("upgrade", $_SERVER['PHP_SELF'])){
						header("location:sitedown.php"); exit;
					}
				}
			}
			if($pref['log_activate'][1] == 1 && ADMIN == FALSE){
				$referer = getreferer();
			}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class news{

	function edit_item($existing){
		/*
		# Retrieve news item for editing
		#
		# - parameter #1		string $existing, id of respective table entry
		# - return				array of news item fields
		# - scope					public
		*/
		$cls = new db;
		if($cls -> db_Select("news", "*", "news_id='$existing' ")){
			$row = $cls-> db_Fetch();
		}
		$tp = new textparse;
		$row['news_title'] = $tp -> editparse($row['news_title']);
		$row['news_body'] = $tp -> editparse($row['news_body']);
		$row['news_extended'] = $tp -> editparse($row['news_extended']);
		$row['news_source'] = $tp -> editparse($row['news_source']);
		$row['news_url'] = $tp -> editparse($row['news_url']);
		return $row;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//	
	function delete_item($news_id){
		/*
		# Delete a news item
		#
		# - parameter #1		string $news_id, id of respective table entry
		# - return				comfort message
		# - scope					public
		*/
		$cls = new db;
		if($cls -> db_Delete("news",  "news_id='$news_id' ")){
			return  LAN_13;
		}
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function submit_item($news_id, $news_title, $news_body, $news_extended, $news_source, $news_url, $category_id, $allow_comments){
		/*	
		# Enter news item into database
		#
		# - parameter #1		string $news_id, id of news item if already exists (edit), else null
		# - parameter #2		string $news_title
		# - parameter #3		string $news_body
		# - parameter #4		string $news_extended
		# - parameter #5		string $news_source
		# - parameter #6		string $news_url
		# - parameter #7		string $cat_name
		# - parameter #8		string $allow_comments
		# - return				comfort message
		# - scope					public
		*/
		$aj = new textparse;
		$news_title = $aj -> tp($news_title, $mode="on", 0);
		$news_body = $aj -> tp($news_body, $mode="on", 0);
		$news_extended = $aj -> tp($news_extended, $mode="on", 0);
		$news_source = $aj -> tp($news_source, $mode="on", 0);
		$news_url = $aj -> tp($news_url, $mode="on", 0);
		if($allow_comments == 0){ $allow_comments = 1; }else{ $allow_comments = 0; }
		$cls = new db;

		if($news_id != ""){
			$cls -> db_Update("news", "news_title='$news_title', news_body='$news_body', news_extended='$news_extended', news_source='$news_source', news_url='$news_url', news_category='$category_id', news_allow_comments='$allow_comments' WHERE news_id='$news_id' ");
			$message = LAN_14;
		}else{
			$datestamp = time();
			$cls -> db_Insert("news","0, '$news_title', '$news_body', '$news_extended', '$datestamp', '".ADMINID."', '$news_source', '$news_url', '$category_id', '$allow_comments' ");
			$message = LAN_15;
		}
		return $message;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function preview($news_id, $news_title, $news_body, $news_extended, $news_source, $news_url, $cat_id, $allow_comments){
		/*
		# Preview news item
		#
		# - parameter #1		string $news_id, id of news item if already exists (edit), else null
		# - parameter #2		string $news_title
		# - parameter #3		string $news_body
		# - parameter #4		string $news_extended
		# - parameter #5		string $news_source
		# - parameter #6		string $news_url
		# - parameter #7		string $cat_name
		# - return				null
		# - scope					public
		*/

		$aj = new textparse;
		$news_title = $aj -> tp($news_title, $mode="on", "preview");
		$news_body = $aj -> tp($news_body, $mode="on", "preview");
		$news_extended = $aj -> tp($news_extended, $mode="on", "preview");
		$news_source = $aj -> tp($news_source, $mode="on");
		$news_url = $aj -> tp($news_url, $mode="on");
		$cls = new db;
		if($allow_comments == 0){ $allow_comments = 1; }else{ $allow_comments = 0; }
		
		$this -> render_newsitem($news_id, $news_title, $news_body, $news_extended, $news_source, $news_url, ADMINID, "0", $cat_id,  time(), $allow_comments, "preview");
		return $news_id;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function render_newsitem($news_id, $news_title, $news_body, $news_extended, $news_source, $news_url, $news_author, $comment_total, $category_id, $datestamp, $allow_comments, $modex=""){
		/*
		# Render news item to screen
		#
		# - parameter #1		string $news_id, id of news item if already exists (edit), else null
		# - parameter #2		string $news_title
		# - parameter #3		string $news_body
		# - parameter #4		string $news_extended
		# - parameter #5		string $news_source
		# - parameter #6		string $news_url
		# - parameter #7		string $news_author, admin_id of author
		# - parameter #8		string $comment_total, comment count of news item
		# - parameter #9		string $category_name, category name of news item
		# - parameter #10		string $datestamp, post date of news item
		# - parameter #11		string $preview, boolean, true if preview, false if index.php
		# - parameter #12		string $cat_name
		# - return				null
		# - scope					public
		*/
		$aj = new textparse;
		
		$news_title = $aj -> tpa($news_title, $mode="on");
		$news_body = $aj -> tpa($news_body, $mode="off");
		$news_extended = $aj -> tpa($news_extended, $mode="off");

		if(Empty($comment_total)) $comment_total = "0";
		$con = new convert;
		$datestamp = $con -> convert_date($datestamp, "long");
		$cls = new db;
		$cls -> db_Select("admin", "*", "admin_id='$news_author' ");
		list($a_id, $a_name, $null, $a_email, $null, $null) = $cls-> db_Fetch();

		if($news_title == "Welcome to e107"){
			$a_name = "e107";
			$a_email = "e107@jalist.com";
			$category_name = "e107 welcome message";
			$category_id = 0;
			if(ereg("admin", $_SERVER['PHP_SELF'])){
				$category_icon = "../button.png";
			}else{
				$category_icon = "button.png";
			}
		}else{
			
			$cls -> db_Select("news_category", "*",  "category_id='$category_id' ");
			list($category_id, $category_name, $category_icon) = $cls-> db_Fetch();
			if(ereg("admin", $_SERVER['PHP_SELF'])){
				$category_icon = "../".THEME.$category_icon;
			}else{
				$category_icon = THEME.$category_icon;
			}
		}

		$search = array("[administrator]", "[date and time]", "[count]", "[l]", "[/l]", "[nc]");

		if($allow_comments == 1){
			$replace = array("<a href=\"mailto:$a_email\">$a_name</a>", $datestamp, COMMENT_OFF_TEXT, "", "", "<a href=\"index.php?cat.".$category_id."\">".$category_name."</a>");
		}else{
			$replace = array("<a href=\"mailto:$a_email\">$a_name</a>", $datestamp, $comment_total, "<a href=\"comment.php?".$news_id."\">", "</a>", "<a href=\"index.php?cat.".$category_id."\">".$category_name."</a>");
		}
		$info_text = str_replace($search,$replace, INFO_TEXT);

		if(SHOW_EMAIL_PRINT == TRUE){
			if(eregi("admin", $_SERVER['PHP_SELF'])){
				$ptext = " <a href=\"email.php?".$news_id."\"><img src=\"../themes/shared/generic/friend.gif\" style=\"border:0\" alt=\"email to someone\" /></a> <a href=\"print.php?".$news_id."\"><img src=\"../themes/shared/generic/printer.gif\" style=\"border:0\" alt=\"printer friendly\" /></a>";
			}else{
				$ptext = " <a href=\"email.php?".$news_id."\"><img src=\"themes/shared/generic/friend.gif\" style=\"border:0\" alt=\"email to someone\" /></a> <a href=\"print.php?".$news_id."\"><img src=\"themes/shared/generic/printer.gif\" style=\"border:0\" alt=\"printer friendly\" /></a>";
			}
		}

		if(ICON_SHOW == TRUE && ICON_POSITION == "caption"){
			$caption = "<table style=\"width:95%\"><tr><td style=\"width:50%\">";
		}

		if(TITLE_POSITION == "caption"){
			$caption .= "<div style=\"text-align:".TITLE_ALIGN."\">".TITLE_STYLE_START.$news_title.TITLE_STYLE_END."</div>";
		}
		if(INFO_POSITION == "caption"){
			$caption .= "<div style=\"text-align:".INFO_ALIGN."\">".$info_text." ".$ptext."</div>";
		}

		if(ICON_SHOW == TRUE && ICON_POSITION == "caption"){
			$tmp = "<table style=\"width:95%\"><tr><td style=\"width:50%\">";
			if(ICON_ALIGN == "left"){
				$tmp = "<a href=\"index.php?cat.".$category_id."\"><img style=\"float: ".ICON_ALIGN."; border:0\"  src=\"".$category_icon."\" alt=\"\" /></a>";
				$caption = $tmp.$caption."</td></tr></table>";
			}else{
				$caption .= "</td><td style=\"text-align:right; width:50%\"><a href=\"index.php?cat.".$category_id."\"><img style=\"float: ".ICON_ALIGN."; border:0\"  src=\"".$category_icon."\" alt=\"\" /></a></td></tr></table>";
			}
		}

		if(INFO_POSITION == "belowcaption"){
			$text = "<div style=\"text-align:".INFO_ALIGN."\">".$info_text." ".$ptext."</div>";
		}else{
			unset($text);
		}


		if(ICON_SHOW == TRUE && ICON_POSITION == "body"){
			$text .= "<a href=\"index.php?cat.".$category_id."\"><img style=\"float: ".ICON_ALIGN."; border:0\"  src=\"".$category_icon."\" alt=\"\" /></a>";
		}

		if(TITLE_POSITION == "body"){
			$text .= "<div style=\"text-align:".TITLE_ALIGN."\">".TITLE_STYLE_START.$news_title.TITLE_STYLE_END."</div><br />";
		}

		$text .= "<div style=\"text-align:".TEXT_ALIGN."\">".
		stripslashes($news_body);

		$text .= "</div>";

		if($modex == "preview" && $news_extended != ""){
			$text .= "<br />[Extended text]: ".$news_extended;
		}else if($news_extended != "" && $modex != "extend"){
			$text .= "<br /><a href=\"index.php?extend.".$news_id."\">".EXTENDED_STRING."</a>";
		}
		
		if($modex == "extend"){
			$text .= "<br />".$news_extended;
		}
		if($news_url != ""){
			$text .= "<br />".URL_TEXT.$news_url."<br />";
		}
		if($news_source != ""){
			$text .= SOURCE_TEXT.$news_source."<br />";
		}

		if(INFO_POSITION == "body"){
			$text .= "<div style=\"text-align:".INFO_ALIGN."\">";
			$text .= $info_text." ".$ptext."</div>";
		}

		$ns = new table;
		$ns -> tablerender($caption, $text, $category_id);
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class auth{
	
	function authform(){
		/*
		# Admin auth login
		#
		# - parameters		none
		# - return				null
		# - scope					public
		*/
		echo "<div style=\"align:center\">";
		$text =  "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n
<table style=\"width:40%\" align=\"center\">
<tr>
<td style=\"width:15%\" class=\"defaulttext\">".LAN_16."</td>
<td><input class=\"tbox\" type=\"text\" name=\"authname\" size=\"30\" value=\"$authname\" maxlength=\"20\" />\n</td>
</tr>
<tr>
<td style=\"width:15%\" class=\"defaulttext\">".LAN_17."</td>
<td><input class=\"tbox\" type=\"password\" name=\"authpass\" size=\"30\" value=\"\" maxlength=\"20\" />\n</td>
</tr>
<tr>
<td style=\"width:15%\"></td>
<td>
<input class=\"button\" type=\"submit\" name=\"authsubmit\" value=\"Log In\" /> 
</td>
</tr>
</table>";

$au = new table;
$au -> tablerender(LAN_18, $text);
echo "</div>";
	}

	function authcheck($authname, $authpass){
		/*
		# Admin auth check
		# - parameter #1:		string $authname, entered name
		# - parameter #2:		string $authpass, entered pass
		# - return				boolean if fail, else result array
		# - scope					public
		*/
		$sql_auth = new db;
		if($sql_auth -> db_Select("admin", "*", "admin_name='$authname' ")){
			if($sql_auth -> db_Select("admin", "*", "admin_name='$authname' AND admin_password='".md5($authpass)."' ")){
				$row = $sql_auth -> db_Fetch();
				return $row;
			}else{
				$row = array("fop");
				return $row;
			}
		}else{
			$row = array("fon");
			return $row;
		}
	}
}

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

function timezone(){
	/*
	# Render style table
	# - parameters		none
	# - return				timezone arrays
	# - scope					public
	*/
	global $timezone, $timearea;
	$timezone = array("-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "GMT", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9", "+10", "+11", "+12", "+13");
	$timearea = array("International DateLine West", "Samoa", "Hawaii", "Alaska", "Pacific Time (US and Canada)", "Mountain Time (US and Canada)", "Central Time (US and Canada), Central America", "Eastern Time (US and Canada)", "Atlantic Time (Canada)", "Greenland, Brasilia, Buenos Aires, Georgetown", "Mid-Atlantic", "Azores", "GMT - UK, Ireland, Lisbon", "West Central Africa, Western Europe", "Greece, Egypt, parts of Africa", "Russia, Baghdad, Kuwait, Nairobi", "Abu Dhabi, Kabul", "Islamabad, Karachi", "Astana, Dhaka", "Bangkok, Rangoon", "Hong Kong, Singapore, Perth, Beijing", "Tokyo, Seoul", "Brisbane, Canberra, Sydney, Melbourne", "Soloman Islands", "New Zealand", "Nuku'alofa");
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class textparse{
	function tp($text, $mode="off", $len=6){
		/*
		# Pre parse
		# - parameter #1:		string $text, text to parse
		# - parameter #2:		string $mode, on=HTML allowed, default off
		# - return				parsed text
		# - scope					public
		*/

//		if($mode == "off"){
//			$text = str_replace("<","&lt;",$text);
//			$text = str_replace(">","&gt;",$text);
//		}
//		$text=str_replace("&","&amp;",$text);


		if($mode == "off"){
			$text = htmlentities($text);
		}

		$search = array("[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]", "[img]", "[/img]", "[center]", "[/center]", "[left]", "[/left]", "[right]", "[/right]", "[blockquote]", "[/blockquote]", "[code]", "[/code]");
		$replace = array("<b>", "</b>", "<i>", "</i>", "<u>", "</u>", "<img alt=\"\" src=\"", "\" />", "<div style=\"text-align:center\">", "</div>", "<div style=\"text-align:left\">", "</div>", "<div style=\"text-align:right\">", "</div>", "<span class=\"indent\">", "</span>", "<code>", "</code>");
//		if($mode == "off"){
//			$text = wordwrap($text, 100);
//		}

//	echo $mode."<br />".$len;

		if($mode == "off" && $len != 6){
			$text = wordwrap($text, $len, "\n", 1);
		}
		$text = eregi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\">\\2://\\3\\4</a>\\5", $text);
		$text = str_replace($search,$replace, $text);

		$p = array();
		$r = array();
		$p[0] = "#\[link\]([a-z]+?://){1}(.*?)\[/link\]#si";
		$r[0] = '<a href="\1\2">\1\2</a>';
		$p[1] = "#\[link\](.*?)\[/link\]#si";
		$r[1] = '<a href="http://\1">\1</a>';
		$p[2] = "#\[link=([a-z]+?://){1}(.*?)\](.*?)\[/link\]#si";
		$r[2] = '<a href="\1\2">\3</a>';
		$p[3] = "#\[link=(.*?)\](.*?)\[/link\]#si";
		$r[3] = '<a href="http://\1">\2</a>';
		$p[4] = "#\[email\](.*?)\[/email\]#si";
		$r[4] = '<a href="mailto:\1">\1</a>';
		$p[5] = "#\[email=(.*?){1}(.*?)\](.*?)\[/email\]#si";
		$r[5] = '<a href="mailto:\1\2">\3</a>';
		$p[6] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
		$r[6] = '<a href="\1\2">\1\2</a>';
		$p[7] = "#\[url\](.*?)\[/url\]#si";
		$r[7] = '<a href="http://\1">\1</a>';
		$p[8] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
		$r[8] = '<a href="\1\2">\3</a>';
		$text = preg_replace($p, $r, $text);

		$text = preg_replace("/\[quote=(.*?)\](.*?)\[\/quote\]/si", "<i>Originally posted by \\1: \"\\2\"</i>", $text);
		addslashes($text);
		if($len != "preview"){	
			$text = mysql_escape_string($text);
		}
		return($text);
	}
	
	function editparse($text){
		$search = array("<b>", "</b>", "<i>", "</i>", "<u>", "</u>", "<div style=\"text-align:center\">", "</div>", "<div style=\"text-align:left\">", "</div>", "<div style=\"text-align:right\">", "</div>", "<code>", "</code>");
		$replace = array("[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]", "[center]", "[/center]", "[left]", "[/left]", "[right]", "[/right]","[code]", "[/code]");
		$text = str_replace($search,$replace, $text);

		$text = preg_replace("/\<img alt=\"\" src=\"(.*?)\" \/>/si", "[img]\\1[/img]", $text);
		$text = preg_replace("/\<a href=\"(.*?)\">(.*?)<\/a>/si", "[link=\\1]\\2[/link]", $text);
		$text = preg_replace("/\<span class=\"indent\">(.*?)<\/span>/si", "[blockquote]\\1[/blockquote]", $text);

		return $text;
	}

	function tpa($text, $mode="off"){
		/*
		# Post parse
		# - parameter #1:		string $text, text to parse
		# - parameter #2:		string $mode, on=line breaks not replaced, default off
		# - return				parsed text
		# - scope					public
		*/
		global $pref;

		if($pref['profanity_filter'][1] == 1){
			$prof = LAN_24;
			$text = eregi_replace($prof, $pref['profanity_replace'][1], $text);
		}
		if($pref['smiley_activate'][1] == 1){
			if(eregi("admin", $_SERVER['PHP_SELF'])){
				require_once("../plugins/emoticons.php");
			}else{
				require_once("plugins/emoticons.php");
			}
			$text = emoticons($text);
		}

		$search = array("[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]", "[img]", "[/img]", "[center]", "[/center]", "[left]", "[/left]", "[right]", "[/right]", "[blockquote]", "[/blockquote]", "[code]", "[/code]");
		$replace = array("<b>", "</b>", "<i>", "</i>", "<u>", "</u>", "<img alt=\"\" src=\"", "\" />", "<div style=\"text-align:center\">", "</div>", "<div style=\"text-align:left\">", "</div>", "<div style=\"text-align:right\">", "</div>", "<span class=\"indent\">", "</span>", "<code>", "</code>");
		$text = str_replace($search,$replace, $text);
		$text = str_replace("<br>","<br />", $text);
		$text = stripslashes($text);
		

		if($mode == "off"){
			$text = nl2br($text);
			$text = ereg_replace("<br /><br />", "<br />", $text);
		}
		return $text;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function sitelinks(){
	/*
	# Render style links
	# - parameters		none
	# - return				parsed text
	# - scope					null
	*/
	$text = PRELINK;
	if(ADMIN == TRUE){
		$text .= LINKSTART."<a href=\"admin/admin.php\">Admin Area</a>".LINKEND."\n";
	}
	$sql = new db;
	$sql -> db_Select("links", "*", "link_category='1' ORDER BY link_order ASC");
	while(list($link_id_, $link_name_, $link_url_) = $sql-> db_Fetch()){
		$text .=  LINKSTART."<a href=\"".$link_url_."\">".$link_name_."</a>".LINKEND."\n";
	}
	$text .= POSTLINK;
	if(LINKDISPLAY == 2){
		$ns = new table;
		$ns -> tablerender(LAN_183, $text);
	}else{
		echo $text;
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
			return ereg_replace(" 0", " ", date($pref['longdate'][1], $datestamp));
		}else if($mode == "short"){
			return ereg_replace(" 0", " ", date($pref['shortdate'][1], $datestamp));
		}else{
			return ereg_replace(" 0", " ", date($pref['forumdate'][1], $datestamp));
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class online {
	
	var $timeout = 120;

	function refresh(){
		/*
		# Refresh users online
		# - parameters		none
		# - return				user array
		# - scope					public
		*/
		$use = new db;
		$timestamp = time();
        $timeout = $timestamp - $this->timeout;
		$ip = getip();
		$use -> db_Delete("online", "online_timestamp < $timeout");
		$use -> db_Delete("online", "online_ip='$ip' ");
		if(USER != FALSE){
			$un = USERID.".".USERNAME;
			$use -> db_Insert("online", " '$timestamp', '1', '".$un."', '$ip', '".$_SERVER['PHP_SELF']."' ");
		}else{
			$un = "0";
			$use -> db_Insert("online", " '$timestamp', '0', '".$un."', '$ip', '".$_SERVER['PHP_SELF']."' ");
		}
		

		$ruser[0] = $use -> db_Count("online", "(*)", " WHERE online_location='".$_SERVER['PHP_SELF']."' ");
		$ruser[1] = $use -> db_Count("online");
	
		if($use -> db_Select("online", "*", "online_flag='1' ")){
			while(list($null, $null, $online_user_id) = $use-> db_Fetch()){
				$fca = explode(".", $online_user_id);
				$userid = $fca[0];
				$username = $fca[1];
				if(!eregi($username, $ruser[2])){
					$ruser[2] .= "<a href=\"user.php?id.".$userid."\">".$username."</a>&nbsp; ";
				}
			}
		}
		return $ruser;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
// code updated by que 18/08/02
class nextprev{
	function nextprev($url, $from, $view, $total, $td, $qs=""){
		/*
		# Next previous pages
		# - parameter #1:		string $url, refer url
		# - parameter #2:		int $from, start figure
		# - parameter #3:		int $view, items per page
		# - parameter #4:		int $total, total items
		# - parameter #5:		string $td, comfort text
		# - parameter #6:		string $qs, QUERY_STRIING, default null
		# - return				null
		# - scope					public
		*/
  if($total == 0){
   return;
  }
  $ns = new table;
  echo "<table style=\"width:100%\">
  <tr>";
  if($from > 1){
   $s = $from-$view;
   echo "<td style=\"width:33%\" class=\"np\">";
   if($qs != ""){
		$text = "<div style=\"text-align:left\"><span class=\"smalltext\"><a href=\"".$url."?".$s.".".$qs."\">".LAN_25."</a></span></div>";
   }else{
		$text = "<div style=\"text-align:left\"><span class=\"smalltext\"><a href=\"".$url."?".$s."\">".LAN_25."</a></span></div>";
   }
   echo $text;
  }else{
   echo "<td style=\"width:33%\">&nbsp;";
  }
 
  echo "</td>\n<td style=\"width:34%\" class=\"np\">";
  $start = $from+1;
  $finish = $from+$view;
  if($finish>$total){
   $finish = $total;
  }
  $text = "<div style=\"text-align:center\"><span class=\"smalltext\">$td $start - $finish of $total</span></div>";
  echo $text;
 
  $s = $from+$view;
  if($s < $total){
   echo "</td><td style=\"width:33%\" class=\"np\">";
   if($qs != ""){
		$text = "<div style=\"text-align:right\"><span class=\"smalltext\"><a href=\"".$url."?".$s.".".$qs."\">".LAN_26."</a></span></div></td>";
   }else{
		$text = "<div style=\"text-align:right\"><span class=\"smalltext\"><a href=\"".$url."?".$s."\">".LAN_26."</a></span></div></td>";
   }
   echo $text;
  }else{
   echo "</td><td style=\"width:33%\">&nbsp;</td>";
  }
  echo "</tr>\n</table>";
 }
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

// code updated by que 18/08/02
class get_count{
	function get_count($url){
		/*
		# Counter per url
		# - parameter #1:		string $url, refer url
		# - return				null
		# - scope					public
		*/
		$cnt = new db;
		$date = date("Y-m-d");
		$cnt -> db_Select("stat_counter", "*", "counter_date='$date' AND counter_url='$url' ");
		$row = $cnt -> db_Fetch();
		echo LAN_21.$row['counter_total']." (unique: ".$row['counter_unique'].")<br />";
 
		$cnt -> db_Select("stat_counter", "*", "counter_url='$url' ");
		while($row = $cnt -> db_Fetch()){
			$unique_ever += $row[2];
			$total_ever += $row[3];
		}
		echo LAN_22.$total_ever." (unique: $unique_ever)<br />";
 
		$cnt = new dbFunc;

		$total_page_views = $cnt -> db_Count("SELECT sum(counter_total) FROM stat_counter");
		$row= $cnt -> dbFetch();
		echo LAN_23.$total_page_views." (unique: ";
 
		$total_page_views = $cnt -> dbCount("SELECT sum(counter_unique) FROM ".MUSER."stat_counter");
		$row= $cnt -> dbFetch();
		echo $total_page_views.")<br /><br />";
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class dbfunc{

	// NOTE: This class is now depracated, kept in for third-party plugin compatibilty.

	var $mySQLserver;
	var $mySQLuser;
	var $mySQLpassword;
	var $mySQLdefaultdb;
	var $mySQLaccess;
	var $mySQLresult;
	var $mySQLrows;
	var $mySQLerror;

	function dbRows(){
		$rows = $this->mySQLrows = @mysql_num_rows($this->mySQLresult);
		return $rows;
		$this->dbError("dbRows");
	}

	function dbCount($query){
		if($this->mySQLresult = @mysql_query($query)){
			$rows = $this->mySQLrows = @mysql_fetch_array($this->mySQLresult);
			return $rows[0];
		}else{
			$this->dbError("dbCount ($query)");
		}
	}

	function dbQuery($query){
		if($this->mySQLresult = @mysql_query($query)){
			$this->dbError("dbQuery");
			return $this->dbRows();
		}else{
			$this->dbError("dbQuery ($query)");
			return FALSE;
		}
	}

	function dbFetch(){
		if($row = @mysql_fetch_array($this->mySQLresult)){
			$this->dbError("dbFetch");
			return $row;
		}else{
			$this->dbError("dbFetch");
			return FALSE;
		}
	}
	function dbError($from){
		if($error_message = @mysql_error()){
			if($this->mySQLerror == TRUE){
				echo "<b>mySQL Error!</b> Function: $from. [".@mysql_errno()." - $error_message]<br />";
				return $error_message;
			}
		}
	}
	function dbInsert($query){
		return $this->mySQLresult = mysql_query($query);
	}
}
function getreferer(){
	$sql = new db;
	$referer = $_SERVER['HTTP_REFERER'];
	if($referer != ""){
		$siteurl = parse_url(SITEURL);
		if(!eregi($siteurl['host'], $referer) && !eregi("localhost", $referer)){
			if($referer != ""){
				if($pref['log_refertype'][1] == 0){
					// log domain only
					$rl = parse_url($referer);
					$ref =  eregi_replace("www.", "", $rl['host']);
					if($sql -> db_Select("stat_info", "*", "info_name='$referer' ")){
						$sql -> db_Update("stat_info", "info_count=info_count+1 WHERE info_name='$referer' ");
					}else{
						$sql -> db_Insert("stat_info", " '$ref', '1', '6' ");
					}
				}else{
				// Log whole URL
					if($sql -> db_Select("stat_info", "*", "info_name='$referer' ")){
						$sql -> db_Update("stat_info", "info_count=info_count+1 WHERE info_name='$referer' ");
					}else{
						$sql -> db_Insert("stat_info", " '$referer', '1', '6' ");
					}
				}
			}
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function timing_start($name = "default") {
    global $timing_start_times;
    $timing_start_times[$name] = explode(' ', microtime());
}

function timing_stop($name = "default") {
    global $timing_stop_times;
    $ss_timing_stop_times[$name] = explode(' ', microtime());
}

function timing_return($name = "default") {
    global $timing_start_times, $ss_timing_stop_times;
    if (!isset($timing_start_times[$name])) {
        return 0;
    }
    if (!isset($timing_stop_times[$name])) {
        $stop_time = explode(' ', microtime());
    }
    else {
        $stop_time = $timing_stop_times[$name];
    }
    $current = $stop_time[1] - $timing_start_times[$name][1];
    $current += $stop_time[0] - $timing_start_times[$name][0];
	$current = number_format($current, 4);
    return $current;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class create_rss{

	function create_rss(){
		/*
		# rss create
		# - parameters		none
		# - return				null
		# - scope					public
		*/
		$rsd = new db;
		$rsd -> db_Select("e107");
		list($e107_author, $e107_url, $e107_version, $e107_build, $e107_datestamp) = $rsd-> db_Fetch();
		$rsd -> db_Select("prefs");

		list($sitename, $siteurl, $sitebutton, $sitetag, $sitedescription, $siteadmin, $siteadminemail, $sitetheme, $posts, $chatbox_d, $chat_posts, $poll_d, $disclaimer, $headline_d, $headline_update, $article_d, $counter_d) = $rsd-> db_Fetch();
		$rsd -> db_Select("news", "*", "ORDER BY news_datestamp DESC LIMIT 0,10", $mode="no_where");
		$host = "http://".getenv("HTTP_HOST");

$rss = "<?xml version=\"1.0\"?>
<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">
<rss version=\"0.91\">
<channel>
<title>".SITENAME."</title>
<link>".SITEURL."</link>
<description>".SITEDESCRIPTION."</description>
<language>en-us</language>
<copyright>".SITEDISCLAIMER."</copyright>
<managingEditor>".SITEADMIN."</managingEditor>
<webMaster>".SITEADMINEMAIL."</webMaster>
<image>
<title>".SITENAME."</title> 
<url>".SITEBUTTON."</url> 
<link>".SITEURL."</link> 
<width>90</width> 
<height>30</height> 
<description>".SITETAG."</description> 
</image>
";

  while(list($news_id, $news_title, $news_body, $news_datestamp, $news_author, $news_source, $news_url, $news_catagory) = $rsd-> db_Fetch()){
  		$tmp = explode(" ", $news_body);
		unset($nb);
		for($a=0; $a<=100; $a++){
			$nb .= $tmp[$a]." ";
		}
  		$nb = htmlentities($nb); 
		$text .= $news_title."\n".SITEURL."/comment.php?".$news_id."\n\n";
		$rss .= "<item>
<title>".$news_title."</title>
<description>".$nb."</description>
<link>".SITEURL."/comment.php?".$news_id."</link> 
</item>
";
	}
	$rss .= "</channel>
</rss>";
	$fp = fopen("../backend/news.xml","w");
	@fwrite($fp, $rss);
	fclose($fp);

	$fp = fopen("../backend/news.txt","w");
	@fwrite($fp, $text);
	fclose($fp);

	if(!fwrite){
		$text = "<div style=\"text-align:center\">".LAN_19."</div>";
		$ns -> tablerender("<div style=\"text-align:center\">".LAN_20."</div>", $text);
	}
}
}
?>