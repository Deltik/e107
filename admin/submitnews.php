<?php
/*
+---------------------------------------------------------------+
|	e107 website system													|
|	/admin/submitnews.php												|
|																						|
|	�Steve Dunstan 2001-2002										|
|	http://jalist.com															|
|	stevedunstan@jalist.com											|
|																						|
|	Released under the terms and conditions of the		|
|	GNU General Public License (http://gnu.org).				|
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(ADMINPERMS != 0 && ADMINPERMS != 1 && ADMINPERMS != 2){
	header("location:../index.php");
}

if(IsSet($_POST['transfer'])){ 
	$sql -> db_Update("submitnews", "submitnews_auth='1' WHERE submitnews_id ='".$_POST['id']."' ");
	header("location:newspost.php?sn.".$_POST['id']); 
}

require_once("auth.php");

if(IsSet($_POST['confirm'])){
	$sql -> db_Delete("submitnews", "submitnews_id ='".$_POST['id']."' ");
	$message = "Submitted news item deleted.";
}

if($_POST['delete']){
	$text = "<div style=\"text-align:center\">
	<b>Please confirm you wish to delete this submitted news post - once deleted it cannot be retrieved</b>
<br /><br />
<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
<input class=\"button\" type=\"submit\" name=\"cancel\" value=\"Cancel\" /> 
<input class=\"button\" type=\"submit\" name=\"confirm\" value=\"Confirm Delete\" /> 
<input type=\"hidden\" name=\"id\" value=\"".$_POST['id']."\">
</form>
</div>";
$ns -> tablerender("Confirm Delete Submitted News Item", $text);
	
	require_once("footer.php");
	exit;
}
if(IsSet($_POST['cancel'])){
	$message = "Delete cancelled.";
}

if(IsSet($_POST['unauth_sn'])){
	$sql -> db_Update("submitnews", "submitnews_auth='1' WHERE submitnews_id ='".$_POST['id']."' ");
}
if(IsSet($_POST['reeval'])){
	$sql -> db_Update("submitnews", "submitnews_auth='0' WHERE submitnews_id ='".$_POST['id']."' ");
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

if(!$sql -> db_Select("submitnews", "*", "submitnews_auth ='0' ORDER BY submitnews_datestamp DESC")){
	$text = "<div style=\"text-align:center\"><b>There are no new user submitted news items.</b></div>
	<br />";
}else{
	$submit_total = $sql -> db_Rows();
	if($submit_total == 1){
		$text = "<div style=\"text-align:center\"><b>There is 1 submitted news item to review.</b></div>";
	}else{
		$text = "<div style=\"text-align:center\"><b>There are ".$submit_total." submitted news items to review.</b></div>";
	}
	$text .= "<br />
	<br />";
	while(list($submitnews_id, $submitnews_name, $submitnews_email, $submitnews_title, $submitnews_item, $submitnews_datestamp, $submitnews_ip, $submitnews_auth) = $sql-> db_Fetch()){
		$obj = new convert;
		$datestamp = $obj->convert_date($submitnews_datestamp, "long");
		if($submitnews_ip == ""){ $submitnews_ip = "Unknown"; }
		$text .= "Submitted by <b>".$submitnews_name. "</b>
		<br />
		[email address: ".$submitnews_email." (ip: ".$submitnews_ip.")]
		<br />
		[on ".$datestamp."]
		<br />
		<br />
		<span class=\"mediumtext\">
		Subject: <i>".$submitnews_title."</i><br />
		Item:
		<i>".$submitnews_item."</i>
		</span>
		<br />
		<br />";
		$text .= "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
		<input type=\"hidden\" name=\"news_body\" value=\"$submitnews_item\">
		<input type=\"hidden\" name=\"news_source\" value=\"Submitted by $submitnews_name [$submitnews_email]\">
		<input type=\"hidden\" name=\"id\" value=\"$submitnews_id\">
		<input class=\"button\" type=\"submit\" name=\"transfer\" value=\"Transfer item to newspost\" />
		<input class=\"button\" type=\"submit\" name=\"unauth_sn\" value=\"Mark item as unwanted\" />
		</form>
		<br />
		<br />";
	}
}	

$sql -> db_Select("submitnews", "*", "submitnews_auth ='1' ");
$sub_total = $sql -> db_Rows();

if($sub_total == "0"){
	$text .= "<div style=\"text-align:center\"><b>No older submitted news items.</b></div>
	<br />";
}else{
	$text .= "<div style=\"text-align:center\">
	<b>Older submitted news items ...</b>
	</div>
	<br />
	<br />";
	while(list($submitnews_id, $submitnews_name, $submitnews_email, $submitnews_item, $submitnews_datestamp, $submitnews_ip, $submitnews_auth) = $sql-> db_Fetch()){
		$obj = new convert;
		$datestamp = $obj->convert_date($submitnews_datestamp, "short");
		$item = substr($submitnews_item, 0, 75)." ...";
		if($submitnews_ip == ""){ $submitnews_ip = "Unknown"; }
		$text .= "<form method=\"post\" action=\"$PHP_SELF?id=$submitnews_id\">
		Submitted by <b>".$submitnews_name. "</b>
		<br />
		[email address: ".$submitnews_email." (ip: ".$submitnews_ip.")]
		<br />
		[on ".$datestamp."]
		<br />
		<span class=\"mediumtext\">
		Item:
		<i>".$item."</i>
		</span>
		<br />
		<br />
		<form method=\"post\" action=\"submitnews.php\">
		<input type=\"hidden\" name=\"news_body\" value=\"$submitnews_item\">
		<input type=\"hidden\" name=\"news_source\" value=\"Submitted by $submitnews_name [$submitnews_email]\">
		<input type=\"hidden\" name=\"id\" value=\"$submitnews_id\">
		<input class=\"button\" type=\"submit\" name=\"reeval\" value=\"Re-evaluate\" />
		<input class=\"button\" type=\"submit\" name=\"delete\" value=\"Permanently delete\" />
		</form>
		<br />
		<br />";
	}
}

$ns -> tablerender("<div style=\"text-align:center\">Review user submitted news items</div>", $text);

require_once("footer.php");
?>