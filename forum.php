<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/forum.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("class2.php");

if(strstr(e_QUERY, "untrack")){
	$tmp1 = explode(".", e_QUERY);
	$tmp = str_replace("-".$tmp1[1]."-", "", USERREALM);
	$sql -> db_Update("user", "user_realm='$tmp' WHERE user_id='".USERID."' ");
	header("location:".e_SELF."?track");
	exit;
}

define("IMAGE_e", (file_exists(THEME."forum/e.png") ? "<img src='".THEME."forum/e.png' alt='' />" : "<img src='".e_IMAGE."forum/e.png' alt='' />"));
define("IMAGE_nonew_small", (file_exists(THEME."forum/nonew_small.png") ? "<img src='".THEME."forum/nonew_small.png' alt='' />" : "<img src='".e_IMAGE."forum/nonew_small.png' alt='' />"));
define("IMAGE_new_small", (file_exists(THEME."forum/new_small.png") ? "<img src='".THEME."forum/new_small.png' alt='' />" : "<img src='".e_IMAGE."forum/new_small.png' alt='' />"));
define("IMAGE_closed_small", (file_exists(THEME."forum/closed_small.png") ? "<img src='".THEME."forum/closed_small.png' alt='' />" : "<img src='".e_IMAGE."forum/closed_small.png' alt='' />"));
define("IMAGE_new", (file_exists(THEME."forum/new.png") ? "<img src='".THEME."forum/new.png' alt='".LAN_199."' style='border:0' />" : "<img src='".e_IMAGE."forum/new.png' alt='".LAN_199."' style='border:0' />"));
define("IMAGE_nonew", (file_exists(THEME."forum/nonew.png") ? "<img src='".THEME."forum/nonew.png' alt='' />" : "<img src='".e_IMAGE."forum/nonew.png' alt='' />"));
define("IMAGE_post", (file_exists(THEME."forum/post.png") ? "<img src='".THEME."forum/post.png' alt='' style='border:0; vertical-align:bottom' />" : "<img src='".e_IMAGE."forum/post.png' alt='' style='border:0; vertical-align:bottom' />"));

$gen = new convert;

$FORUMTITLE = LAN_46;
$THREADTITLE = LAN_47;
$REPLYTITLE = LAN_48;
$LASTPOSTITLE = LAN_49;
$INFOTITLE = LAN_191;
$LOGO = IMAGE_e;

$total_topics = $sql -> db_Count("forum_t", "(*)", " WHERE thread_parent='0' ");
$total_replies = $sql -> db_Count("forum_t", "(*)", " WHERE thread_parent!='0' ");
$total_members = $sql -> db_Count("user");
$newest_member = $sql -> db_Select("user", "*", "ORDER BY user_join DESC LIMIT 0,1", $mode="no_where");
list($nuser_id, $nuser_name)  = $sql -> db_Fetch();

$ICONKEY = "
<table style='width:100%'>\n<tr>
<td style='width:2%'>".IMAGE_new_small."</td>
<td style='width:10%'><span class='smallblacktext'>".LAN_79."</span></td>
<td style='width:2%'>".IMAGE_nonew_small."</td>
<td style='width:10%'><span class='smallblacktext'>".LAN_80."</span></td>
<td style='width:2%'>".IMAGE_closed_small."</td>
<td style='width:10%'><span class='smallblacktext'>".LAN_394."</span></td>
</tr>\n</table>\n";

$SEARCH = "
<form method='post' action='search.php'>
<p>
<input class='tbox' type='text' name='searchquery' size='20' value='' maxlength='50' />
<input class='button' type='submit' name='searchsubmit' value='".LAN_180."' />
</p>
</form>\n";

$PERMS = "
<span class='smallblacktext'>".
(USER == TRUE || ANON == TRUE ? LAN_204." - ".LAN_206." - ".LAN_208 : LAN_205." - ".LAN_207." - ".LAN_209)."
</span>\n";

