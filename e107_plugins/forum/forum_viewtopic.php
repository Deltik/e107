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
|     $Source: /cvsroot/e107/e107_0.7/e107_plugins/forum/forum_viewtopic.php,v $
|     $Revision: 1.54 $
|     $Date: 2006/01/14 14:09:51 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

require_once('../../class2.php');

@include_once e_PLUGIN.'forum/languages/'.e_LANGUAGE.'/lan_forum_viewtopic.php';
@include_once e_PLUGIN.'forum/languages/English/lan_forum_viewtopic.php';
@require_once(e_PLUGIN.'forum/forum_class.php');
global $FORUM_CRUMB;

if (file_exists(THEME.'forum_design.php'))
{
	@include_once(THEME.'forum_design.php');
}

$forum = new e107forum;
if (IsSet($_POST['fjsubmit']))
{
	header("location:".e_PLUGIN."forum/forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
$highlight_search = FALSE;
if (isset($_POST['highlight_search']))
{
	$highlight_search = TRUE;
}

if (!e_QUERY)
{
	//No paramters given, redirect to forum home
	header("Location:".e_PLUGIN."forum/forum.php");
	exit;
}
else
{
	$tmp = explode(".", e_QUERY);
	$thread_id = $tmp[0];
	$from = $tmp[1];
	$action = $tmp[2];
	if (!$from)
	{
		$from = 0;
	}
	if (!$thread_id || !is_numeric($thread_id))
	{
		header("Location:".e_PLUGIN."forum/forum.php");
		exit;
	}
}

if($from === 'post')
{
	if($thread_id)
	{
		$post_num = $forum->thread_postnum($thread_id);
		$pages = ceil(($post_num['post_num']+1)/$pref['forum_postspage']);
		$from = ($pages-1) * $pref['forum_postspage'];
		if($post_num['parent'] != $thread_id)
		{
			header("location: ".e_SELF."?{$post_num['parent']}.{$from}#post_{$thread_id}");
			exit;
		}
	}
	else
	{
		header("Location:".e_PLUGIN."forum/forum.php");
		exit;
	}
}

require_once(e_PLUGIN.'forum/forum_shortcodes.php');


if ($action == "track" && USER)
{
	$forum->track($thread_id);
	header("location:".e_SELF."?{$thread_id}.{$from}");
	exit;
}

if ($action == "untrack" && USER)
{
	$forum->untrack($thread_id);
	header("location:".e_SELF."?{$thread_id}.{$from}");
	exit;
}

if ($action == "next")
{
	$next = $forum->thread_getnext($thread_id, $from);
	if ($next)
	{
		header("location:".e_SELF."?{$next}");
		exit;
	}
	else
	{
		require_once(HEADERF);
		$ns->tablerender('', LAN_405, array('forum_viewtopic', '405'));
		require_once(FOOTERF);
		exit;
	}
}

if ($action == "prev") {
	$prev = $forum->thread_getprev($thread_id, $from);
	if ($prev) {
		header("location:".e_SELF."?{$prev}");
		exit;
	} else {
		require_once(HEADERF);
		$ns->tablerender('', LAN_404, array('forum_viewtopic', '404'));
		require_once(FOOTERF);
		exit;
	}

}

if ($action == "report") {
	$thread_info = $forum->thread_get_postinfo($thread_id, TRUE);

	if (isset($_POST['report_thread'])) {
		$report_add = $tp -> toDB($_POST['report_add']);
		if ($pref['reported_post_email']) {
			require_once(e_HANDLER."mail.php");
			$report = LAN_422.SITENAME." : ".(substr(SITEURL, -1) == "/" ? SITEURL : SITEURL."/").$PLUGINS_DIRECTORY."forum/forum_viewtopic.php?".$thread_id.".post\n".LAN_425.USERNAME."\n".$report_add;
			$subject = LAN_421." ".SITENAME;
			sendemail(SITEADMINEMAIL, $subject, $report);
		}
		$sql->db_Insert('generic', "0, 'reported_post', ".time().", '".USERID."', '{$thread_info['head']['thread_name']}', ".intval($thread_id).", '{$report_add}'");
		define("e_PAGETITLE", LAN_01." / ".LAN_428);
		require_once(HEADERF);
		$text = LAN_424."<br /><br /><a href='forum_viewtopic.php?".$thread_id.".post'>".LAN_429."</a";
		$ns->tablerender(LAN_414, $text, array('forum_viewtopic', 'report'));
	} else {
		$thread_name = $tp -> toHTML($thread_info['head']['thread_name'], TRUE);
		define("e_PAGETITLE", LAN_01." / ".LAN_426." ".$thread_name);
		require_once(HEADERF);
		$text = "<form action='".e_PLUGIN."forum/forum_viewtopic.php?".e_QUERY."' method='post'> <table style='width:100%'>
			<tr>
			<td  style='width:50%' >
			".LAN_415.": ".$thread_name." <a href='".e_PLUGIN."forum/forum_viewtopic.php?".$thread_id.".post'><span class='smalltext'>".LAN_420." </span>
			</a>
			</td>
			<td style='text-align:center;width:50%'>
			</td>
			</tr>
			<tr>
			<td>".LAN_417."<br />".LAN_418."
			</td>
			<td style='text-align:center;'>
			<textarea cols='40' rows='10' class='tbox' name='report_add'></textarea>
			</td>
			</tr>
			<tr>
			<td colspan='2' style='text-align:center;'><br />
			<input class='button' type='submit' name='report_thread' value='".LAN_419."' />
			</td>
			</tr>
			</table>";
		$ns->tablerender(LAN_414, $text, array('forum_viewtopic', 'report2'));
	}
	require_once(FOOTERF);
	exit;
}
$pm_installed = ($pref['pm_title'] ? TRUE : FALSE);

$replies = $forum->thread_count($thread_id)-1;
if ($from === 'last') {
	$pref['forum_postspage'] = ($pref['forum_postspage'] ? $pref['forum_postspage'] : 10);
	$pages = ceil(($replies+1)/$pref['forum_postspage']);
	$from = ($pages-1) * $pref['forum_postspage'];
}
$gen = new convert;
$thread_info = $forum->thread_get($thread_id, $from-1, $pref['forum_postspage']);
if($thread_info['head']['thread_forum_id'] == "")
{
	require_once(HEADERF);
	$ns->tablerender(LAN_01, FORLAN_104, array('forum_viewtopic', '104'));
	require_once(FOOTERF);
	exit;
}
$forum_info = $forum->forum_get($thread_info['head']['thread_forum_id']);


if (!check_class($forum_info['forum_class']) || !check_class($forum_info['parent_class'])) {
	header("Location:".e_PLUGIN."forum/forum.php");
	exit;
}

$forum->thread_incview($thread_id);

define("e_PAGETITLE", LAN_01." / ".$tp->toHTML($forum_info['forum_name'], TRUE, 'no_hook')." / ".$tp->toHTML($thread_info['head']['thread_name'], TRUE));
//define("MODERATOR", (preg_match("/".preg_quote(ADMINNAME)."/", $forum_info['forum_moderators']) && getperms('A') ? TRUE : FALSE));
define("MODERATOR", $forum_info['forum_moderators'] != "" && check_class($forum_info['forum_moderators']));
$modArray = $forum->forum_getmods($forum_info['forum_moderators']);

$message = '';
if (MODERATOR)
{
	if ($_POST)
	{
		require_once(e_PLUGIN.'forum/forum_mod.php');
		$message = forum_thread_moderate($_POST);
		$thread_info = $forum->thread_get($thread_id, $from-1, $pref['forum_postspage']);
	}
}

require_once(HEADERF);
require_once(e_HANDLER."level_handler.php");
if ($message)
{
	$ns->tablerender("", $message, array('forum_viewtopic', 'msg'));
}

if (isset($_POST['pollvote']))
{
	if ($_POST['votea'])
	{
		if($sql -> db_Select("polls", "*", "poll_datestamp=$thread_id"))
		{
			$row = $sql -> db_Fetch();
			extract($row);
			$votes = explode(chr(1), $poll_votes);
			if(is_array($_POST['votea']))
			{
				/* multiple choice vote */
				foreach($_POST['votea'] as $vote)
				{
					$vote = intval($vote);
					$votes[($vote-1)] ++;
				}
			}
			else
			{
				$_POST['votea'] = intval($_POST['votea']);
				$votes[($_POST['votea']-1)] ++;
			}

			$votep = implode(chr(1), $votes);

			$sql->db_Update("polls", "poll_votes = '$votep' WHERE poll_id=".intval($poll_id));
			$POLLMODE = "voted";

		}
	}
}

if (stristr($thread_info['head']['thread_name'], "[".LAN_430."]"))
{
	if ($sql->db_Select("polls", "*", "poll_datestamp='{$thread_info['head']['thread_id']}'"))
	{
		$pollArray = $sql -> db_Fetch();

		$cookiename = "poll_".$pollArray['poll_id'];
		if(isset($_COOKIE[$cookiename]))
		{
			$POLLMODE = "voted";
		}
		else
		{
			$POLLMODE = "notvoted";
		}
		if(!defined("POLLCLASS"))
		{
			require(e_PLUGIN."poll/poll_class.php");
		}
		$poll = new poll;
		$pollstr = "<div class='spacer'>".$poll->render_poll($pollArray, "forum", $POLLMODE, TRUE)."</div>";
	}
}
//Load forum templates

if (!$FORUMSTART) {
	if (file_exists(THEME."forum_viewtopic_template.php"))
	{
		require_once(THEME."forum_viewtopic_template.php");
	}
	else if (file_exists(THEME."forum_template.php"))
	{
		require_once(THEME."forum_template.php");
	}
	else
	{
		require_once(e_PLUGIN."forum/templates/forum_viewtopic_template.php");
	}
}

$forum_info['forum_name'] = $tp -> toHTML($forum_info['forum_name'], TRUE);

// get info for main thread -------------------------------------------------------------------------------------------------------------------------------------------------------------------

if(is_array($FORUM_CRUMB))
{
	$search 	= array("{SITENAME}", "{SITENAME_HREF}");
	$replace 	= array(SITENAME, "href='".e_BASE."index.php'");
	$FORUM_CRUMB['sitename']['value'] = str_replace($search, $replace, $FORUM_CRUMB['sitename']['value']);

	$search 	= array("{FORUMS_TITLE}", "{FORUMS_HREF}");
	$replace 	= array(LAN_01, "href='".e_PLUGIN."forum/forum.php'");
	$FORUM_CRUMB['forums']['value'] = str_replace($search, $replace, $FORUM_CRUMB['forums']['value']);

	if($forum_info['sub_parent'])
	{
		$search 	= array("{SUBPARENT_TITLE}", "{SUBPARENT_HREF}");
		$replace 	= array($forum_info['sub_parent'], "href='".e_PLUGIN."forum/forum_viewforum.php?{$forum_info['forum_sub']}'");
		$FORUM_CRUMB['subparent']['value'] = str_replace($search, $replace, $FORUM_CRUMB['subparent']['value']);
	}
	else
	{
		$FORUM_CRUMB['subparent']['value'] = "";
	}

	$search 	= array("{FORUM_TITLE}", "{FORUM_HREF}");
	$replace 	= array($forum_info['forum_name'],"href='".e_PLUGIN."forum/forum_viewforum.php?{$forum_info['forum_id']}'");
	$FORUM_CRUMB['forum']['value'] = str_replace($search, $replace, $FORUM_CRUMB['forum']['value']);
	$FORUM_CRUMB['fieldlist'] = "sitename,forums,subparent,forum";
	
	$BREADCRUMB = $tp->parseTemplate("{BREADCRUMB=FORUM_CRUMB}", true);

}
else
{
	$BREADCRUMB = "<a class='forumlink' href='".e_BASE."index.php'>".SITENAME."</a> -> <a class='forumlink' href='".e_PLUGIN."forum/forum.php'>".LAN_01."</a> -> ";
	if($forum_info['sub_parent'])
	{
		$BREADCRUMB .= "<a class='forumlink' href='".e_PLUGIN."forum/forum_viewforum.php?{$forum_info['forum_sub']}'>{$forum_info['sub_parent']}</a> -> ";
	}
	$BREADCRUMB .= "<a class='forumlink' href='".e_PLUGIN."forum/forum_viewforum.php?{$forum_info['forum_id']}'>".$tp->toHTML($forum_info['forum_name'], TRUE, 'no_hook')."</a></b>";
}

$BACKLINK = $BREADCRUMB;
$THREADNAME = $tp->toHTML($thread_info['head']['thread_name'], TRUE);
$NEXTPREV = "&lt;&lt; <a href='".e_SELF."?{$thread_id}.{$forum_info['forum_id']}.prev'>".LAN_389."</a>";
$NEXTPREV .= " | ";
$NEXTPREV .= "<a href='".e_SELF."?{$thread_id}.{$forum_info['forum_id']}.next'>".LAN_390."</a> &gt;&gt;";

if ($pref['forum_track'] && USER)
{
	$TRACK = (strpos(USERREALM, "-".$thread_id."-") !== FALSE ? "<span class='smalltext'><a href='".e_SELF."?".$thread_id.".0."."untrack'>".LAN_392."</a></span>" : "<span class='smalltext'><a href='".e_SELF."?".$thread_id.".0."."track'>".LAN_391."</a></span>");
}

$MODERATORS = LAN_321.implode(", ", $modArray);

$THREADSTATUS = (!$thread_info['head']['thread_active'] ? LAN_66 : "");

$pref['forum_postspage'] = ($pref['forum_postspage'] ? $pref['forum_postspage'] : 10);
$pages = ceil(($replies+1)/$pref['forum_postspage']);
if ($pages > 1)
{
	$parms = ($replies+1).",{$pref['forum_postspage']},{$from},".e_SELF.'?'.$thread_id.'.[FROM]';
	$GOTOPAGES = $tp->parseTemplate("{NEXTPREV={$parms}}");
}

if ((check_class($forum_info['forum_postclass']) && check_class($forum_info['parent_postclass'])) || MODERATOR)
{
	if ($thread_info['head']['thread_active'])
	{
		$BUTTONS = "<a href='".e_PLUGIN."forum/forum_post.php?rp.".e_QUERY."'>".IMAGE_reply."</a>";
	}
	$BUTTONS .= "<a href='".e_PLUGIN."forum/forum_post.php?nt.".$forum_info['forum_id']."'>".IMAGE_newthread."</a>";
}

$POLL = $pollstr;

$FORUMJUMP = forumjump();

$forstr = preg_replace("/\{(.*?)\}/e", '$\1', $FORUMSTART);

unset($forrep);
if (!$FORUMREPLYSTYLE) $FORUMREPLYSTYLE = $FORUMTHREADSTYLE;
$alt = FALSE;
for($i = 0; $i < count($thread_info)-1; $i++) {
	unset($post_info);
	$post_info = $thread_info[$i];
	$loop_uid = $post_info['user_id'];
	if (!$post_info['thread_user']) {
		// guest
		$tmp = explode(chr(1), $post_info['thread_anon']);
		$ip = $tmp[1];
		$host = $e107->get_host_name($ip);
		$post_info['iphost'] = "<div class='smalltext' style='text-align:right'>IP: <a href='".e_ADMIN."userinfo.php?$ip'>$ip ( $host )</a></div>";
		$post_info['anon'] = TRUE;
	} else {
		$post_info['anon'] = FALSE;
	}

	if($post_info['thread_parent'])
	{
		$alt = !$alt;
		if(isset($FORUMREPLYSTYLE_ALT) && $alt)
		{
			$forrep .= $tp->parseTemplate($FORUMREPLYSTYLE_ALT, TRUE, $forum_shortcodes)."\n";
		}
		else
		{
			$forrep .= $tp->parseTemplate($FORUMREPLYSTYLE, TRUE, $forum_shortcodes)."\n";
		}
	}
	else
	{
		$forthr = $tp->parseTemplate($FORUMTHREADSTYLE, TRUE, $forum_shortcodes)."\n";
	}
}


if (((check_class($forum_info['forum_postclass']) && check_class($forum_info['parent_postclass'])) || MODERATOR) && $thread_info['head']['thread_active'] )
{
	if (!$forum_quickreply)
	{
		$QUICKREPLY = "<form action='".e_PLUGIN."forum/forum_post.php?rp.".e_QUERY."' method='post'>\n<p>\n".LAN_393.":<br /><textarea cols='60' rows='4' class='tbox' name='post' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'></textarea><br /><input type='submit' name='fpreview' value='".LAN_394."' class='button' /> &nbsp;\n<input type='submit' name='reply' value='".LAN_395."' class='button' />\n<input type='hidden' name='thread_id' value='$thread_parent' />\n</p>\n</form>";
	}
	else
	{
		$QUICKREPLY = $forum_quickreply;
	}
}

$forend = preg_replace("/\{(.*?)\}/e", '$\1', $FORUMEND);
$forumstring = $forstr.$forthr.$forrep.$forend;


if ($thread_info['head']['thread_lastpost'] > USERLV && (strpos(USERVIEWED, ".{$thread_info['head']['thread_id']}.") === FALSE)) {
	$tst = $forum->thread_markasread($thread_info['head']['thread_id']);
}

if ($pref['forum_enclose']) {
	$ns->tablerender(LAN_01, $forumstring, array('forum_viewtopic', 'main'));
} else {
	echo $forumstring;
}


// end -------------------------------------------------------------------------------------------------------------------------------------------------------------------

echo "<script type=\"text/javascript\">
	function confirm_(mode, forum_id, thread_id, thread) {
	if (mode == 'thread') {
	return confirm(\"".$tp->toJS(LAN_409)."\");
	} else {
	return confirm(\"".$tp->toJS(LAN_410)." [ ".$tp->toJS(LAN_411)."\" + thread + \" ]\");
	}
	}
	</script>";
require_once(FOOTERF);

function showmodoptions()
{
	global $thread_id;
	global $thread_info;
	global $forum_info;
	global $post_info;
	$forum_id = $forum_info['forum_id'];
	if ($post_info['thread_parent'] == FALSE)
	{
		$type = 'thread';
		$ret = "<form method='post' action='".e_PLUGIN."forum/forum_viewforum.php?{$forum_id}' id='frmMod_{$forum_id}_{$post_info['thread_id']}'>";
	}
	else
	{
		$type = 'reply';
		$ret = "<form method='post' action='".e_SELF."?".e_QUERY."' id='frmMod_{$forum_id}_{$post_info['thread_id']}'>";
	}
	
	$ret .= "
		<div>
		<a href='".e_PLUGIN."forum/forum_post.php?edit.{$post_info['thread_id']}.{$from}'>".IMAGE_admin_edit."</a>
		<input type='image' ".IMAGE_admin_delete." name='delete_{$post_info['thread_id']}' value='thread_action' onclick=\"return confirm_('{$type}', {$forum_id}, {$thread_id}, '{$post_info['user_name']}')\" />
		";
	if ($type == 'thread')
	{
		$ret .= "<a href='".e_PLUGIN."forum/forum_conf.php?move.{$thread_id}'>".IMAGE_admin_move2."</a>";
	}
	$ret .= "
		</div>
		</form>";
	return $ret;
}

function forumjump()
{

	global $forum;
	$jumpList = $forum->forum_get_allowed();
	$text = "<form method='post' action='".e_SELF."'><p>".LAN_65.": <select name='forumjump' class='tbox'>";
	foreach($jumpList as $key => $val)
	{
		$text .= "\n<option value='".$key."'>".$val."</option>";
	}
	$text .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_10."</a></p></form>";
	return $text;
}

function rpg($user_join, $user_forums)
{
	global $FORUMTHREADSTYLE;
	if (strpos($FORUMTHREADSTYLE, '{RPG}') == FALSE)
	{
		return '';
	}
	// rpg mod by Ikari ( kilokan1@yahoo.it | http://artemanga.altervista.org )

	$lvl_post_mp_cost = 2.5;
	$lvl_mp_regen_per_day = 4;
	$lvl_avg_ppd = 5;
	$lvl_bonus_redux = 5;
	$lvl_user_days = max(1, round((time() - $user_join ) / 86400 ));
	$lvl_ppd = $user_forums / $lvl_user_days;
	if ($user_forums < 1) {
		$lvl_level = 0;
	} else {
		$lvl_level = floor(pow(log10($user_forums ), 3 ) ) + 1;
	}
	if ($lvl_level < 1) {
		$lvl_hp = "0 / 0";
		$lvl_hp_percent = 0;
	} else {
		$lvl_max_hp = floor((pow($lvl_level, (1/4) ) ) * (pow(10, pow($lvl_level+2, (1/3) ) ) ) / (1.5) );

		if ($lvl_ppd >= $lvl_avg_ppd) {
			$lvl_hp_percent = floor((.5 + (($lvl_ppd - $lvl_avg_ppd) / ($lvl_bonus_redux * 2)) ) * 100);
		} else {
			$lvl_hp_percent = floor($lvl_ppd / ($lvl_avg_ppd / 50) );
		}
		if ($lvl_hp_percent > 100) {
			$lvl_max_hp += floor(($lvl_hp_percent - 100) * pi() );
			$lvl_hp_percent = 100;
		} else {
			$lvl_hp_percent = max(0, $lvl_hp_percent);
		}
		$lvl_cur_hp = floor($lvl_max_hp * ($lvl_hp_percent / 100) );
		$lvl_cur_hp = max(0, $lvl_cur_hp);
		$lvl_cur_hp = min($lvl_max_hp, $lvl_cur_hp);
		$lvl_hp = $lvl_cur_hp . '/' . $lvl_max_hp;
	}
	if ($lvl_level < 1) {
		$lvl_mp = '0 / 0';
		$lvl_mp_percent = 0;
	} else {
		$lvl_max_mp = floor((pow($lvl_level, (1/4) ) ) * (pow(10, pow($lvl_level+2, (1/3) ) ) ) / (pi()) );
		$lvl_mp_cost = $user_forums * $lvl_post_mp_cost;
		$lvl_mp_regen = max(1, $lvl_user_days * $lvl_mp_regen_per_day);
		$lvl_cur_mp = floor($lvl_max_mp - $lvl_mp_cost + $lvl_mp_regen);
		$lvl_cur_mp = max(0, $lvl_cur_mp);
		$lvl_cur_mp = min($lvl_max_mp, $lvl_cur_mp);
		$lvl_mp = $lvl_cur_mp . '/' . $lvl_max_mp;
		$lvl_mp_percent = floor($lvl_cur_mp / $lvl_max_mp * 100 );
	}
	if ($lvl_level < 1) {
		$lvl_exp = "0 / 0";
		$lvl_exp_percent = 100;
	} else {
		$lvl_posts_for_next = floor(pow(10, pow($lvl_level, (1/3) ) ) );
		if ($lvl_level == 1) {
			$lvl_posts_for_this = max(1, floor(pow (10, (($lvl_level - 1) ) ) ) );
		} else {
			$lvl_posts_for_this = max(1, floor(pow (10, pow(($lvl_level - 1), (1/3) ) ) ) );
		}
		$lvl_exp = ($user_forums - $lvl_posts_for_this) . "/" . ($lvl_posts_for_next - $lvl_posts_for_this);
		$lvl_exp_percent = floor((($user_forums - $lvl_posts_for_this) / max(1, ($lvl_posts_for_next - $lvl_posts_for_this ) ) ) * 100);
	}
	$rpg_info .= "<div style='padding:2px;'>";
	$rpg_info .= "<b>Level = ".$lvl_level."</b><br />";
	$rpg_info .= "HP = ".$lvl_hp."<br /><img src='".THEME."images/bar.jpg' height='10' alt='' style='border:#345487 1px solid; width:".$lvl_hp_percent."'><br />";
	$rpg_info .= "EXP = ".$lvl_exp."<br /><img src='".THEME."images/bar.jpg' height='10' alt='' style='border:#345487 1px solid; width:".$lvl_exp_percent."'><br />";
	$rpg_info .= "MP = ".$lvl_mp."<br /><img src='".THEME."images/bar.jpg' height='10' alt='' style='border:#345487 1px solid; width:".$lvl_mp_percent."'><br />";
	$rpg_info .= "</div>";
	return $rpg_info;
}

?>