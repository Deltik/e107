<?php
if(IsSet($_POST['fjsubmit'])){
	header("location:forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
/*
+---------------------------------------------------------------+
|	e107 website system
|	/forum_post.php
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
require_once(HEADERF);
require_once(e_HANDLER."ren_help.php");
require_once(e_HANDLER."mail.php");
$gen = new convert;
$aj = new textparse();
$fp = new floodprotect;

if(!e_QUERY){
	header("Location:".e_BASE."forum.php");
	exit;
}else{
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0]; $forum_id = $tmp[1]; $thread_id = $tmp[2];
}

$ip = getip();
if($sql -> db_Select("tmp", "*",  "tmp_ip='$ip' ")){
	$row = $sql -> db_Fetch();
	$tmp = explode("^", $row['tmp_info']);
	$action = $tmp[0];
	$anonname = $tmp[1];
	$subject = $tmp[2];
	$post = $tmp[3];
	$sql -> db_Delete("tmp", "tmp_ip='$ip' ");
}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

if(IsSet($_POST['addoption']) && $_POST['option_count'] < 10){
	$_POST['option_count']++;
	$anonname = $aj -> formtpa($_POST['anonname']);
	$subject = $aj -> formtpa($_POST['subject']);
	$post = $aj -> formtpa($_POST['post']);
}

if(IsSet($_POST['submitpoll'])){
	require_once(e_HANDLER."poll_class.php");
	$poll = new poll;
	$poll -> submit_poll(0, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], $forum_id, "forum");

	require_once(HEADERF);
	echo "<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' colspan='2'>".LAN_133."</td>
	</tr><tr>
	<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'><img src='".e_IMAGE."forum/e.png' alt='' />&nbsp;</td>
	<td style='vertical-align:center; width:80%' class='forumheader2'>
	<br />".LAN_384."<br />

	<span class='defaulttext'><a class='forumlink' href='".e_BASE."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."'>".LAN_325."</a><br />
	<a class='forumlink' href='".e_BASE."forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
	</td></tr></table>";
	require_once(FOOTERF);
	exit;
}


if(IsSet($_POST['fpreview'])){
	if(USER ? $poster = USERNAME : $poster = $_POST['anonname']);
	$postdate = $gen->convert_date(time(), "forum");

	$tsubject = $aj -> tpa($_POST['subject']);
	$tpost = $aj -> tpa($_POST['post']);

	if($_POST['poll_title'] != ""){
		require_once(e_HANDLER."poll_class.php");
		$poll = new poll;
		$poll -> render_poll($_POST['existing'], $_POST['poll_title'], $_POST['poll_option'], array($votes), "preview", "forum");
		$count=0;
		while($_POST['poll_option'][$count]){
			$_POST['poll_option'][$count] = $aj -> formtpa($_POST['poll_option'][$count]);
			$count++;
		}
		$_POST['poll_title'] = $aj -> formtpa($_POST['poll_title']);
	}

	$text = "<div style='text-align:center'>
	<table style='width:95%' class='fborder'>
	<tr>
	<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_323;
	$text .= ($action != "nt" ? "</td>" : " ( ".LAN_62.$tsubject." )</td>");
	$text .= "<tr>
	<td class='forumheader3' style='width:20%' style='vertical-align:top'><b>".$poster."</b></td>
	<td class='forumheader3' style='width:80%'>
	<div class='smallblacktext' style='text-align:right'><img src='".e_IMAGE."forum/post.png' alt='' /> ".LAN_322.$postdate."</div>".$tpost."</td>
	</tr>
	</table>
	</div>";

	$ns -> tablerender(LAN_323, $text);


	$anonname = $aj -> formtpa($_POST['anonname'], "public");
	$subject = $aj -> formtpa($_POST['subject'], "public");
	$post = $aj -> formtpa($_POST['post'], "public");

	if($action == "edit"){
		if($_POST['subject'] ? $action = "nt" : $action = "reply");
		$eaction = TRUE;
	}else if($action == "quote"){
		$action = "reply";
		$eaction = FALSE;
	}
}

if(IsSet($_POST['newthread'])){

	if(trim(chop($_POST['subject'])) == "" || trim(chop($_POST['post'])) == ""){
		message_handler("ALERT", 5);
	}else{
		if($fp -> flood("forum_t", "thread_datestamp") == FALSE){
			header("location: ".e_BASE."index.php");
			exit;
		}
		if($sql -> db_Select("forum_t", "*", "thread_thread='".$_POST['post']."' ")){
			$ns -> tablerender(LAN_20, "<div style='text-align:center'>".LAN_389."</div>");
			require_once(FOOTERF);
			exit;
		}
		if(USER){
			$user = USERID.".".USERNAME;
		}else{
			if(!$user = getuser($_POST['anonname'])){
				require_once(HEADERF);
				$ns -> tablerender(LAN_20, LAN_310);
				$ip = getip();
				$sql -> db_Delete("tmp", "tmp_ip='$ip' ");
				$tmpdata = "newthread^".$_POST['anonname']."^".$_POST['subject']."^".$_POST['post'];
				$sql -> db_Insert("tmp", "'$ip', '".time()."', '$tmpdata' ");
				loginf();
				require_once(FOOTERF);
				exit;
			}
		}

		$post = $aj -> formtpa($_POST['post'], "public");
		$subject = $aj -> formtpa($_POST['subject'], "public");

		$email_notify = ($_POST['email_notify'] ? 99 : 1);
		if(strstr($user, chr(1))){
			$tmp = explode(chr(1), $user);
			$lastpost = $tmp[0].".".time();
		}else{
			$lastpost = $user.".".time();
		}

		if($_POST['poll_title'] != "" && $_POST['poll_option'][0] != "" && $_POST['poll_option'][1] != ""){
			$subject = "[poll] ".$subject;
		}

		if($_POST['threadtype'] == 2){
			$subject = "[announcement] ".$subject;
		}else if($_POST['threadtype'] == 1){
			$subject = "[sticky] ".$subject;
		}

		$iid = $sql -> db_Insert("forum_t", "0, '".$subject."', '".$post."', '$forum_id', '".time()."', '0', '$user', 0, $email_notify, '".time()."', '".$_POST['threadtype']."' ");
		$sql -> db_Update("forum", "forum_threads=forum_threads+1, forum_lastpost='$lastpost' WHERE forum_id='$forum_id' ");
		$sql -> db_Update("user", "user_forums=user_forums+1, user_viewed='".USERVIEWED.$iid.".' WHERE user_id='".USERID."' ");

		$sql -> db_Select("forum_t", "*", "thread_thread='$post' ");
		$row = $sql -> db_Fetch(); extract($row);
		
		if($_POST['poll_title'] != "" && $_POST['poll_option'][0] != "" && $_POST['poll_option'][1] != ""){
			require_once(e_HANDLER."poll_class.php");
			$poll = new poll;
			$poll -> submit_poll(0, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], $thread_id, "forum");
		}

		require_once(HEADERF);
		echo "<table style='width:100%' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".LAN_133."</td>
		</tr><tr>
		<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'><img src='".e_IMAGE."forum/e.png' alt='' />&nbsp;</td>
		<td style='vertical-align:center; width:80%' class='forumheader2'>
		<br />".LAN_324."<br />

		<span class='defaulttext'><a href='".e_BASE."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."#$iid'>".LAN_325."</a><br />
		<a href='".e_BASE."forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
		</td></tr></table>";
		$sql -> db_Delete("cache", "cache_url='newforumposts'");
		require_once(FOOTERF);
		exit;
	}
}

if(IsSet($_POST['reply'])){
	if(!$_POST['post']){
		message_handler("ALERT", 5);
	}else{
		if($fp -> flood("forum_t", "thread_datestamp") == FALSE){
			header("location: ".e_BASE."index.php");
			exit;
		}
		if($sql -> db_Select("forum_t", "*", "thread_thread='".$_POST['post']."' AND thread_id='$thread_id' ")){
			$ns -> tablerender(LAN_20, "<div style='text-align:center'>".LAN_389."</div>");
			require_once(FOOTERF);
			exit;
		}
		if(USER){
			$user = USERID.".".USERNAME;
		}else{
			if(!$user = getuser($_POST['anonname'])){
				require_once(HEADERF);
				$ns -> tablerender(LAN_20, LAN_310);
				$tmpdata = "reply.".$_POST['anonname'].".".$_POST['subject'].".".$_POST['post'];
				$sql -> db_Insert("tmp", "'$ip', '".time()."', '$tmpdata' ");
				loginf();
				require_once(FOOTERF);
				exit;
			}
		}
		$post = $aj -> formtpa($_POST['post'], "public");
		$subject = $aj -> formtpa($_POST['subject'], "public");

		if(strstr($user, chr(1))){
			$tmp = explode(chr(1), $user);
			$lastpost = $tmp[0].".".time();
		}else{
			$lastpost = $user.".".time();
		}

		$sql -> db_Select("forum_t", "*", "thread_id='$thread_id' ");
		$row = $sql-> db_Fetch(); extract($row);
		if($thread_parent){
			$thread_id = $thread_parent;
		}

		$iid = $sql -> db_Insert("forum_t", "0, '', '".$post."', '$forum_id', '".time()."', '".$thread_id."', '$user', 0, 1, '".time()."', 0 ");
		$sql -> db_Update("forum_t",  "thread_lastpost='".time()."' WHERE thread_id='$thread_id' ");
		$sql -> db_Update("forum", "forum_replies=forum_replies+1, forum_lastpost='$lastpost' WHERE forum_id='$forum_id' ");
		$sql -> db_Update("user", "user_forums=user_forums+1 WHERE user_id='".USERID."' ");

		if($thread_active == 99){	
			$datestamp = $gen->convert_date(time(), "long");
			$email_name = substr($thread_user, (strpos($thread_user, ".")+1));
			$sql -> db_Select("user", "*", "user_name='$email_name' ");
			$row = $sql -> db_Fetch(); extract($row);
			$poster = ereg_replace("^[0-9]+\.", "", $user);
			$message = LAN_384.SITENAME.".\n\n".LAN_382.$gen->convert_date(time(), "long")."\n".LAN_94.": ".$poster."\n\n".LAN_385.stripslashes($_POST['post'])."\n\n".LAN_383."\n\n".SITEURL."forum_viewtopic.php?".$forum_id.".".$thread_id;
			sendemail($user_email, $pref['forum_eprefix']." '".$thread_name."', ".LAN_381.SITENAME, $message);
		}
		if($sql -> db_Select("user", "*", "user_realm REGEXP('-".$thread_id."-') ")){
			echo "Tracking match found - sending email ...";
			while($row = $sql -> db_Fetch()){
				extract($row);
				$poster = ereg_replace("^[0-9]+\.", "", $user);
				$message = LAN_385.SITENAME.".\n\n".LAN_382.$gen->convert_date(time(), "long")."\n".LAN_94.": ".$poster."\n\n".LAN_385.stripslashes($_POST['post'])."\n\n".LAN_383."\n\n".SITEURL."forum_viewtopic.php?".$forum_id.".".$thread_id;
				sendemail($user_email, $pref['forum_eprefix']." '".$thread_name."', ".LAN_381.SITENAME, $message);
			}

		}

		require_once(HEADERF);
		echo "<table style='width:100%' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".LAN_133."</td>
		</tr><tr>
		<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'><img src='".e_IMAGE."forum/e.png' alt='' />&nbsp;</td>
		<td style='vertical-align:center; width:80%' class='forumheader2'>
		<br />".LAN_324."<br />

		<span class='defaulttext'><a href='".e_BASE."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."#$iid'>".LAN_325."</a><br />
		<a href='".e_BASE."forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
		</td></tr></table>";
		$sql -> db_Delete("cache", "cache_url='newforumposts'");
		require_once(FOOTERF);
		exit;
	}
}

if(IsSet($_POST['update_thread'])){
	if(!$_POST['subject'] || !$_POST['post']){
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	}else{
		$post = $aj -> formtpa($_POST['post']."\n<span class='smallblacktext'>[ ".LAN_29." ".$datestamp." ]</span>", "public");
		$subject = $aj -> formtpa($_POST['subject'], "public");

		$datestamp = $gen->convert_date(time(), "forum");
		$sql -> db_Update("forum_t", "thread_name='".$subject."', thread_thread='".$post."' WHERE thread_id='$thread_id' ");
		$sql -> db_Delete("cache", "cache_url='newforumposts'");
		header("location: forum_viewtopic.php?".$forum_id.".".$thread_id);
		exit;
	}
}

if(IsSet($_POST['update_reply'])){

	if(!$_POST['post']){
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	}else{
		$datestamp = $gen->convert_date(time(), "forum");
		$post = $aj -> formtpa($_POST['post']."\n<span class='smallblacktext'>[ ".LAN_29." ".$datestamp." ]</span>", "public");

		$sql -> db_Update("forum_t", "thread_thread='".$post."' WHERE thread_id=".$thread_id);
		$sql -> db_Delete("cache", "cache_url='newforumposts'");
		header("location: forum_viewtopic.php?".$forum_id.".".$_POST['thread_id']);
		exit;
	}
}

if($error){	$ns -> tablerender(LAN_20, $error); }

if($action == "edit" || $action == "quote"){
	$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
	$row = $sql-> db_Fetch(); extract($row);
	$sql -> db_Select("forum_t", "*", "thread_id='".$thread_id."' ");
	$row = $sql-> db_Fetch("no_strip"); extract($row);

	$post_author_id = substr($thread_user, 0, strpos($thread_user, "."));
	if($action == "edit"){
		if($post_author_id != USERID && !ADMIN){
			$ns -> tablerender(LAN_95, "<div style='text-align:center'>".LAN_96."</div>");
			require_once(FOOTERF);
			exit;
		}
	}


	$subject = $thread_name;
	$post = $aj -> editparse($thread_thread);
	$post = ereg_replace("\[ .*\]", "", $post);
	if($action == "quote"){
		$post_author_name = substr($thread_user, (strpos($thread_user, ".")+1));
		$post = "[quote=$post_author_name]".$post."[/quote]\n";
		$eaction = FALSE;
		$action = "reply";
	}else{
		$eaction = TRUE;
		if($thread_parent ? $action = "reply" : $action = "nt");
	}
}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


if(ANON == FALSE && USER == FALSE){
	$text .= LAN_45;
	$ns -> tablerender(LAN_20, $text);
	require_once(FOOTERF);
	exit;
}

$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
$row = $sql-> db_Fetch(); extract($row);

if($thread_id){
	$sql -> db_Select("forum_t", "*", "thread_id='".$thread_id."' ");
	$row = $sql-> db_Fetch(); extract($row);
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."?".e_QUERY."' name='postforum'>
<table style='width:95%' class='fborder'>
<tr><td colspan='2' class='fcaption'><a class='forumlink' href='".e_BASE."forum.php'>Forums</a> -> <a class='forumlink' href='".e_HTTP."forum.php?forum.".$forum_id."'>".$forum_name."</a> -> ";

if($action == "nt"){
	$text .= ($eaction ? LAN_77 : LAN_60);
}else{
	$text .= ($eaction ? LAN_78 : "Re: ".$thread_name);
}

$text .= "</td></tr>";

if(ANON == TRUE  && USER == FALSE){
	$text .= "<tr>
<td class='forumheader2' style='width:20%'>".LAN_61."</td>
<td class='forumheader2' style='width:80%'>
<input class='tbox' type='text' name='anonname' size='71' value='".$anonname."' maxlength='100' />
</td>
</tr>";
}

if($action == "nt"){
	$text .= "<tr>
<td class='forumheader2' style='width:20%'>".LAN_62."</td>
<td class='forumheader2' style='width:80%'>
<input class='tbox' type='text' name='subject' size='71' value='".$subject."' maxlength='100' />
</td>
</tr>";
}

$text .= "<tr> 
<td class='forumheader2' style='width:20%'>";
$text .= ($action == "nt" ? LAN_63 : LAN_73);

$text .= "</td>
<td class='forumheader2' style='width:80%'>
<textarea class='tbox' name='post' cols='70' rows='10'>".$post."</textarea>
<br />
<input class='helpbox' type='text' name='helpb' size='90' />
<br />
".ren_help("addtext", TRUE);

$text .= "<br />";
require_once(e_HANDLER."emote.php");
$text .= r_emote();

if($pref['email_notify'] && $action == "nt"){
	$text .= "
	<span class='defaulttext'>".LAN_380."</span>".
	($_POST['email_notify'] ? "<input type='checkbox' name='email_notify' value='1' checked>" : "<input type='checkbox' name='email_notify' value='1'>");

}

if(ADMIN && getperms("5") && $action == "nt"){

	$text .= "<br />
	<span class='defaulttext'>
	post thread as  
	<input name='threadtype' type='radio' value='0'".(!$_POST['threadtype'] ? "checked" : "").">".LAN_1."
	&nbsp;
	<input name='threadtype' type='radio' value='1'".($_POST['threadtype'] == 1 ? "checked" : "").">".LAN_2."
	&nbsp;
	<input name='threadtype' type='radio' value='2'".($_POST['threadtype'] == 2 ? "checked" : "").">".LAN_3."
	</span>";
}



if($action == "nt" && $pref['forum_poll'] && !eregi("edit", e_QUERY)){
	$text .= "</td>
	</tr>
	<tr>
	<td colspan='2' class='fcaption'>".LAN_4."</td>

	</tr>
	<tr> 

	<td colspan='2' class='forumheader3'>
	<span class='smalltext'>".LAN_386."
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'><div class='normaltext'>".LAN_5."</div></td>
	<td style='width:80%'class='forumheader3'>
	<input class='tbox' type='text' name='poll_title' size='70' value=`".$_POST['poll_title']."` maxlength='200' />";

	$option_count = ($_POST['option_count'] ? $_POST['option_count'] : 1);
	$text .= "<input type='hidden' name='option_count' value='$option_count'>";

	for($count=1; $count<=$option_count; $count++){
		$var = "poll_option_".$count;
		$option = stripslashes($$var);
		$text .= "<tr>
	<td style='width:20%' class='forumheader3'>Option ".$count.":</td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='poll_option[]' size='60' value=`".$_POST['poll_option'][($count-1)]."` maxlength='200' />";
		if($option_count == $count){
			$text .= " <input class='button' type='submit' name='addoption' value='".LAN_6."' /> ";
		}
		$text .= "</td></tr>";
	}


	$text .= "<tr>
	<td style='width:20%' class='forumheader3'>".LAN_7."</td>
	<td class='forumheader3'>";
	$text .= ($_POST['activate'] == 9 ? "<input name='activate' type='radio' value='9' checked>".LAN_8."<br />" : "<input name='activate' type='radio' value='9'>".LAN_8."<br />");
	$text .= ($_POST['activate'] == 10 ? "<input name='activate' type='radio' value='10' checked>".LAN_9."<br />" : "<input name='activate' type='radio' value='10'>".LAN_9."<br />");

	$text .= "</td>
	</tr>";
}


$text .= "<tr style='vertical-align:top'> 

<td colspan='2' class='forumheader' style='text-align:center'>
<input class='button' type='submit' name='fpreview' value='".LAN_323."' /> ";

if($action != "nt"){
		$text .= ($eaction ? "<input class='button' type='submit' name='update_reply' value='".LAN_78."' />" : "<input class='button' type='submit' name='reply' value='".LAN_74."' />");
}else{
	$text .= ($eaction ? "<input class='button' type='submit' name='update_thread' value='".LAN_77."' />" : "<input class='button' type='submit' name='newthread' value='".LAN_64."' />");
}
$text .= "</td>
</tr>
<input type='hidden' name='thread_id' value='$thread_parent'>
</table>
</form>
</div>";

//echo $text;


$text .= "<table style='width:95%'>
<tr>
<td style='width:50%'>";
$text .= forumjump();
$text .= "</td></tr></table>";



//echo $text;

if($action == "rp"){
	// review
	$sql -> db_Select("forum_t", "*", "thread_id = '$thread_id' ");
	$row = $sql-> db_Fetch("no_strip"); extract($row);
	$post_author_name = substr($thread_user, (strpos($thread_user, ".")+1));
	
	$thread_datestamp  = $gen->convert_date($thread_datestamp , "forum");
	$thread_name = $aj -> tpa($thread_name, $mode="off");
	$thread_thread = $aj -> tpa($thread_thread, $mode="off");
	$thread_thread = preg_replace("/([^s]{80})/", "$1\n", $thread_thread);
	$text .= "<div style='text-align:center'>
	<table style='width:95%' class='fborder'>
	<tr>
	<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_327;
	$text .= ($action != "nt" ? "</td>" : " ( ".LAN_62.$thread_name." )</td>");
	$text .= "<tr>
	<td class='forumheader3' style='width:20%' style='vertical-align:top'><b>".$post_author_name."</b></td>
	<td class='forumheader3' style='width:80%'>
	<div class='smallblacktext' style='text-align:right'><img src='".e_IMAGE."forum/post.png' alt='' /> ".LAN_322.$thread_datestamp."</div>".$thread_thread."</td>
	</tr>
	</table>
	</div>";
}

if($pref['forum_enclose']){ $ns -> tablerender($pref['forum_title'], $text); }else{ echo $text; }

function getuser($name){
	$sql = new db;
	$ip = getip();
	if(USER){
		$name = USERID.".".USERNAME;
		$sql -> db_Update("user", "user_chats=user_chats+1, user_lastpost='".time()."' WHERE user_id='".USERID."' ");
	}else if(!$name){
		// anonymous guest
		$name = "0.".LAN_311.chr(1).$ip;
	}else{
		$sql = new db;
		if($sql -> db_Select("user", "*", "user_name='$name' ")){
			$ip = getip();
			if($sql -> db_Select("user", "*", "user_name='$name' AND user_ip='$ip' ")){
				list($cuser_id, $cuser_name) = $sql-> db_Fetch();
				$name = $cuser_id.".".$cuser_name;
			}else{
				return FALSE;
			}
		}else{
			$name = "0.".$name.chr(1).$ip;
		}
	}
	return $name;
}
function loginf(){
	$text .=  "<div style='text-align:center'>
<form method='post' action='".e_SELF."?".e_QUERY."'><p>
".LAN_16."<br />
<input class='tbox' type='text' name='username' size='15' value='' maxlength='20' />\n
<br />
".LAN_17."
<br />
<input class='tbox' type='password' name='userpass' size='15' value='' maxlength='20' />\n
<br />
<input class='button' type='submit' name='userlogin' value='".LAN_10."' />\n
<br />
<input type='checkbox' name='autologin' value='1' /> ".LAN_11."
<br /><br />
[ <a href='".e_BASE."signup.php'>".LAN_174."</a> ]<br />[ <a href='fpw.php'>".LAN_212."</a> ]
</p>
</form>
</div>";
$ns = new e107table;
$ns -> tablerender(LAN_175, $text);
}
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
	$text .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_387."' /></p></form>";
	return $text;
}
?>

<script type="text/javascript">
function addtext(str){
	document.postforum.post.value += str;
}

function help(help){
	document.postforum.helpb.value = help;
}
</script>
<?php
require_once(FOOTERF);
?>