if(USER == TRUE){
	$total_new_threads = $sql -> db_Count("forum_t", "(*)", "WHERE thread_datestamp>'".USERLV."' ");
	if(USERVIEWED != ""){
		$tmp = explode("..", USERVIEWED);
		$total_read_threads = count($tmp);
	}else{
		$total_read_threads = 0;
	}

	$INFO = 
	LAN_30." ".USERNAME."<br />";
	$sql -> db_Select("user", "*",  "user_name='".USERNAME."' ");
	$row = $sql -> db_Fetch();
	extract($row);
	$lastvisit_datestamp = $gen->convert_date($user_lastvisit, "long");
	$datestamp = $gen->convert_date(time(), "long");
	if(!$total_new_threads){
		$INFO .= LAN_31;
	}else if($total_new_threads == 1){
		$INFO .= LAN_32;
	}else{
		$INFO .= LAN_33." ".$total_new_threads." ".LAN_34." ";
	}
	$INFO .= LAN_35;
	if($total_new_threads == $total_read_threads && $total_new_threads !=0 && $total_read_threads >= $total_new_threads){
		$INFO .= LAN_198;
		$allread = TRUE;
	}else if($total_read_threads != 0){
		$INFO .= " (".LAN_196.$total_read_threads.LAN_197.")";
	}

	$INFO .= "<br />
	".LAN_36." ".$lastvisit_datestamp."<br />
	".LAN_37." ".$datestamp.LAN_38.$pref['timezone'];
}else{
	$INFO .= "";
	if(ANON == TRUE){
		$INFO .= LAN_410."<br />".LAN_44;
	}else if(USER == FALSE){
		$INFO .= LAN_410."<br />".LAN_45;
	}
}

if(USER && $allread != TRUE && $total_new_threads && $total_new_threads >= $total_read_threads){
	$INFO .= "<br /><a href='".e_SELF."?mark.all.as.read'>".LAN_199."</a>";
}

if(USERREALM && USER && e_QUERY != "track"){
	$INFO .= "<br /><a href='".e_SELF."?track'>".LAN_393."</a>";
}

$FORUMINFO .= LAN_192.($total_topics+$total_replies)." ".LAN_404.".<br />".LAN_42.$total_members."<br />".LAN_41."<a href='".e_BASE."user.php?id.".$nuser_id."'>".$nuser_name."</a>.\n";

if(!$FORUM_MAIN_START){
	$FORUM_MAIN_START = "<div style='text-align:center'>\n<table style='width:95%' class='fborder' border=1>\n<tr>\n<td colspan='2' style='width:60%; text-align:center' class='fcaption'>{FORUMTITLE}</td>\n<td style='width:10%; text-align:center' class='fcaption'>{THREADTITLE}</td>\n<td style='width:10%; text-align:center' class='fcaption'>{REPLYTITLE}</td>\n<td style='width:20%; text-align:center' class='fcaption'>{LASTPOSTITLE}</td>\n</tr>";
}
if(!$FORUM_MAIN_PARENT){
	$FORUM_MAIN_PARENT = " <tr>\n<td colspan='5' class='forumheader'>{PARENTNAME} {PARENTSTATUS}</td>\n</tr>";
}
if(!$FORUM_MAIN_FORUM){
	$FORUM_MAIN_FORUM = "<tr>\n<td style='width:5%; text-align:center' class='forumheader2'>{NEWFLAG}</td>\n<td style='width:55%' class='forumheader2'>{FORUMNAME}<br /><span class='smallblacktext'>{FORUMDESCRIPTION}</span></td>\n<td style='width:10%; text-align:center' class='forumheader3'>{THREADS}</td>\n<td style='width:10%; text-align:center' class='forumheader3'>{REPLIES}</td>\n<td style='width:20%; text-align:center' class='forumheader3'><span class='smallblacktext'>{LASTPOST}</span></td>\n</tr>";
}
if(!$FORUM_MAIN_END){
	$FORUM_MAIN_END = "</table>\n<div class='spacer'>\n<table style='width:95%' class='fborder'>\n<tr>\n<td colspan='2' style='width:60%' class='fcaption'>{INFOTITLE}</td>\n</tr>\n<tr>\n<td rowspan='2' style='width:5%; text-align:center' class='forumheader3'>{LOGO}</td>\n<td style='width:auto' class='forumheader3'>{INFO}</td>\n</tr>\n<tr>\n<td style='width:95%' class='forumheader3'>{FORUMINFO}</td>\n</tr>\n</table>\n</div>\n<div class='spacer'>\n<table class='fborder' style='width:95%'>\n<tr>\n<td class='forumheader3' style='text-align:center; width:33%'>{ICONKEY}</td>\n<td style='text-align:center; width:33%' class='forumheader3'>{SEARCH}</td>\n<td style='width:33%; text-align:center; vertical-align:middle' class='forumheader3'>{PERMS}</td>\n</tr>\n</table>\n</div>\n";
}

