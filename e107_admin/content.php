<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin//content.php
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
if(!getperms("J") && !getperms("K") && !getperms("L")){
	header("location:".e_HTTP."index.php");
	exit;
}
require_once("auth.php");
require_once(e_HANDLER."userclass_class.php");

$content_comment = TRUE; // set default to On

$aj = new textparse;

If(IsSet($_POST['submit'])){
	if($_POST['data'] != ""){
		$content_subheading = $aj -> formtpa($_POST['content_subheading'], "admin");
		$content_heading = $aj -> formtpa($_POST['content_heading'], "admin");
		$content_content = $aj -> formtpa($_POST['data'], "admin");

		 $sql -> db_Insert("content", "0, '".$content_heading."', '".$content_subheading."', '$content_content', '".$_POST['content_page']."', '".time()."', '".ADMINID."', '".$_POST['content_comment']."', '0', '1' , {$_POST['c_class']}");
		
		if($_POST['content_heading'] != ""){
			$sql -> db_Select("content", "*", "ORDER BY content_datestamp DESC LIMIT 0,1 ", $mode="no_where");
			list($content_id, $content_heading) = $sql-> db_Fetch();
			$sql -> db_Insert("links", "0, '".$content_heading."', 'article.php?".$content_id.".255', '', '', '1', '0', '0', '0', {$_POST['c_class']} ");
			$message = CNTLAN_24;
		}else{
			$sql -> db_Select("content", "*", "ORDER BY content_datestamp DESC LIMIT 0,1 ", $mode="no_where");
			list($content_id, $content_heading) = $sql-> db_Fetch();
			$message = CNTLAN_23." - 'article.php?".$content_id.".255'.";
		}
		unset($content_heading, $content_subheading, $content_content, $content_parent);
	}else{
		$message = CNTLAN_1;
	}
}

If(IsSet($_POST['update'])){
	$content_subheading = $aj -> formtpa($_POST['content_subheading'], "admin");
	$content_heading = $aj -> formtpa($_POST['content_heading'], "admin");
	$content_content = $aj -> formtpa($_POST['data'], "admin");
	$sql -> db_Update("content", " content_heading='$content_heading', content_subheading='$content_subheading', content_content='$content_content', content_page='".$_POST['content_page']."',  content_comment='".$_POST['content_comment']."', content_class='{$_POST['c_class']}' WHERE content_id='".$_POST['content_id']."' ");

	$sql -> db_Update("links", "link_class='".$_POST['c_class']."' WHERE link_name='$content_heading' ");

	unset($content_heading, $content_subheading, $content_content, $content_parent);
	$message = CNTLAN_2;
}

If(IsSet($_POST['edit'])){
	$sql -> db_Select("content", "*", "content_id='".$_POST['existing']."' ");
	$row = $sql -> db_Fetch();
	extract($row);
	$content_content = $aj -> editparse($content_content);
}

If(IsSet($_POST['delete'])){
	if($_POST['confirm']){
		$sql = new db;
		$sql -> db_Select("content", "*", "content_id='".$_POST['existing']."' ");
		$row = $sql -> db_Fetch(); extract($row);
		$sql -> db_Delete("links", "link_name='".$content_heading."' ");
		$sql -> db_Delete("content", "content_id='".$_POST['existing']."' ");
		$message = CNTLAN_20;
		unset($content_heading, $content_subheading, $content_content);
	}else{
		$message = CNTLAN_3;
	}
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$article_total = $sql -> db_Select("content", "*", "content_type='254' OR content_type='255' OR content_type='1' ");

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."' name='dataform'>
<table style='width:80%' class='fborder'>
<tr>
<td class='forumheader' style='text-align:center' colspan='2'>";

if($article_total == "0"){
	$text .= CNTLAN_4;
}else{
	$text .= "<span class='defaulttext'>".CNTLAN_5.":</span>
	<select name='existing' class='tbox'>";
	while(list($content_id_, $content_heading_) = $sql-> db_Fetch()){
		if(!$content_heading_){ $content_heading_ = "Content Page ID $content_id_"; }
		$text .= "<option value='$content_id_'>".$content_heading_."</option>";
	}
	$text .= "</select>
	<input class='button' type='submit' name='edit' value='".CNTLAN_6."' />
	<input class='button' type='submit' name='delete' value='".CNTLAN_7."' />
	<input type='checkbox' name='confirm' value='1'><span class='smalltext'> ".CNTLAN_8."</span>
	</td>
	</tr>";
}

$text .= "<tr>
<td colspan='2' style='text-align:center' class='forumheader2'>
<input class='button' type='button' onClick='openwindow()'  value='".CNTLAN_9."' />
</td>
</tr>

<tr>
<td style='width:20%; vertical-align:top' class='forumheader3'>".CNTLAN_10.":</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='content_heading' size='60' value='$content_heading' maxlength='100' />

</td>
</tr>
<tr>
<td style='width:20%' class='forumheader3'>".CNTLAN_11.":</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='content_subheading' size='60' value='$content_subheading' maxlength='100' />
</td>
</tr>
<tr>
<td style='width:20%' class='forumheader3'><u>".CNTLAN_12."</u>: </td>
<td style='width:80%' class='forumheader3'>
<textarea class='tbox' name='data' cols='70' rows='30'>$content_content</textarea>
<br />
<input class='helpbox' type='text' name='helpb' size='100' />
<br />";
require_once(e_HANDLER."ren_help.php");
$text .= ren_help("addtext", TRUE)."
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".CNTLAN_21."?:</td>
<td style='width:80%' class='forumheader3'>";

if($content_page){
	$text .= CNTLAN_14.": <input type='radio' name='content_page' value='0'>
	".CNTLAN_15.": <input type='radio' name='content_page' value='1' checked>";
}else{
	$text .= CNTLAN_14.": <input type='radio' name='content_page' value='0' checked>
	".CNTLAN_15.": <input type='radio' name='content_page' value='1'>";
}
$text .= "<span class='smalltext'>".CNTLAN_22."</span>
</td></tr>
<tr>
<td style='width:20%' class='forumheader3'>".CNTLAN_13."?:</td>
<td style='width:80%' class='forumheader3'>";


if(!$content_comment){
	$text .= CNTLAN_14.": <input type='radio' name='content_comment' value='1'>
	".CNTLAN_15.": <input type='radio' name='content_comment' value='0' checked>";
}else{
	$text .= CNTLAN_14.": <input type='radio' name='content_comment' value='1' checked>
	".CNTLAN_15.": <input type='radio' name='content_comment' value='0'>";
}


$text .= "
</td></tr>
";

$text.="
<tr>
<td style='width:20%' class='forumheader3'>".CNTLAN_19.":</td>
<td style='width:80%' class='forumheader3'>".r_userclass("c_class",$content_class)."
</td>
</tr>
<tr style='vertical-align:top'>
<td colspan='2'  style='text-align:center' class='forumheader'>";


If(IsSet($_POST['edit'])){
	$text .= "<input class='button' type='submit' name='update' value='".CNTLAN_16."' />
	<input type='hidden' name='content_id' value='$content_id'>";
}else{
	$text .= "<input class='button' type='submit' name='submit' value='".CNTLAN_17."' />";
}

$text .= "</td>
</tr>
</table>
</form>
</div>";


$ns -> tablerender("<div style='text-align:center'>".CNTLAN_18."</div>", $text);

?>
<script type="text/javascript">
function addtext(sc){
	document.dataform.data.value += sc;
}
function help(help){
	document.dataform.helpb.value = help;
}
</script>
<?php
require_once("footer.php");
?>