<?php
$text = "";
if($pref['user_reg'][1] == 1 || ADMIN == TRUE){

	if(USER == TRUE || ADMIN == TRUE){
		$tmp = ($_COOKIE['userkey'] ? explode(".", $_COOKIE['userkey']) : explode(".", $_SESSION['userkey']));
		$uid = $tmp[0]; $upw = $tmp[1];
		$sql = new db;
		if($sql -> db_Select("user", "*", "user_id='$uid' AND user_password='$upw' ")){
			if(ADMIN == TRUE){
				$text = ($pref['maintainance_flag'][1]==1 ? "<div style='text-align:center'><b>The maintenance flag is true - this means normal visitors are being redirected to sitedown.php. To reset the flag go to admin/maintenance.</div></b><br />" : "" );
				$text .= "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_ADMIN."admin.php'>Admin</a><br />";
			}
			$text .= "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."usersettings.php'>Settings</a>
<br />
<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."?logout'>".LAN_172."</a>";
		

			if(!$sql -> db_Select("online", "*", "online_ip='$ip' AND online_user_id='0' ")){
				$sql -> db_Delete("online", "online_ip='$ip' AND online_user_id='0' ");
			}

			$time = USERLV;
			$new_news = $sql -> db_Count("news", "(*)", "WHERE news_datestamp>'".$time."' "); if(!$new_news){ $new_news = "no"; }
			$new_comments = $sql -> db_Count("comments", "(*)", "WHERE comment_datestamp>'".$time."' "); if(!$new_comments){ $new_comments = "no"; }
			$new_chat = $sql -> db_Count("chatbox", "(*)", "WHERE cb_datestamp>'".$time."' "); if(!$new_chat){ $new_chat = "no"; }
			$new_forum = $sql -> db_Count("forum_t", "(*)", "WHERE thread_datestamp>'".$time."' "); if(!$new_forum){ $new_forum = "no"; }
			$new_users = $sql -> db_Count("user", "(*)", "WHERE user_join>'".$time."' "); if(!$new_users){ $new_users = "no"; }


			$text .= "<br /><br />
			<span class='smalltext'>
			Since your last visit there have been 
			$new_news ".($new_news == 1 ? "news item" : "news items").", 
			$new_chat ".($new_chat == 1 ? "chatbox post" : "chatbox posts").", 
			$new_comments ".($new_comments == 1 ? "comment" : "comments").", 
			$new_forum ".($new_forum == 1 ? "forum post" : "forum posts")." and 
			$new_users ".($new_users == 1 ? "new site member" : "new site members").".<span>";
			$caption = (file_exists(THEME."images/login_menu.png") ? "<img src='".THEME."images/login_menu.png' alt='' /> ".LAN_30." ".USERNAME : LAN_30." ".USERNAME);
			$ns -> tablerender($caption, $text);


		}else{
			$text = "<div style='text-align:center'>".LAN_171."<br /><br />
			<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."index.php?logout'>".LAN_172."</a></div>";
			$ns -> tablerender(LAN_173, $text);
		}
	}else{
		if(LOGINMESSAGE != ""){
			$text = "<div style='text-align:center'>".LOGINMESSAGE."</div>";
		}
		$text .=  "<div style='text-align:center'>
<form method='post' action='".e_SELF;
if(e_QUERY){
	$text .= "?".e_QUERY;
}

$text .= "'><p>
".LAN_16."<br />
<input class='tbox' type='text' name='username' size='15' value='' maxlength='20' />\n
<br />
".LAN_17."
<br />
<input class='tbox' type='password' name='userpass' size='15' value='' maxlength='20' />\n
<br />
<input class='button' type='submit' name='userlogin' value='Login' />\n
<br />
<input type='checkbox' name='autologin' value='1' /> Auto Login
<br /><br />
[ <a href='".e_BASE."signup.php'>".LAN_174."</a> ]<br />[ <a href='".e_BASE."fpw.php'> ".LAN_212."</a> ]
</p>
</form>
</div>";
		$caption = (file_exists(THEME."images/login_menu.png") ? "<img src='".THEME."images/login_menu.png' alt='' /> ".LAN_30 : LAN_30);
		$ns -> tablerender($caption, $text);
	}
}
?>