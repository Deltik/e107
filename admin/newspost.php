<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/newspost.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("H")){ header("location:".e_HTTP."index.php"); exit;}

require_once("auth.php");
require_once(e_BASE."classes/news_class.php");
require_once(e_BASE."classes/ren_help.php");
$aj = new textparse;

if(e_QUERY != ""){
	$qs = explode(".", e_QUERY);
	$action = $qs[0];
	$id = $qs[1];
	if($action == "ne"){
		$_POST['edit'] = TRUE;
		$_POST['existing'] = $id;
	}else if($action == "nd"){
		$_POST['delete'] = TRUE;
		$_POST['existing'] = $id;


	}else if($action == "news"){
		$sql -> db_Select("upload", "*", "upload_id=$id");
		$row = $sql -> db_Fetch(); extract($row);

		$post_author_id = substr($upload_poster, 0, strpos($upload_poster, "."));
		$post_author_name = substr($upload_poster, (strpos($upload_poster, ".")+1));
		$poster = (!$post_author_id ? "<b>".$post_author_name."</b>" : "<a href='".e_BASE."user.php?id.".$post_author_id."'><b>".$post_author_name."</b></a>");

		$news_title = $upload_filename;
		$data = $upload_type."\n".$upload_filename." by ".$poster." ...\n[blockquote]".$upload_description."[/blockquote]";




	}else{
		$sql -> db_Select("submitnews", "*", "submitnews_id ='$id' ");
		list($submitnews_id, $submitnews_name, $submitnews_email, $submitnews_title, $submitnews_item, $submitnews_datestamp, $submitnews_ip, $submitnews_auth) = $sql-> db_Fetch();
		$news_title = $submitnews_title;
		$data = $submitnews_item;
		$news_source = "Submitted by ".$submitnews_name." ( ".$submitnews_email." )";
	}
}

$ix = new news;
$news_id = $_POST['news_id'];

if(IsSet($_POST['reset'])){
	$news_id = "";
}

if(IsSet($_POST['deletecancel'])){
	$message = "Delete cancelled.<br />";
}

if(IsSet($_POST['deleteconfirm'])){
	$message = $ix -> delete_item($_POST['news_id']);
	$news_id = "";
	$rsd = new create_rss();
}

if(IsSet($_POST['edit'])){
	$row = $ix -> edit_item($_POST['existing']);
	extract($row);
	$news_title = stripslashes($news_title);
	$data = stripslashes($news_body);
	$news_extended = stripslashes($news_extended);
	$news_source = stripslashes($news_source);
	$news_url = stripslashes($news_url);
	$_POST['news_allow_comments'] = $news_allow_comments;
	$_POST['news_active'] = $news_active;
	if($news_start){$tmp = getdate($news_start);$_POST['startmonth'] = $tmp['mon'];$_POST['startday'] = $tmp['mday'];$_POST['startyear'] = $tmp['year'];}
	if($news_end){$tmp = getdate($news_end);$_POST['endmonth'] = $tmp['mon'];$_POST['endday'] = $tmp['mday'];$_POST['endyear'] = $tmp['year'];}
}

if(IsSet($_POST['submit'])){
	if(!$_POST['startmonth'] || !$_POST['startday'] || !$_POST['startyear'] ? $active_start = 0 : $active_start = mktime (0, 0, 0, $_POST['startmonth'], $_POST['startday'], $_POST['startyear']));
	if(!$_POST['endmonth'] || !$_POST['endday'] || !$_POST['endyear'] ? $active_end = 0 : $active_end = mktime (0, 0, 0, $_POST['endmonth'], $_POST['endday'], $_POST['endyear']));

	$message = $ix -> submit_item($_POST['news_id'], $_POST['news_title'], $_POST['data'], $_POST['news_extended'], $_POST['news_source'], $_POST['news_url'], $_POST['cat_id'], $_POST['news_allow_comments'], $active_start, $active_end, $always_active);
	unset($news_id, $news_title, $data, $news_extended, $news_source, $news_url, $_POST['news_allow_comments'], $active_start, $active_end, $_POST['news_active']);
	$rsd = new create_rss();
}

