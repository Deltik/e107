<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/online.php,v $
|     $Revision: 1.13 $
|     $Date: 2005/12/24 22:53:38 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");
require_once(HEADERF);

global $listuserson;

foreach($listuserson as $uinfo => $pinfo) {
	$class_check = TRUE;
	list($oid, $oname) = explode(".", $uinfo, 2);
	$sql2 = new db;
	$sql2->db_Select("user", "user_id", "user_admin > '0' ");
	$row_2 = $sql2->db_Fetch();
	$online_location = $pinfo;
	$online_location_page = substr(strrchr($online_location, "/"), 1);
	if (!strstr($online_location, "forum_") || !strstr($online_location, "content.php") || !strstr($online_location, "comment.php")) {
		$online_location_page = str_replace(".php", "", substr(strrchr($online_location, "/"), 1));
	}
	if ($online_location_page == "log" || $online_location_page == "error") {
		$online_location = "news.php";
		$online_location_page = NEWS;
	}
	if ($online_location_page == "request") {
		$online_location = "download.php";
	}
	if ($online_location_page == "article") {
		$online_location_page = ARTICLEPAGE;
	}
	if ($online_location_page == "chat") {
		$online_location_page = CHAT;
	}
	//if($online_location_page == "comment"){$online_location_page = COMMENT;}
	if ($online_location_page == "content") {
		$online_location_page = CONTENT;
	}
	if ($online_location_page == "download") {
		$online_location_page = DOWNLOAD;
	}
	if ($online_location_page == "email") {
		$online_location_page = EMAIL;
	}
	if ($online_location_page == "forum") {
		$online_location_page = FORUM;
	}
	if ($online_location_page == "links") {
		$online_location_page = LINKS;
	}
	if ($online_location_page == "news") {
		$online_location_page = NEWS;
	}
	if ($online_location_page == "oldpolls") {
		$online_location_page = OLDPOLLS;
	}
	if ($online_location_page == "print") {
		$online_location_page = PRINTPAGE;
	}
	if ($online_location_page == "login") {
		$online_location_page = LOGIN;
	}
	if ($online_location_page == "search") {
		$online_location_page = SEARCH;
	}
	if ($online_location_page == "stats") {
		$online_location_page = STATS;
	}
	if ($online_location_page == "submitnews") {
		$online_location_page = SUBMITNEWS;
	}
	if ($online_location_page == "upload") {
		$online_location_page = UPLOAD;
	}
	if ($online_location_page == "user") {
		$online_location_page = USERPAGE;
	}
	if ($online_location_page == "usersettings") {
		$online_location_page = USERSETTINGS;
	}
	if ($online_location_page == "new") {
		$online_location_page = LISTNEW;
	}
	if ($online_location_page == "online") {
		$online_location_page = ONLINE;
	}
	if ($online_location_page == "userposts") {
		$online_location_page = USERPOSTS;
	}
	if ($online_location_page == "subcontent") {
		$online_location_page = SUBCONTENT;
	}
	if ($online_location_page == "top") {
		$online_location_page = TOP;
	}
	//commonly used plugin pages
	if ($online_location_page == "bugtracker") {
		$online_location_page = BUGTRACKER;
	}
	if ($online_location_page == "event") {
		$online_location_page = EVENT;
	}
	if ($online_location_page == "calendar") {
		$online_location_page = CALENDAR;
	}
	if ($online_location_page == "faq") {
		$online_location_page = FAQ;
	}
	if ($online_location_page == "pm") {
		$online_location_page = PM;
	}
	if ($online_location_page == "survey") {
		$online_location_page = SURVEY;
	}
	if (strstr($online_location, "content.php")) {
		$tmp = explode(".", substr(strrchr($online_location, "php."), 2));
		if ($tmp[0] == "article") {
			$sql->db_Select("content", "content_heading, content_class", "content_id='".intval($tmp[1])."'");
			list($content['content_heading'], $content['content_class']) = $sql->db_Fetch();
			$online_location_page = ARTICLE.": ".$content['content_heading'];
			$online_location = str_replace("php.", "php?", $online_location);
			if (!check_class($content['content_class'])) {
				$class_check = FALSE;
				$online_location_page = ARTICLE.": \"".CLASSRESTRICTED."\"";
			}
		} elseif($tmp[0] == "review") {
			$sql->db_Select("content", "content_heading, content_class", "content_id='".intval($tmp[1])."'");
			list($content['content_heading'], $content['content_class']) = $sql->db_Fetch();
			$online_location_page = REVIEW.": ".$content['content_heading'];
			$online_location = str_replace("php.", "php?", $online_location);
			if (!check_class($content['content_class'])) {
				$class_check = FALSE;
				$online_location_page = REVIEW.": \"".CLASSRESTRICTED."\"";
			}
		} elseif($tmp[0] == "content") {
			$sql->db_Select("content", "content_heading, content_class", "content_id='".intval($tmp[1])."'");
			list($content['content_heading'], $content['content_class']) = $sql->db_Fetch();
			$online_location_page = CONTENT.": ".$content['content_heading'];
			$online_location = str_replace("php.", "php?", $online_location);
			if (!check_class($content['content_class'])) {
				$class_check = FALSE;
				$online_location_page = CONTENT.": \"".CLASSRESTRICTED."\"";
			}
		}
	}
	if (strstr($online_location, "comment.php")) {
		$tmp = explode(".php.", $online_location);
		$tmp = explode(".", $tmp[1]);
		if ($tmp[1] == "news") {
			$id = ($tmp[0] == "reply" ? $tmp[3] : $tmp[2]);
			$sql->db_Select("news", "news_title, news_class", "news_id='".intval($id)."'");
			list($news['news_title'], $news['news_class']) = $sql->db_Fetch();
			$online_location_page = ($tmp[0] == "reply" ? COMMENT.": ".ONLINE_EL12." > ".$news['news_title'] : COMMENT.": ".$news['news_title']);
			$online_location = "comment.php?comment.news.$id";
			if (!check_class($news['news_class'])) {
				$class_check = FALSE;
				$online_location_page = COMMENT.": \"".CLASSRESTRICTED."\"";
			}
		} elseif($tmp[1] == "poll") {
			$id = ($tmp[0] == "reply" ? $tmp[3] : $tmp[2]);
			$sql->db_Select("poll", "poll_title", "poll_id='".intval($id)."'");
			list($poll['poll_title']) = $sql->db_Fetch();
			$online_location_page = POLLCOMMENT.": ".$poll['poll_title'];
			$online_location = "comment.php?comment.poll.$id";
		} else {
			$online_location_page = COMMENT;
			$class_check = FALSE;
		}
	}
	if (strstr($online_location, "forum")) {
		$tmp = explode(".", substr(strrchr($online_location, "php."), 2));
		if (strstr($online_location, "_viewtopic")) {
			if ($tmp[2]) {
				$pref['forum_postspage'] = ($pref['forum_postspage'] ? $pref['forum_postspage'] : 10);
				$t_page = $tmp[2]/$pref['forum_postspage'] +1;
			} else {
				$t_page = 1;
			}
			$qry = "
			SELECT t.thread_name, f.forum_name, f.forum_class from #forum_t AS t
			LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
			WHERE t.thread_id = '".intval($tmp[0])."'
			";
			$sql->db_Select_gen($qry);
			$forum = $sql->db_Fetch();
			$online_location_page = ONLINE_EL13." .:. ".$forum['forum_name']."->".ONLINE_EL14." .:. ".$forum['thread_name']."->".ONLINE_EL15.": ".$t_page;
			$online_location = str_replace("php.", "php?", $online_location);
			if (!check_class($forum['forum_class'])) {
				$class_check = FALSE;
				$online_location_page = ONLINE_EL13.": \"".CLASSRESTRICTED."\"";
			}
		} elseif(strstr($online_location, "_viewforum")) {
			$sql->db_Select("forum", "forum_name, forum_class", "forum_id='".intval($tmp[0])."' ");
			list($forum['forum_name'], $forum['forum_class']) = $sql->db_Fetch();
			$online_location_page = ONLINE_EL13." .:. ".$forum['forum_name'];
			$online_location = str_replace("php.", "php?", $online_location);
			if (!check_class($forum['forum_class'])) {
				$class_check = FALSE;
				$online_location_page = ONLINE_EL13.": \"".CLASSRESTRICTED."\"";
			}
		} elseif(strstr($online_location, "_post")) {
			$sql->db_Select("forum_t", "thread_name, thread_forum_id", "thread_forum_id='".intval($tmp[0])."' AND thread_parent='0'");
			list($forum_t['thread_name'], $forum_t['thread_forum_id']) = $sql->db_Fetch();
			$sql->db_Select("forum", "forum_name", "forum_id='".$forum_t['thread_forum_id']."'");
			list($forum['forum_name']) = $sql->db_Fetch();
			$online_location_page = ONLINE_EL12.": ".ONLINE_EL13." .:. ".$forum['forum_name']."->".ONLINE_EL14." .:. ".$forum_t['thread_name'];
			$online_location = e_PLUGIN."forum/forum_viewtopic.php?$tmp[0].$tmp[1]";
		}
	}
	if (strstr($online_location, "admin")) {
		$class_check = FALSE;
		$online_location_page = ADMINAREA;
	}
	$pm_installed = ($pref['pm_title'] ? TRUE : FALSE);
	$ONLINE_TABLE_ICON = ($pm_installed && $oid != USERID ? $tp->parseTemplate("{pm_menu.sendpm={$oid}}") : "<img src='".e_PLUGIN."online_extended_menu/images/user.png' alt='' style='vertical-align:middle' />");
	 
	$ONLINE_TABLE_USERNAME = "<a href='".e_BASE."user.php?id.$oid'>$oname</a>";
	$ONLINE_TABLE_LOCATION = ($class_check ? "<a href='$online_location'>$online_location_page</a>" : $online_location_page);
	 
	if (!$ONLINE_TABLE) {
		if (file_exists(THEME."online_template.php")) {
			require_once(THEME."online_template.php");
		} else {
			require_once(e_BASE.$THEMES_DIRECTORY."templates/online_template.php");
		}
	}
	$textstring .= preg_replace("/\{(.*?)\}/e", '$\1', $ONLINE_TABLE);
}
	