require_once(HEADERF);
$sql2 = new db;

if(!$sql -> db_Select("forum", "*", "forum_parent='0' ORDER BY forum_order ASC")){
	$ns -> tablerender(PAGE_NAME, "<div style='text-align:center'>".LAN_51."</div>");
	require_once(FOOTERF);
	exit;
}

while($row = $sql-> db_Fetch()){
	$status = parse_parent($row);
	extract($row);
	$PARENTSTATUS = $status[0];
	if($status[1]){
		$PARENTNAME = $forum_name;
		$forum_string .= preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_MAIN_PARENT);
		$forums = $sql2 -> db_Select("forum", "*", "forum_parent='".$forum_id."' ORDER BY forum_order ASC ");
		if(!$forums && $status[1]){
			$text .= "<td colspan='5' style='text-align:center' class='forumheader3'>".LAN_52."</td>";
		}else if($status[1]){
			while($row = $sql2-> db_Fetch()){
				extract($row);
				if($forum_class == e_UC_ADMIN && ADMIN){
					$forum_string .= parse_forum($row, LAN_406);
				}else if($forum_class == e_UC_MEMBER && USER){
					$forum_string .= parse_forum($row, LAN_407);
				}else if($forum_class == e_UC_READONLY){
					$forum_string .= parse_forum($row, LAN_408);
				}else if($forum_class && check_class($forum_class)){
					$forum_string .= parse_forum($row, LAN_409);
				}else if(!$forum_class){
					$forum_string .= parse_forum($row);
				}
			}
		}
	}
}

function parse_parent($row){
	extract($row);
	if($forum_class == e_UC_NOBODY){
		$status[0] = "{ ".LAN_398." )";
		$status[1] = FALSE;
	}else if($forum_class == e_UC_MEMBER && !USER){
		$status[1] = FALSE;
	}else if($forum_class == e_UC_MEMBER && USER){
		$status[0] = "( ".LAN_401." )";
		$status[1] = TRUE;
	}else if($forum_class == e_UC_READONLY){
		$status[0] = "( ".LAN_405." )";
		$status[1] = TRUE;
	}else if($forum_class){
		if(check_class($forum_class)){
			$status[0] = "( ".LAN_399." )";
			$status[1] = TRUE;
		}else{
			$status[1] = FALSE;
		}
	}else{
		$status[0] = "";
		$status[1] = TRUE;
	}
	return ($status);
}