if(IsSet($_POST['delete'])){
	$sql -> db_Select("news", "*", "news_id='".$_POST['existing']."' ");
	$row = $sql -> db_Fetch(); extract($row);

	$text = "<div style=\"text-align:center\">
<b>'".$news_title."'</b><br /><br />
Are you absolutely certain you want to delete this news story? Once deleted it <b><u>cannot</u></b> be retreived.
<br /><br />
<form method=\"post\" action=\"".e_SELF."\">
<input class=\"button\" type=\"submit\" name=\"cancel\" value=\"Cancel\" /> 
<input class=\"button\" type=\"submit\" name=\"deleteconfirm\" value=\"Confirm Delete\" /> 
<input type=\"hidden\" name=\"news_id\" value=\"$news_id\">
</form>
</div>";
$ns -> tablerender("Confirm Delete Category", $text);
	
	require_once("footer.php");
	exit;
}

if(IsSet($_POST['preview'])){
	if(!$_POST['startmonth'] || !$_POST['startday'] || !$_POST['startyear'] ? $active_start = 0 : $active_start = mktime (0, 0, 0, $_POST['startmonth'], $_POST['startday'], $_POST['startyear']));
	if(!$_POST['endmonth'] || !$_POST['endday'] || !$_POST['endyear'] ? $active_end = 0 : $active_end = mktime (0, 0, 0, $_POST['endmonth'], $_POST['endday'], $_POST['endyear']));
	$temp = $ix -> preview($news_id, $_POST['news_title'], $_POST['data'],  $_POST['news_extended'], $_POST['news_source'], $_POST['news_url'], $_POST['cat_id'], $_POST['news_allow_comments'], $active_start, $active_end, $_POST['news_active']);
	$news_category = stripslashes($temp[0]);
	$news_title = stripslashes(str_replace("\"", "&quot;", $temp[1]));
	$data = stripslashes($temp[2]);
	$news_extended = stripslashes($temp[3]);
	$news_source = stripslashes(str_replace("\"", "&quot;", $temp[4]));
	$news_url = stripslashes(str_replace("\"", "&quot;", $temp[5]));
//	$news_title = $aj -> editparse($news_title);
//	$data = $aj -> editparse($data);
//	$news_extended = $aj -> editparse($news_extended);
//	$news_source = $aj -> editparse($news_source);
//	$news_url = $aj -> editparse($news_url);
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

$text = "<div style=\"text-align:center\">
<form method=\"post\" action=\"".e_SELF."\" name=\"dataform\">
<table style=\"width:95%\" class=\"fborder\">
<tr>
<td colspan=\"2\" style=\"text-align:center\" class=\"forumheader\">";

if(!$sql -> db_Select("news", "*", "ORDER BY news_datestamp DESC LIMIT 0,20", $mode="no_where")){
	$text .= "No news items yet.";
}else{
	$text .= "<span class=\"defaulttext\">Existing News:</span> 
	<select name=\"existing\" class=\"tbox\">";
	
	while(list($news_id_, $news_title_) = $sql-> db_Fetch()){
		$text .= "<option value=\"$news_id_\">".$news_title_."</option>";
	}
	$text .= "</select>
<input class=\"button\" type=\"submit\" name=\"edit\" value=\"Edit\" /> 
<input class=\"button\" type=\"submit\" name=\"delete\" value=\"Delete\" />
</div>
";
}

$text .= "
</td></tr>
<tr>
<td colspan=\"2\" style=\"text-align:center\" class=\"forumheader2\">
<input class=\"button\" type=\"button\" onClick=\"openwindow()\"  value=\"Open HTML Editor\" />
</td>
</tr>

<tr>
<td style=\"width:20%\" class=\"forumheader3\">Category: </td>
<td style=\"width:80%\" class=\"forumheader3\">";

if(!$sql -> db_Select("news_category")){
	$text .= "No categories set yet.";
}else{

	$text .= "
	<select name='cat_id' class='tbox'>";
	
	while(list($cat_id, $cat_name, $cat_icon) = $sql-> db_Fetch()){
		if($news_category == $cat_id){
			$text .= "<option value='$cat_id' selected>".$cat_name."</option>";
		}else{
			$text .= "<option value='$cat_id'>".$cat_name."</option>";
		}
	}
	$text .= "</select>";
}
$text .= " [ <a href='news_category.php'>Add/Edit Categories</a> ]
</td>
</tr>
<tr> 
<td style='width:20%' class='forumheader3'>Title:</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='news_title' size='80' value=\"$news_title\" maxlength='200' />
</td>
</tr>
<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Body:<br /></td>
<td style=\"width:80%\" class=\"forumheader3\">
<textarea class=\"tbox\" name=\"data\" cols=\"80\" rows=\"10\">$data</textarea>
<br />
<input class=\"helpbox\" type=\"text\" name=\"helpb\" size=\"100\" />
<br />
";
$text .= ren_help("addtext");
$text .= "</td>
</tr>
<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Extended:<br /></td>
<td style=\"width:80%\" class=\"forumheader3\">
<textarea class=\"tbox\" name=\"news_extended\" cols=\"80\" rows=\"10\">$news_extended</textarea>
<br />
";
$text .= ren_help("addtext2");
$text .= "</tr>
<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Source:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<input class=\"tbox\" type=\"text\" name=\"news_source\" size=\"80\" value=\"$news_source\" maxlength=\"100\" />
</td>
</tr>
<tr> 
<td style=\"width:20%\" class=\"forumheader3\">URL:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<input class=\"tbox\" type=\"text\" name=\"news_url\" size=\"80\" value=\"$news_url\" maxlength=\"100\" />
</td>
</tr>

<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Comments:</td>
<td style=\"width:80%\" class=\"forumheader3\">";

if(!$_POST['news_allow_comments']){
	$text .= "<input name=\"news_allow_comments\" type=\"radio\" value=\"0\" checked>Enabled&nbsp;&nbsp;<input name=\"news_allow_comments\" type=\"radio\" value=\"1\">Disabled";
}else{
	$text .= "<input name=\"news_allow_comments\" type=\"radio\" value=\"0\">Enabled&nbsp;&nbsp;<input name=\"news_allow_comments\" type=\"radio\" value=\"1\" checked>Disabled";
}

$text .= " <span class=\"smalltext\">( Allow comments to be posted to this news item )";

$text .= "</td>
</tr>

<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Status:</td>
<td style=\"width:80%\" class=\"forumheader3\">";


$text .= (!$_POST['news_active'] ? "<input name=\"news_active\" type=\"radio\" value=\"0\" checked>Enabled&nbsp;&nbsp;<input name=\"news_active\" type=\"radio\" value=\"1\">Disabled" : "<input name=\"news_active\" type=\"radio\" value=\"0\">Enabled&nbsp;&nbsp;<input name=\"news_active\" type=\"radio\" value=\"1\" checked>Disabled");

$text .= " <span class=\"smalltext\">( Enabled = visible on front page, disabled - not visible )
</td>
</tr>

<tr> 
<td style=\"width:20%\" class=\"forumheader3\">Activation:<br /><span class=\"smalltext\">(Leave blank to disable auto-activation)</span></td>
<td style=\"width:80%\" class=\"forumheader3\">";

$text .= "<br />Activate between: <select name=\"startday\" class=\"tbox\"><option selected> </option>";
for($a=1; $a<=31; $a++){
	$text .= ($a == $_POST['startday'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}
$text .= "</select> <select name=\"startmonth\" class=\"tbox\"><option selected> </option>";
for($a=1; $a<=12; $a++){
	$text .= ($a == $_POST['startmonth'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}
$text .= "</select> <select name=\"startyear\" class=\"tbox\"><option selected> </option>";
for($a=2003; $a<=2010; $a++){
	$text .= ($a == $_POST['startyear'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}
$text .= "</select> and <select name=\"endday\" class=\"tbox\"><option selected> </option>";
for($a=1; $a<=31; $a++){
	$text .= ($a == $_POST['endday'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}
$text .= "</select> <select name=\"endmonth\" class=\"tbox\"><option selected> </option>";
for($a=1; $a<=12; $a++){
	$text .= ($a == $_POST['endmonth'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}
$text .= "</select> <select name=\"endyear\" class=\"tbox\"><option selected> </option>";
for($a=2003; $a<=2010; $a++){
	$text .= ($a == $_POST['endyear'] ? "<option selected>".$a."</option>" : "<option>".$a."</option>");
}

$text .= "</select>
<tr style=\"vertical-align: top;\">
<td colspan=\"2\"  style=\"text-align=center\" class=\"forumheader\">";
	
if(IsSet($_POST['preview'])){
	$text .= "<input class=\"button\" type=\"submit\" name=\"preview\" value=\"Preview again\" /> ";
	if($news_id != ""){
		$text .= "<input class=\"button\" type=\"submit\" name=\"submit\" value=\"Update news in database\" /> ";
	}else{
		$text .= "<input class=\"button\" type=\"submit\" name=\"submit\" value=\"Post news to database\" /> ";
	}
}else{
	$text .= "<input class=\"button\" type=\"submit\" name=\"preview\" value=\"Preview\" /> ";
}
if(IsSet($id)){
	$text .= "<input class=\"button\" type=\"submit\" name=\"reset\" value=\"New story\" /> ";
}
$text .= "<input type=\"hidden\" name=\"news_id\" value=\"$news_id\">
</td>
</tr>
<tr>
<td colspan=\"2\" class=\"forumheader2\" style=\"text-align:right\">
<div class=\"smalltext\">
Line breaks (&lt;br /&gt;) are auto added. <u>Underlined fields are required.</u>
</div>
</td>
</tr>
</table>
</div>
<input type=\"hidden\" name=\"news_id\" value=\"$news_id\">
</form>";
//<a href=\"#\" onclick=\"window.open('../htmlarea/index.php?Sent to textarea','Editor', 'top=100,left=100,resizable=no,width=670,height=600,scrollbars=no,menubar=no'); return false\">Open editor</a>";
$ns -> tablerender("<div style=\"text-align:center\">News Post</div>", $text);
?>
<script type="text/javascript">


function addtext(str){
	document.dataform.data.value += str;
}

function addtext2(str){
	document.dataform.news_extended.value += str;
}

function fclear(){
	document.dataform.data.value = "";
	document.dataform.news_extended.value = "";
}
function help(help){
	document.dataform.helpb.value = help;
}
</script>
<?php

/*function storeCaret (textEl) {
	if (textEl.createTextRange) 
	textEl.caretPos = document.selection.createRange().duplicate();
}
*/

require_once("footer.php");

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

  while(list($news_id, $news_title, $data, $news_datestamp, $news_author, $news_source, $news_url, $news_catagory) = $rsd-> db_Fetch()){
  		$tmp = explode(" ", $data);
		unset($nb);
		for($a=0; $a<=100; $a++){
			$nb .= $tmp[$a]." ";
		}
  		$nb = htmlspecialchars($nb); 
		$text .= $news_title."\n".SITEURL."/comment.php?".$news_id."\n\n";
		$rss .= "<item>
<title>".$news_title."</title>
<description>".$nb."</description>
<link>http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?".$news_id."</link> 
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