$ONLINE_TABLE_MEMBERS_ONLINE = ONLINE_EL1.GUESTS_ONLINE;
$ONLINE_TABLE_GUESTS_ONLINE = ONLINE_EL2.MEMBERS_ONLINE;
if ((MEMBERS_ONLINE + GUESTS_ONLINE) > ($menu_pref['most_members_online'] + $menu_pref['most_guests_online'])) {
	global $sysprefs;
	$menu_pref['most_members_online'] = MEMBERS_ONLINE;
	$menu_pref['most_guests_online'] = GUESTS_ONLINE;
	$menu_pref['most_online_datestamp'] = time();
	$sysprefs->setArray('menu_pref');
}
	
if (!is_object($gen)) {
	$gen = new convert;
}
	
$datestamp = $gen->convert_date($menu_pref['most_online_datestamp'], "short");
	
$ONLINE_TABLE_MOST_EVER_ONLINE = ONLINE_EL8.($menu_pref['most_members_online'] + $menu_pref['most_guests_online']);
$ONLINE_TABLE_MOST_MEMBERS_ONLINE = strtolower(ONLINE_EL2).$menu_pref['most_members_online'];
$ONLINE_TABLE_MOST_GUESTS_ONLINE = strtolower(ONLINE_EL1).$menu_pref['most_guests_online'];
$ONLINE_TABLE_DATESTAMP = $datestamp;
	
$total_members = $sql->db_Count("user");
	
if ($total_members > 1) {
	$newest_member = $sql->db_Select("user", "user_id, user_name", "user_ban='0' ORDER BY user_join DESC LIMIT 0,1");
	$row = $sql->db_Fetch();
	 extract($row);
	 
	$ONLINE_TABLE_MEMBERS_TOTAL = "<br />".ONLINE_EL5.": ".$total_members;
	$ONLINE_TABLE_MEMBERS_NEWEST = "<br />".ONLINE_EL6.": ".(USER ? "<a href='".e_BASE."user.php?id.".$user_id."'>".$user_name."</a>" : $user_name);
}
	
$textstart = preg_replace("/\{(.*?)\}/e", '$\1', $ONLINE_TABLE_START);
$textend = preg_replace("/\{(.*?)\}/e", '$\1', $ONLINE_TABLE_END);
$text = $textstart.$textstring.$textend;
	
$ns->tablerender(ONLINE_EL4, $text);
	
require_once(FOOTERF);
?>