function parse_forum($row, $restricted_string=""){
	global $FORUM_MAIN_FORUM, $gen;
	extract($row);
	$sql = new db;
	if(USER){
		if($sql -> db_Select("forum_t", "*", "thread_forum_id='$forum_id' AND thread_datestamp > '".USERLV."' ")){
			while(list($nthread_id) = $sql -> db_Fetch()){
				if(!ereg("\.".$nthread_id."\.", USERVIEWED)){ $newflag = TRUE; }
			}
		}
	}
	$NEWFLAG = ($newflag ? "<a href='".e_SELF."?".$forum_id."'>".IMAGE_new."</a></td>" : IMAGE_nonew);
	$FORUMNAME = "<a href='".e_BASE."forum_viewforum.php?$forum_id'>$forum_name</a>";
	$FORUMDESCRIPTION = $forum_description.($restricted_string ? "<br /><span class='smalltext'><i>$restricted_string</i></span>" : "");;
	$THREADS = $forum_threads;
	$REPLIES = $forum_replies;
	if($forum_lastpost){
		$sql -> db_Select("forum_t", "*", "thread_forum_id='$forum_id' ORDER BY thread_datestamp DESC LIMIT 0,1");
		$row = $sql -> db_Fetch();
		extract($row);
		$lastpost_author_id = substr($forum_lastpost, 0, strpos($forum_lastpost, "."));
		$lastpost_author_name = substr($forum_lastpost, (strpos($forum_lastpost, ".")+1));
		$lastpost_datestamp = substr($lastpost_author_name, (strrpos($lastpost_author_name, ".")+1));
		$lastpost_author_name = str_replace(".".$lastpost_datestamp, "", $lastpost_author_name);
		$lastpost_datestamp = $gen->convert_date($lastpost_datestamp, "forum");
		$LASTPOST = $lastpost_datestamp."<br />".($lastpost_author_id ? "<a href='".e_BASE."user.php?id.$lastpost_author_id'>$lastpost_author_name</a> " : $lastpost_author_name).
		($thread_parent ?  " <a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_parent."'>".IMAGE_post."</a>" : " <a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id."'>".IMAGE_post."</a>")."
		</span></td>";
	}else{
		$LASTPOST = "-";
	}
	return(preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_MAIN_FORUM));
}


if(e_QUERY == "track"){
	$text = "<br /><div style='text-align:center'>\n<div class='spacer'>\n<table style='width:95%' class='fborder'>\n<tr>\n<td colspan='3' style='width:60%' class='fcaption'>".LAN_397."</td>\n</tr>\n";
	$tmp = explode("-", USERREALM);
	foreach($tmp as $key => $value){
		if($value){
			$sql -> db_Select("forum_t", "*", "thread_id='".$value."' ");
			$row = $sql -> db_Fetch(); extract($row);
			$icon = IMAGE_nonew_small;
			if($thread_datestamp > USERLV && (!ereg("\.".$thread_id."\.", USERVIEWED))){
				$icon = IMAGE_new_small;
			}else if($sql2 -> db_SELECT("forum_t", "*", "thread_parent='$thread_id' AND thread_datestamp > '".USERLV."' ")){
				while(list($nthread_id) = $sql2 -> db_Fetch()){
					if(!ereg("\.".$nthread_id."\.", USERVIEWED)){
						$icon = IMAGE_new_small;
					}
				}
			}
			$sql -> db_Select("forum_t", "*",  "thread_id='".$tmp[$key]."' ORDER BY thread_s DESC, thread_lastpost DESC, thread_datestamp DESC");
			$row = $sql -> db_Fetch(); extract($row);
			$result = preg_split("/\]/", $thread_name);
			$thread_name = ($result[1] ? $result[0]."] <a href='".e_BASE."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."'>".ereg_replace("\[.*\]", "", $thread_name)."</a>" : "<a href='".e_BASE."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."'>".$thread_name."</a>");
			$text .= "<tr>
			<td style='text-align:center; vertical-align:middle; width:6%'  class='forumheader3'>".$icon."</td>
			<td style='vertical-align:middle; text-align:left; width:70%'  class='forumheader3'><span class='mediumtext'>".$thread_name."</span></td>
			<td style='vertical-align:middle; text-align:center; width:24%'  class='forumheader3'><span class='mediumtext'><a href='".e_SELF."?untrack.".$thread_id."'>".LAN_392."</a></td>
			</tr>";
		}
	}
	$text .= "</table>\n</div>\n</div>";
	echo $text;
}

$forum_main_start = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_MAIN_START);
$forum_main_end = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_MAIN_END);
echo $forum_main_start.$forum_string.$forum_main_end;
require_once(FOOTERF);

?>







