<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/article.php
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
if(!getperms("J") && !getperms("K") && !getperms("L")){header("location:".e_HTTP."index.php"); exit; }
require_once("auth.php");

//echo "-> "$content_id."<br /> -> ".$_POST['content_id'];.


If(IsSet($_POST['submit'])){
	$message = submit_article();
}

if(IsSet($_POST['preview'])){
	preview_article();
	If(IsSet($_POST['edit'])){
		$edit = TRUE;
		unset($_POST['edit']);
	}
}

If(IsSet($_POST['update'])){
	$message = update_article();
}

If(IsSet($_POST['edit'])){
	article_edit();
}

If(IsSet($_POST['delete'])){
	$message = article_delete();
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

$article_total = $sql -> db_Select("content", "*", "content_type='0' ");

$text = "<div style=\"text-align:center\">
<form method=\"post\" action=\"".e_SELF."\" name=\"dataform\">
<table style=\"width:80%\" class=\"fborder\">
<tr>
<td style=\"text-align:center\" colspan=\"2\" class=\"forumheader\">";

if($article_total == "0"){
	$text .= "No articles yet.";
}else{
	$text .= "
	<span class=\"defaulttext\">Existing Articles:</span>
	<select name=\"existing\" class=\"tbox\">";
	while($row = $sql-> db_Fetch()){
		extract($row);
		$text .= "<option value=\"".$content_id."\">".$content_heading."</option>";
	}
	$text .= "</select>
	<input class=\"button\" type=\"submit\" name=\"edit\" value=\"Edit\" /> 
	<input class=\"button\" type=\"submit\" name=\"delete\" value=\"Delete\" />
	<input type=\"checkbox\" name=\"confirm\" value=\"1\"><span class=\"smalltext\"> tick to confirm</span>
	</td></tr>";
}


$text .= "
<tr>
<td colspan=\"2\" style=\"text-align:center\" class=\"forumheader2\">
<input class=\"button\" type=\"button\" onClick=\"openwindow()\"  value=\"Open HTML Editor\" />
</td>
</tr>


<tr>
<td style=\"width:20%; vertical-align:top\" class=\"forumheader3\"><u>Heading</u>:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<input class=\"tbox\" type=\"text\" name=\"content_heading\" size=\"60\" value=\"".stripslashes($_POST['content_heading'])."\" maxlength=\"100\" />

</td>
</tr>
<tr>
<td style=\"width:20%\" class=\"forumheader3\">Sub-Heading:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<input class=\"tbox\" type=\"text\" name=\"content_subheading\" size=\"60\" value=\"".stripslashes($_POST['content_subheading'])."\" maxlength=\"100\" />
</td>
</tr>

<tr>
<td style=\"width:20%\" class=\"forumheader3\">Summary:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<textarea class=\"tbox\" name=\"content_summary\" cols=\"70\" rows=\"5\">".stripslashes($_POST['content_summary'])."</textarea>
</td>
</tr>

<tr>
<td style=\"width:20%\" class=\"forumheader3\"><u>Article</u>: </td>
<td style=\"width:80%\" class=\"forumheader3\">
<textarea class=\"tbox\" name=\"data\" cols=\"70\" rows=\"30\">".stripslashes($_POST['data'])."</textarea>
<br />";

require_once("../classes/shortcuts.php");
$text .= shortcuts("article");

$text .= "</td>
</tr>

<tr>
<td colspan=\"2\" class=\"forumheader3\">Allow comments?:&nbsp;&nbsp;";
if(!$_POST['content_comment']){
	$text .= "On: <input type=\"radio\" name=\"content_comment\" value=\"1\">
	Off: <input type=\"radio\" name=\"content_comment\" value=\"0\" checked>";
}else{
	$text .= "On: <input type=\"radio\" name=\"content_comment\" value=\"1\" checked>
	Off: <input type=\"radio\" name=\"content_comment\" value=\"0\">";
}

$text .= "</td>
</tr>
<tr>
<td colspan=\"2\" class=\"forumheader3\">Add email/print icons?:&nbsp;&nbsp;
";
if(!eregi("EMAILPRINT", $_POST['data']) || !$_POST['add_icons']){
	$text .= "Yes: <input type=\"radio\" name=\"add_icons\" value=\"1\">
	No: <input type=\"radio\" name=\"add_icons\" value=\"0\" checked>";
}else{
	$text .= "Yes: <input type=\"radio\" name=\"add_icons\" value=\"1\" checked>
	No: <input type=\"radio\" name=\"add_icons\" value=\"0\">";
}

$text .= "</td></tr>
<tr style=\"vertical-align:top\">
<td colspan=\"2\"  style=\"text-align:center\" class=\"forumheader\">";


If(IsSet($_POST['preview'])){
	$text .= "<input class=\"button\" type=\"submit\" name=\"preview\" value=\"Preview Again\" /> ";
}else{
	$text .= "<input class=\"button\" type=\"submit\" name=\"preview\" value=\"Preview\" /> ";
}
If(IsSet($_POST['edit']) || $edit == TRUE){
	$text .= "<input class=\"button\" type=\"submit\" name=\"update\" value=\"Update Article\" />
	<input type=\"hidden\" name=\"edit\" value=\"".$_POST['edit']."\">";
	$text .= "<input type=\"hidden\" name=\"content_id\" value=\"".$_POST['content_id']."\">";

}else{
	$text .= "<input class=\"button\" type=\"submit\" name=\"submit\" value=\"Submit Article\" />";
}

$text .= "</td>
</tr>
<tr>
<td colspan=\"2\" class=\"forumheader2\" style=\"text-align:right\">
<div class=\"smalltext\">
Tags allowed: all. <u>Underlined</u> fields are required. Use [newpage] to seperate multi-page articles. Use [preserve][/preserve] to show HTML code as entered.
</div>
</td>
</tr>
</table>
</form>
</div>";


$ns -> tablerender("<div style=\"text-align:center\">Articles</div>", $text);

?>
<script type="text/javascript">
function addtext(sc){
	document.dataform.data.value += sc;
}
</script>
<?php

require_once("footer.php");

function submit_article(){	
	if($_POST['data']){
		$sql = new db;
		article_pre_cleanup();
		if($_POST['add_icons']){
			if(!eregi("EMAILPRINT", $_POST['data'])){
				echo "<b>DEBUG</b> 766<br />";
				$_POST['data'] .= "{EMAILPRINT}";
			}
		}
		$sql -> db_Insert("content", "0, '".$_POST['content_heading']."', '".$_POST['content_subheading']."', '".$_POST['data']."', '0', '".time()."', '".ADMINID."', '".$_POST['content_comment']."', '".$_POST['content_summary']."', '0' ");
		unset($_POST['content_heading'], $_POST['content_subheading'], $_POST['data'], $_POST['content_comment'], $_POST['content_summary']);
		return "Article entered into database.";		
	}else{
		return "Fields left blank.";
	}
}
function preview_article(){
	$obj = new convert;
	$ns = new table;
	$article = article_post_cleanup();
	$datestamp = $obj->convert_date(time(), "long");
	$text = "<i>by ".ADMINNAME."</i><br /><span class=\"smalltext\">".$datestamp."</span><br /><br />Subheading: ".$article['content_subheading']."<br />Summary: ".$article['content_summary']."<br /><br />".$article['data'];
	$ns -> tablerender($article['content_heading'], $text);
	echo "<br /><br />";
}
function update_article(){
	if($_POST['content_heading'] && $_POST['data']){
		$sql = new db;
		article_pre_cleanup();
		if($_POST['add_icons']){
			if(!eregi("EMAILPRINT", $_POST['data'])){
				$_POST['data'] .= "{EMAILPRINT}";
			}
		}
		if(!$content_id){ $content_id = $_POST['content_id']; }
		$sql -> db_Update("content", " content_heading='".$_POST['content_heading']."', content_subheading='".$_POST['content_subheading']."', content_content='".$_POST['data']."', content_comment='".$_POST['content_comment']."', content_type='".$_POST['content_type']."', content_summary='".$_POST['content_summary']."' WHERE content_id='".$_POST['content_id']."' ");
		unset($_POST['content_heading'], $_POST['content_subheading'], $_POST['data'], $_POST['content_comment'], $_POST['content_summary'], $_POST['edit']);
		return "Article updated in database.";
	}else{
		return "Fields left blank.";
	}
}
function article_pre_cleanup(){
	$aj = new textparse;
	$_POST['content_heading'] = $aj -> tp($_POST['content_heading'], $mode="on");
	$_POST['content_subheading'] = $aj -> tp($_POST['content_subheading'], $mode="on");
	$_POST['data'] = $aj -> tp($_POST['data'], $mode="on");
	$_POST['content_summary'] = $aj -> tp($_POST['content_summary'], $mode="on");
	$_POST['data'] = article_preserve_html($_POST['data']);
	
}
function article_post_cleanup(){
	$aj = new textparse;
	$article['content_heading'] = $aj -> tpa($_POST['content_heading'], $mode="off");
	$article['content_subheading'] = $aj -> tpa($_POST['content_subheading'], $mode="off");
	$article['data'] = $aj -> tpa($_POST['data'], $mode="on");
	$article['content_summary'] = $aj -> tpa($_POST['content_summary'], $mode="off");
	$article['data'] = article_preserve_html($article['data']);
	$article['data'] = nl2br($article['data']);
	return $article;
}
function article_delete(){
	if($_POST['confirm']){
		$sql = new db;
		$sql -> db_Delete("content", "content_id='".$_POST['existing']."' ");
		return "Article deleted.";
	}else{
		return "Please tick the confirm box to delete the article";
	}
}
function article_edit(){
	$aj = new textparse;
	$sql = new db;
	$sql -> db_Select("content", "*", "content_id='".$_POST['existing']."' ");
	list($_POST['content_id'], $_POST['content_heading'], $_POST['content_subheading'], $_POST['data'], $content_page, $content_datestamp, $content_author, $_POST['content_comment'], $_POST['content_summary'], $content_type) = $sql-> db_Fetch();
	$_POST['content_heading'] = $aj -> editparse($_POST['content_heading']);
	$_POST['content_subheading'] = $aj -> editparse($_POST['content_subheading']);
	$_POST['data'] = $aj -> editparse($_POST['data']);
	$_POST['content_summary'] = $aj -> editparse($_POST['content_summary']);
	if($_POST['data'] = str_replace("{EMAILPRINT}", "", $_POST['data'])){ $_POST['add_icons'] = 1; }

}
function article_preserve_html($string){
	$search = array("#<#", "#>#"); 
	$replace = array("&lt;", "&gt;");
	$match_count = preg_match_all("#\[preserve\](.*?)\[/preserve\]#si", $string, $result); 
	for ($a = 0; $a < $match_count; $a++) { 
		$before_replace = $result[1][$a]; 
		$after_replace = $result[1][$a]; 
		$after_replace = preg_replace($search, $replace, $after_replace); 
		$str_to_match = "[preserve]" . $before_replace . "[/preserve]"; 
		$replacement = $code_start_html; 
		$replacement .= $after_replace; 
		$replacement .= $code_end_html; 
		$string= str_replace($str_to_match, $replacement, $string);
	}
	return $string;
}
	

?>