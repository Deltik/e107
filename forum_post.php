<?php
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
require_once(e_BASE."classes/ren_help.php");
$captionlinkcolour = "#fff";
$gen = new convert;
$aj = new textparse();
$fp = new floodprotect;

if(!e_QUERY){
	header("Location:".e_HTTP."forum.php");
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

if(IsSet($_POST['preview'])){
	if(USER ? $poster = USERNAME : $poster = $_POST['anonname']);
	$postdate = $gen->convert_date(time(), "forum");
	$_POST['subject'] = htmlentities($_POST['subject']);
	$_POST['post'] = htmlentities($_POST['post']);

	$tsubject = $aj -> tpa($_POST['subject'], $mode="off");
	$tpost = $aj -> tpa($_POST['post'], $mode="off");
	echo "<table style='width:100%' class='fborder'>
	<tr>
	<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_323;
	echo ($action != "nt" ? "</td>" : " ( ".LAN_62.$tsubject." )</td>");
	echo "<tr>
	<td class='forumheader3' style='width:20%' style='vertical-align:top'><b>".$poster."</b></td>
	<td class='forumheader3' style='width:80%'>
	<div class='smallblacktext' style='text-align:right'><img src='themes/shared/forum/post.png' alt='' /> ".LAN_322.$postdate."</div>".$tpost."</td>
	</tr>
	</table>";
	$anonname = stripslashes($_POST['anonname']);
	$subject = stripslashes($_POST['subject']);
	$post = stripslashes($_POST['post']);

	if($action == "edit"){
		if($_POST['subject'] ? $action = "nt" : $action = "reply");
		$eaction = TRUE;
	}else if($action == "quote"){
		$action = "reply";
		$eaction = FALSE;
	}
}

if(IsSet($_POST['newthread'])){
	if(!$_POST['subject'] || !$_POST['post']){
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	}else{
		if($fp -> flood("forum_t", "thread_datestamp") == FALSE){
			header("location: ".e_HTTP."index.php");
			die();
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

		$post = $aj -> tp($_POST['post']);
		$subject = $aj -> tp($_POST['subject']);
		
		$lastpost = $user.".".time();
		$sql -> db_Insert("forum_t", "0, '".$subject."', '".$post."', '$forum_id', '".time()."', '0', '$user', 0, 1, '".time()."', 0 ");
		$sql -> db_Update("forum", "forum_threads=forum_threads+1, forum_lastpost='$lastpost' WHERE forum_id='$forum_id' ");
		$sql -> db_Update("user", "user_forums=user_forums+1 WHERE user_id='".USERID."' ");

		$sql -> db_Select("forum_t", "*", "thread_thread='$post' ");
		$row = $sql-> db_Fetch(); extract($row);

		require_once(HEADERF);
		echo "<table style='width:100%' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".LAN_133."</td>
		</tr><tr>
		<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'><img src='".e_HTTP."themes/shared/forum/e.png' alt='' />&nbsp;</td>
		<td style='vertical-align:center; width:80%' class='forumheader2'>
		<br />".LAN_324."<br />

		<span class='defaulttext'><a href='".e_HTTP."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."'>".LAN_325."</a><br />
		<a href='".e_HTTP."forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
		</td></tr></table>";
		require_once(FOOTERF);
		exit;
	}
}

if(IsSet($_POST['reply'])){
	if(!$_POST['post']){
		$error = "<div style='text-align:center'>".LAN_28."</div>";
	}else{
		if($fp -> flood("forum_t", "thread_datestamp") == FALSE){
			header("location: ".e_HTTP."index.php");
			die();
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
		$post = $aj -> tp($_POST['post']);
		$lastpost = $user.".".time();

		$sql -> db_Select("forum_t", "*", "thread_id='$thread_id' ");
		$row = $sql-> db_Fetch(); extract($row);
		if($thread_parent){ $thread_id = $thread_parent; }


		$sql -> db_Insert("forum_t", "0, '', '".$post."', '$forum_id', '".time()."', '".$thread_id."', '$user', 0, 1, '".time()."', 0 ");
		$sql -> db_Update("forum_t",  "thread_lastpost='".time()."' WHERE thread_id='$thread_id' ");
		$sql -> db_Update("forum", "forum_replies=forum_replies+1, forum_lastpost='$lastpost' WHERE forum_id='$forum_id' ");
		$sql -> db_Update("user", "user_forums=user_forums+1 WHERE user_id='".USERID."' ");


		require_once(HEADERF);
		echo "<table style='width:100%' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".LAN_133."</td>
		</tr><tr>
		<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'><img src='".e_HTTP."themes/shared/forum/e.png' alt='' />&nbsp;</td>
		<td style='vertical-align:center; width:80%' class='forumheader2'>
		<br />".LAN_324."<br />

		<span class='defaulttext'><a href='".e_HTTP."forum_viewtopic.php?".$thread_forum_id.".".$thread_id."'>".LAN_325."</a><br />
		<a href='".e_HTTP."forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
		</td></tr></table>";
		require_once(FOOTERF);
		exit;
	}
}

if(IsSet($_POST['update_thread'])){
	if($_POST['subject'] == "" || $_POST['post'] == ""){
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	}else{
		$subject = $aj -> tp($_POST['subject']);
		$post = $aj -> tp($_POST['post']);
		$datestamp = $gen->convert_date(time(), "forum");
		$post .= "\n<span class='smallblacktext'>[ ".LAN_29." ".$datestamp." ]</span>";
		$sql -> db_Update("forum_t", "thread_name='".$subject."', thread_thread='".$post."' WHERE thread_id='$thread_id' ");
		header("location: forum_viewtopic.php?".$forum_id.".".$thread_id);
	}
}

if(IsSet($_POST['update_reply'])){
	if($_POST['post'] == ""){
		$error = "<div style='text-align:center'>".LAN_28."</div>";
	}else{
		$post = $aj -> tp($_POST['post']);
		$datestamp = $gen->convert_date(time(), "forum");
		$post .= "\n<span class='smallblacktext'>[ ".LAN_29." ".$datestamp." ]</span>";
		$sql -> db_Update("forum_t", "thread_thread='".$post."' WHERE thread_id='$thread_id' ");
		header("location: forum_viewtopic.php?".$forum_id.".".$_POST['thread_id']);
	}
}

if($action == "edit" || $action == "quote"){
	$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
	$row = $sql-> db_Fetch(); extract($row);
	$sql -> db_Select("forum_t", "*", "thread_id='".$thread_id."' ");
	$row = $sql-> db_Fetch(); extract($row);
	$subject = $thread_name;
	$post = eregi_replace("\n<span class='smallblacktext'>\[.*", "", $thread_thread);
	$post = $aj -> editparse($post);
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
	$ns -> tablerender("Error!", $text);
	require_once(FOOTERF);
	exit;
}

$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
$row = $sql-> db_Fetch(); extract($row);

if($thread_id){
	$sql -> db_Select("forum_t", "*", "thread_id='".$thread_id."' ");
	$row = $sql-> db_Fetch(); extract($row);
}

echo "
<form method='post' action='".e_SELF."?".$_SERVER['QUERY_STRING']."' name='postforum'>
<table style='width:100%' class='fborder'>
<tr><td colspan='3' class='fcaption'><a style='color:$captionlinkcolour' href='".e_HTTP."forum.php'>Forums</a> -> <a style='color:$captionlinkcolour' href='".e_HTTP."forum.php?forum.".$forum_id."'>".$forum_name."</a> -> ";

if($action == "nt"){
	echo ($eaction ? LAN_77 : LAN_60);
}else{
	echo ($eaction ? LAN_78 : "Re: ".$thread_name);
}


echo "</td></tr>";

if(ANON == TRUE  && USER == FALSE){
	echo "<tr>
<td class='forumheader2' style='width:10%'>".LAN_61."</td>
<td class='forumheader2' colspan='2'>
<input class='tbox' type='text' name='anonname' size='71' value='".$anonname."' maxlength='100' />
</td>
</tr>";
}

if($action == "nt"){
	echo "<tr>
<td class='forumheader2' style='width:15%'>".LAN_62."</td>
<td class='forumheader2' colspan='2'>
<input class='tbox' type='text' name='subject' size='71' value='".$subject."' maxlength='100' />
</td>
</tr>";
}

echo "<tr> 
<td class='forumheader2' style='width:15%'>";
$text .= ($action == "nt" ? LAN_63 : LAN_73);

$text .= "</td>
<td class='forumheader2' style='width:85%'>
<textarea class='tbox' name='post' cols='70' rows='10' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$post."</textarea>
<br />
<input class='helpbox' type='text' name='helpb' size='90' />
<br />
".ren_help("addtext")."
<br />";
require_once(e_BASE."classes/emote.php");
$text .= r_emote();
$text .= "</td>

</tr>
<tr style='vertical-align:top'> 

<td colspan='3' class='forumheader' style='text-align:center'>
<input class='button' type='submit' name='preview' value='".LAN_323."' /> ";

if($action != "nt"){
		$text .= ($eaction ? "<input class='button' type='submit' name='update_reply' value='".LAN_78."' />" : "<input class='button' type='submit' name='reply' value='".LAN_74."' />");
}else{
	$text .= ($eaction ? "<input class='button' type='submit' name='update_thread' value='".LAN_77."' />" : "<input class='button' type='submit' name='newthread' value='".LAN_64."' />");
}
$text .= "</td>
</tr>
</table>
<input type='hidden' name='thread_id' value='$thread_parent'>
</form>";


echo $text;

$text = "<table style='width:95%'>
<tr>
<td style='width:50%'>";
$text .= forumjump();
$text .= "</td></tr></table>";
echo $text;

if($action == "rp"){
	// review
	$sql -> db_Select("forum_t", "*", "thread_id = '$thread_id' ");
	$row = $sql-> db_Fetch(); extract($row);
	$post_author_name = substr($thread_user, (strpos($thread_user, ".")+1));
	
	$thread_datestamp  = $gen->convert_date($thread_datestamp , "forum");
	$thread_name = $aj -> tpa($thread_name, $mode="off");
	$thread_thread = $aj -> tpa($thread_thread, $mode="off");
	echo "<table style='width:100%' class='fborder'>
	<tr>
	<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_327;
	echo ($action != "nt" ? "</td>" : " ( ".LAN_62.$thread_name." )</td>");
	echo "<tr>
	<td class='forumheader3' style='width:20%' style='vertical-align:top'><b>".$post_author_name."</b></td>
	<td class='forumheader3' style='width:80%'>
	<div class='smallblacktext' style='text-align:right'><img src='themes/shared/forum/post.png' alt='' /> ".LAN_322.$thread_datestamp."</div>".$thread_thread."</td>
	</tr>
	</table>";
}


function getuser($name){
	$sql = new db;
	$ip = getip();
	if(USER == TRUE){
		$name = USERID.".".USERNAME;
		$sql -> db_Update("user", "user_chats=user_chats+1, user_lastpost='".time()."' WHERE user_id='".USERID."' ");
	}else if($name == ""){
		// anonymous guest
		$name = "0.".LAN_311;
	}else{
		$sql = new db;
		if($sql -> db_Select("user", "*", "user_name='$name' ")){
			$ip = getip();
			if($sql -> db_Select("user", "*", "user_name='$name' AND user_ip='$ip' ")){
				// unlogged-in member found
				list($cuser_id, $cuser_name) = $sql-> db_Fetch();
				$name = $cuser_id.".".$cuser_name;
			}else{
				// guest used regged name
				return FALSE;
			}
		}else{
			// guest entered unregged nick (own nick)
			$name = "0.".$name;
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
<input class='button' type='submit' name='userlogin' value='Login' />\n
<br />
<input type='checkbox' name='autologin' value='1' /> Auto Login
<br /><br />
[ <a href='signup.php'>".LAN_174."</a> ]<br />[ <a href='fpw.php'>".LAN_212."</a> ]
</p>
</form>
</div>";
$ns = new table;
$ns -> tablerender(LAN_175, $text);
}
function forumjump(){
	$sql = new db;
	$sql -> db_Select("forum", "*", "forum_parent !=0 AND forum_active='1'");
	$c=0;
	$text .= "<form method='post' action='".e_SELF."'><p>Jump: <select name='forumjump' class='tbox'>";
	while($row = $sql -> db_Fetch()){
		extract($row);
		if(!$forum_class || check_class($forum_class)){
			$text .= "\n<option>".$forum_name."</option>";
		}
	}
	$text .= "</select> <input class='button' type='submit' name='fjsubmit' value='Go' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>Back to top</a></p></form>";
	return $text;
}
?>
<script type="text/javascript">

function addtext(text) {
	text = ' ' + text + ' ';
	if (document.postforum.post.createTextRange && document.postforum.post.caretPos) {
		var caretPos = document.postforum.post.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		document.postforum.post.focus();
	} else {
	document.postforum.post.value  += text;
	document.postforum.post.focus();
	}
}

function storeCaret (textEl) {
	if (textEl.createTextRange) 
	textEl.caretPos = document.selection.createRange().duplicate();
}


function help(help){
	document.postforum.helpb.value = help;
}
</script>
<?php
require_once(FOOTERF);

?>