<?php
if(IsSet($_POST['fjsubmit'])){
	header("location:forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
/*
+---------------------------------------------------------------+
|	e107 website system
|	/forum_viewforum.php
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

if(!e_QUERY){
	header("Location:".e_BASE."forum.php");
	exit;
}else{
	$tmp = explode(".", e_QUERY);
	$forum_id = $tmp[0]; $from = $tmp[1];
	if(!$from){ $from = 0; }
}
define("FTHEME", (file_exists(THEME."forum/newthread.png") ? THEME."forum/" : e_IMAGE."forum/"));
$view=15;
	
$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
$row = $sql-> db_Fetch(); extract($row);

if($forum_class == 255 || $forum_class && !check_class($forum_class) || !$forum_parent){ header("Location:".e_BASE."forum.php"); exit;}

if(preg_match("/".preg_quote(ADMINNAME)."/", $forum_moderators) && getperms("A")){
	define("MODERATOR", TRUE);
}else{
	define("MODERATOR", FALSE);
}

require_once(HEADERF);

$topics = $sql -> db_Count("forum_t", "(*)", " WHERE thread_forum_id='".$forum_id."' AND thread_parent='0' ");

if($topics > $view){
	$a = $topics/$view;
	$r = explode(".", $a);
	if($r[1] != 0 ? $pages = ($r[0]+1) : $pages = $r[0]);
}else{
	$pages = FALSE;
}

$text = "<div style='text-align:center'><table style='width:95%' class='fborder'>
<tr>
<td  colspan='2' class='fcaption'>
<a class='forumlink' href='index.php'>".SITENAME."</a> >> <a class='forumlink' href='forum.php'>".LAN_01."</a> >> <b>".$forum_name."</b>
</td>
</tr>
<tr>
<td style='width:80%; vertical-align:bottom'>".LAN_321.$forum_moderators;
	
if($pages){
	$nppage= "<br />".LAN_316;
	if($pages > 10){
		$current = ($from/$view)+1;
		for($c=0; $c<=2; $c++){
			$nppage .= ($view*$c == $from ? "<u>".($c+1)."</u> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
		if($current >=3 && $current <= 5){
			for($c=3; $c<=$current; $c++){
				$nppage .= ($view*$c == $from ? "<u>".($c+1)."</u> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
			}
		}else if($current >= 6){
			$text .= " ... ";
			for($c=($current-2); $c<=$current; $c++){
				$nppage .= ($view*$c == $from ? "<u>".($c+1)."</u> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
			}
		}
		$text .= " ... ";
		$tmp = $pages-3;
		for($c=$tmp; $c<=($pages-1); $c++){
			$nppage .= ($view*$c == $from ? "<u>".($c+1)."</u> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
	}else{
		for($c=0; $c < $pages; $c++){
			if($view*$c == $from ? $nppage .= "<u>".($c+1)."</u> " : $nppage .= "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
	}
	$text .= "<br />";
}
	
$text .= $nppage."</td>
<td style='width:20%; text-align:right'>";

if(ANON || USER && ($forum_class != e_UC_READONLY || ADMIN)){
	$text .= "<a href='".e_BASE."forum_post.php?nt.".$forum_id."'><img src='".FTHEME."newthread.png' alt='' style='border:0' /></a>";
}
$text .= "</td></tr><tr>
<td colspan='2'>";

//echo $text;

$text .= "
<table style='width:100%' class='fborder'>
<tr>
<td style='width:3%' class='fcaption'>&nbsp;</td>
<td style='width:47%' class='fcaption'>".LAN_53."</td>
<td style='width:20%; text-align:center' class='fcaption'>".LAN_54."</td>
<td style='width:5%; text-align:center' class='fcaption'>".LAN_55."</td>
<td style='width:5%; text-align:center' class='fcaption'>".LAN_56."</td>
<td style='width:20%; text-align:center' class='fcaption'>".LAN_57."</td>
</tr>
";

if(!$topics){
	$text .= "<td colspan='6' style='text-align:center' class='forumheader2'>".LAN_58."</td>";
}else{
	$sql -> db_Select("forum_t", "*",  "thread_forum_id='".$forum_id."' AND thread_parent='0' ORDER BY thread_s DESC, thread_lastpost DESC, thread_datestamp DESC LIMIT $from, $view");
	$sql2 = new db; $sql3 = new db; $gen = new convert;
	while($row= $sql -> db_Fetch()){
		extract($row);
			
		$replies = $sql2 -> db_Count("forum_t", "(*)", " WHERE thread_parent='$thread_id' ");
		if($replies){
			$sql2 -> db_Select("forum_t", "*", "thread_parent='$thread_id' ORDER BY thread_datestamp DESC");
			list($null, $null, $null, $null, $r_datestamp, $null, $r_user) = $sql2 -> db_Fetch();
			$r_id = substr($r_user, 0, strpos($r_user, "."));
			$r_name = substr($r_user, (strpos($r_user, ".")+1));

			if(strstr($r_name, chr(1))){ $tmp = explode(chr(1), $r_name); $r_name = $tmp[0]; }

			$r_datestamp = $gen->convert_date($r_datestamp, "forum");
			if(!$r_id ? $lastreply = $r_datestamp."<br />".$r_name : $lastreply = $r_datestamp."<br /><a href='".e_BASE."user.php?id.".$r_id."'>".$r_name."</a>");

		}else{
			$replies = LAN_317;
			$lastreply = " - ";
		}

		$post_author_id = substr($thread_user, 0, strpos($thread_user, "."));
		$post_author_name = substr($thread_user, (strpos($thread_user, ".")+1));
		if(strstr($post_author_name, chr(1))){ $tmp = explode(chr(1), $post_author_name); $post_author_name = $tmp[0]; }
		$text .= "<tr>
		<td style='vertical-align:middle; text-align:center; width:3%' class='forumheader3'>";

		$newflag = FALSE;
		if(USER){
			if($thread_datestamp > USERLV && (!ereg("\.".$thread_id."\.", USERVIEWED))){
				$newflag = TRUE;
			}else if($sql3 -> db_SELECT("forum_t", "*", "thread_parent='$thread_id' AND thread_datestamp > '".USERLV."' ")){
				while(list($nthread_id) = $sql3 -> db_Fetch()){
					if(!ereg("\.".$nthread_id."\.", USERVIEWED)){
						$newflag = TRUE;
					}
				}
			}
		}

		$thread_datestamp = $gen->convert_date($thread_datestamp, "forum");
		$icon = ($newflag ? "<img src='".FTHEME."new_small.png' alt='' />" : "<img src='".FTHEME."nonew_small.png' alt='' />");
		if($replies >= $pref['forum_popular'] && $replies != "None"){
			$icon = ($newflag ? "<img src='".FTHEME."new_popular.gif' alt='' />" : "<img src='".FTHEME."nonew_popular.gif' alt='' />");
		}

		if($thread_s == 1){
			$icon = ($thread_active ? "<img src='".FTHEME."sticky.png' alt='' />" : "<img src='".FTHEME."stickyclosed.png' alt='' />");
		}else if($thread_s == 2){
			$icon = "<img src='".FTHEME."announce.png' alt='' />";
		}else if(!$thread_active){
			$icon = "<img src='".FTHEME."closed_small.png' alt='' />";
		}

		$text .= $icon;
		$thread_name = $aj -> tpa($thread_name);
		$result = preg_split("/\]/", $thread_name);
		$thread_name = ($result[1] ? $result[0]."] <a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id."'>".ereg_replace("\[.*\]", "", $thread_name)."</a>" : "<a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id."'>".$thread_name."</a>");
		$text .= "</td><td style='vertical-align:middle; text-align:left; width:47%'  class='forumheader3'><span class='mediumtext'>".$thread_name."</span>";
		$pages = ceil($replies/$pref['forum_postspage']);
		if($pages>1){
			$text .= "<br /><span class='smalltext'>[ goto page ";
			for($a=0; $a<=($pages-1); $a++){
				$text .= "-<a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id.".".($a*$pref['forum_postspage'])."'>".($a+1)."</a>";
			}
			$text .= " ]</span>";
		}

		if(MODERATOR){
			$text .= "<div style='text-align:right'>";
			if($thread_s == 1){
				$text .= "<a href='".e_ADMIN."forum_conf.php?unstick.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_unstick.png' alt='make un-sticky' style='border:0' /></a> ";
				if($thread_active){
					$text .= "<a href='".e_ADMIN."forum_conf.php?close.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_lock.png' alt='lock' style='border:0' /></a> ";
				}else{
					$text .= "<a href='".e_ADMIN."forum_conf.php?open.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_unlock.png' alt='unlock' style='border:0' /></a> ";
				}
			}else{
				$text .= "<a href='".e_ADMIN."forum_conf.php?stick.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_stick.png' alt='make sticky' style='border:0' /></a> ";
				if($thread_active){
					$text .= "<a href='".e_ADMIN."forum_conf.php?close.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_lock.png' alt='lock' style='border:0' /></a> ";
				}else{
					$text .= "<a href='".e_ADMIN."forum_conf.php?open.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_unlock.png' alt='unlock' style='border:0' /></a> ";
				}
			}
			$text .= "<a href='".e_ADMIN."forum_conf.php?move.".$forum_id.".".$thread_id."'><img src='".FTHEME."admin_move.png' alt='move' style='border:0' /></a></div>";
		}
			
		$text .= "</td>
<td style='vertical-align:top; text-align:center; width:20%' class='forumheader3'>".$thread_datestamp."<br />";
$text .= (!$post_author_id ? $post_author_name :  "<a href='".e_BASE."user.php?id.".$post_author_id."'>".$post_author_name."</a>");
$text .= "</td><td style='vertical-align:center; text-align:center; width:5%' class='forumheader3'>$replies</td>
<td style='vertical-align:center; text-align:center; width:5%' class='forumheader3'>$thread_views</td>
<td style='vertical-align:top; text-align:center; width:20%' class='forumheader3'>$lastreply</td>
</tr>";
	}
	$text .= "</table>";
}

$text .= "<table style='width:100%'>
<tr>
<td style='width:80%'>".$nppage;

$text .= forumjump();
$text .= "</td>
<td style='width:20%; text-align:right'>";

if(ANON || USER && ($forum_class != e_UC_READONLY || ADMIN)){
	$text .= "<a href='".e_BASE."forum_post.php?nt.".$forum_id."'><img src='".FTHEME."newthread.png' alt='' style='border:0' /></a>";
}else if($forum_class == e_UC_READONLY && !ADMIN){
	$text .= LAN_397;
}else{
	$text .= LAN_59;
}
$text .= "</td>
</tr>
</table>";

//echo $text;
$text .= "</td></tr></table><br />";

$text .= "<table class='fborder' style='width:95%'>
<tr>
<td style='vertical-align:center; width:33%' class='forumheader3'>
<table style='width:100%'>
<tr>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."new_small.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_79."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."nonew_small.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_80."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."sticky.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_202."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."announce.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_396."</td>
<td style='vertical-align:center; text-align:center; width:50%' class='smallblacktext'>";
if(USER == TRUE || ANON == TRUE){
	$text .= LAN_204." - ".LAN_206." - ".LAN_208;
}else{
	$text .= LAN_205." - ".LAN_207." - ".LAN_209;
}

$text .= "</td>
</tr>
<tr>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."new_popular.gif' alt='' /></td>
<td style='width:2%' class='smallblacktext'>".LAN_79." ".LAN_395."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."nonew_popular.gif' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_80." ".LAN_395."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."stickyclosed.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_203."</td>
<td style='vertical-align:center; text-align:center; width:2%'><img src='".FTHEME."closed_small.png' alt='' /></td>
<td style='width:10%' class='smallblacktext'>".LAN_81."</td>
<td style='vertical-align:center; text-align:center; width:50%' class='smallblacktext'>
<form method='post' action='search.php'>
<p>
<input class='tbox' type='text' name='searchquery' size='20' value='' maxlength='50' />
<input class='button' type='submit' name='searchsubmit' value='".LAN_180."' />
</p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>";
if($pref['forum_enclose']){ $ns -> tablerender($pref['forum_title'], $text); }else{ echo $text; }

require_once(FOOTERF);

function forumjump(){
	global $sql;
	$sql -> db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
	$text .= "<form method='post' action='".e_SELF."'><p>Jump: <select name='forumjump' class='tbox'>";
	while($row = $sql -> db_Fetch()){
		extract($row);
		if(($forum_class && check_class($forum_class)) || ($forum_class == 254 && USER) || !$forum_class){
			$text .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
		}
	}
	$text .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_02."</a></p></form>";
	return $text;
}

?>