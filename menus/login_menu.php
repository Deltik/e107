<?php
$text = "";
if($pref['user_reg'][1] == 1 || ADMIN == TRUE){

	if(USER == TRUE || ADMIN == TRUE){
		if(IsSet($_SESSION['userkey'])){ $uk = $_SESSION['userkey']; }else{ $uk = $_COOKIE['userkey']; }
		$tmp = explode(".", $uk); $uid = $tmp[0]; $upw = $tmp[1];
		if(Empty($upw)){ session_destroy; session_unregister; return FALSE; }
		if($sql -> db_Select("user", "*", "user_id='$uid' AND user_password='$upw' ")){
			$text = "<img src=\"".THEME."images/bullet2.gif\" alt=\"bullet\" /> <a href=\"usersettings.php\">Settings</a>
<br />
<img src=\"".THEME."images/bullet2.gif\" alt=\"bullet\" /> <a href=\"".$_SERVER['PHP_SELF']."?logout\">".LAN_172."</a>";
		$ns -> tablerender(LAN_30." ".USERNAME, $text);

			if(!$sql -> db_Select("online", "*", "online_ip='$ip' AND online_user_id='0' ")){
				$sql -> db_Delete("online", "online_ip='$ip' AND online_user_id='0' ");
				$sql -> db_Insert("online", " '$timestamp', '1', '".USERID."', '$ip', '".$_SERVER['PHP_SELF']."' ");
			}
		}else{
			$text = "<div style=\"text-align:center\">".LAN_171."<br /><br />
			<img src=\"".THEME."images/bullet2.gif\" alt=\"bullet\" /> <a href=\"index.php?logout\">".LAN_172."</a></div>";
			$ns -> tablerender(LAN_173, $text);
		}
	}else{
		if(LOGINMESSAGE != ""){
			$text = "<div style=\"text-align:center\">".LOGINMESSAGE."</div>";
		}
		$text .=  "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
<div style=\"text-align:center\">
".LAN_16."<br />
<input class=\"tbox\" type=\"text\" name=\"username\" size=\"15\" value=\"\" maxlength=\"20\" />\n
<br />
".LAN_17."
<br />
<input class=\"tbox\" type=\"password\" name=\"userpass\" size=\"15\" value=\"\" maxlength=\"20\" />\n
<br />
<input class=\"button\" type=\"submit\" name=\"userlogin\" value=\"Login\" />\n
<br /><br />
[ <a href=\"signup.php\">".LAN_174."</a> ]<br />[ <a href=\"fpw.php\">".LAN_212."</a> ]
</div>
</form>";
		$ns -> tablerender(LAN_175, $text);
	}
}
?>