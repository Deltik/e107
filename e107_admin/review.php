<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/review.php
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
	header("location:".e_BASE."index.php");
	exit;
}
require_once("auth.php");
$aj = new textparse;
require_once(e_HANDLER."form_handler.php");
$rs = new form;

if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

// ##### DB --------------------------------------------------------------------------------------------------------------------------------------------------------------------

if(IsSet($_POST['create_category'])){
	$_POST['category_name'] = $aj -> formtpa($_POST['category_name'], "admin");
	$_POST['category_description'] = $aj -> formtpa($_POST['category_description'], "admin");
	$sql -> db_Insert("content", " '0', '".$_POST['category_name']."', '".$_POST['category_description']."', 0, 0, ".time().", '".ADMINID."', 0, '".$_POST['category_button']."', 10, 0, 0, 0");
	$message = REVLAN_25;
	clear_cache("review");
}

if(IsSet($_POST['update_category'])){
	$_POST['category_name'] = $aj -> formtpa($_POST['category_name'], "admin");
	$_POST['category_description'] = $aj -> formtpa($_POST['category_description'], "admin");
	$sql -> db_Update("content", "content_heading='".$_POST['category_name']."', content_subheading='".$_POST['category_description']."', content_summary='".$_POST['category_button']."' WHERE content_id='".$_POST['category_id']."' ");
	$message = REVLAN_26;
	clear_cache("review");
}

if(IsSet($_POST['create_review'])){
	if($_POST['data'] != ""){
		$content_subheading = $aj -> formtpa($_POST['content_subheading'], "admin");
		$content_heading = $aj -> formtpa($_POST['content_heading'], "admin");
		$content_content = $aj -> formtpa($_POST['data'], "admin");
		$content_author = ($_POST['content_author'] || $_POST['content_author'] == REVLAN_53 ? ADMINID : $_POST['content_author']."^".$_POST['content_author_email']);
		 $sql -> db_Insert("content", "0, '".$content_heading."', '".$content_subheading."', '$content_content', '".$_POST['category']."', '".time()."', '".$content_author."', '".$_POST['content_comment']."', '".$_POST['content_summary']."', '3', ".$_POST['content_rating'].",0 ,".$_POST['r_class']);
		unset($content_heading, $content_subheading, $data, $content_summary);
		$message = REVLAN_1;
		clear_cache("review");
	}else{
		$message = REVLAN_2;
	}
	unset($action);
}

If(IsSet($_POST['update_review'])){
	if($_POST['category'] == -1){ unset($_POST['category']); }
	$content_subheading = $aj -> formtpa($_POST['content_subheading'], "admin");
	$content_heading = $aj -> formtpa($_POST['content_heading'], "admin");
	$content_content = $aj -> formtpa($_POST['data'], "admin");
	$content_author = ($_POST['content_author'] && $_POST['content_author'] != ARLAN_84 ? $_POST['content_author']."^".$_POST['content_author_email'] : ADMINID);
	$sql -> db_Update("content", " content_heading='$content_heading', content_subheading='$content_subheading', content_content='$content_content', content_parent='".$_POST['category']."', content_author='$content_author', content_comment='".$_POST['content_comment']."', content_summary='".$_POST['content_summary']."', content_class='{$_POST['r_class']}' WHERE content_id='".$_POST['content_id']."' ");
	unset($action);
	$message = REVLAN_3;
	clear_cache("review");
}

if($action == "cat" && $sub_action == "confirm"){
	if($sql -> db_Delete("content", "content_id='$id' ")){
		$message = REVLAN_27;
	}
}

if($action == "confirm"){
	if($sql -> db_Delete("content", "content_id='$sub_action' ")){
		$message = REVLAN_4;
	}
}


// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


if(IsSet($message)){
        $ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}


// ##### Categories ------------------------------------------------------------------------------------------------------------------------------------------------------------

if($action == "cat"){
	$text = "<div style='border : solid 1px #000; padding : 4px; width : auto; height : 100px; overflow : auto; '>\n";
	if($category_total = $sql -> db_Select("content", "*", "content_type='10' ")){
		$text .= "<table class='fborder' style='width:100%'>
		<tr>
		<td style='width:5%' class='forumheader2'>ID</td>
		<td style='width:75%' class='forumheader2'>".REVLAN_28."</td>
		<td style='width:20%; text-align:center' class='forumheader2'>".REVLAN_29."</td>
		</tr>";
		while($row = $sql -> db_Fetch()){
			extract($row);
			$text .= "<tr>
			<td style='width:5%; text-align:center' class='forumheader3'>".($content_summary ? "<img src='".e_IMAGE."link_icons/$content_summary' alt='' style='vertical-align:middle' />" : "&nbsp;")."</td>
			<td style='width:75%' class='forumheader3'>$content_heading [$content_subheading]</td>
			<td style='width:20%; text-align:center' class='forumheader3'>
			".$rs -> form_button("submit", "category_edit", REVLAN_30, "onClick=\"document.location='".e_SELF."?cat.edit.$content_id'\"")."
			".$rs -> form_button("submit", "category_delete", REVLAN_31, "onClick=\"confirm_('cat');\"")."
			</td>
			</tr>";
		}
		$text .= "
		</table>";
	}else{
		$text .= "<div style='text-align:center'>".REVLAN_32."</div>";
	}
	$text .= "</div>";
	$ns -> tablerender(REVLAN_33, $text);

	$handle=opendir(e_IMAGE."link_icons");
	while ($file = readdir($handle)){
		if($file != "." && $file != ".." && $file != "/"){
			$iconlist[] = $file;
		}
	}
	closedir($handle);

	unset($content_heading, $content_summary, $content_subheading);

	if($sub_action == "edit"){
		if($sql -> db_Select("content", "*", "content_id='$id' ")){
			$row = $sql -> db_Fetch(); extract($row);
		}
	}

	$text = "<div style='text-align:center'>
	".$rs -> form_open("post", e_SELF."?cat", "dataform")."
	<table class='fborder' style='width:auto'>
	<tr>
	<td class='forumheader3' style='width:30%'><span class='defaulttext'>".REVLAN_34."</span></td>
	<td class='forumheader3' style='width:70%'>".$rs -> form_text("category_name", 30, $content_heading, 25)."</td>
	</tr>
	<tr>
	<td class='forumheader3' style='width:30%'><span class='defaulttext'>".REVLAN_35."</span></td>
	<td class='forumheader3' style='width:70%'>
	".$rs -> form_text("category_button", 60, $content_summary, 100)."
	<br />
	<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".REVLAN_36."' onClick='expandit(this)'>
	<div style='display:none' style=&{head};>";
	while(list($key, $icon) = each($iconlist)){
		$text .= "<a href='javascript:addtext(\"$icon\")'><img src='".e_IMAGE."link_icons/".$icon."' style='border:0' alt='' /></a> ";
	}
	$text .= "</td>
	</tr>
	<tr>
	<td class='forumheader3' style='width:30%'><span class='defaulttext'>".REVLAN_37."</span></td>
	<td class='forumheader3' style='width:70%'>".$rs->form_textarea("category_description", 59, 3, $content_subheading)."</td>
	</tr>
	<tr><td colspan='2' style='text-align:center' class='forumheader'>";
	if($id){
		$text .= "<input class='button' type='submit' name='update_category' value='".REVLAN_38."'> 
		".$rs -> form_button("submit", "category_clear", "Clear Form").
		$rs -> form_hidden("category_id", $id)."
		</td></tr>";
	}else{
		$text .= "<input class='button' type='submit' name='create_category' value='".REVLAN_39."'></td></tr>";
	}
	$text .= "</table>
	".$rs -> form_close()."
	</div>";

	$ns -> tablerender(REVLAN_39, $text);
}

// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


// ##### Display scrolling list of existing reviews ----------------------------------------------------------------------------------------------------------------------------
if(!$action || $action == "confirm"){
	$sql2 = new db;
	$text = "<div style='border : solid 1px #000; padding : 4px; width : auto; height : 200px; overflow : auto; '>";
	if($article_total = $sql -> db_Select("content", "*", "content_type='3' ")){
		$text .= "<table class='fborder' style='width:100%'>
		<tr>
		<td style='width:5%' class='forumheader2'>&nbsp;</td>
		<td style='width:50%' class='forumheader2'>".REVLAN_15."</td>
		<td style='width:45%' class='forumheader2'>".REVLAN_29."</td>
		</tr>";
		while($row = $sql -> db_Fetch()){
			extract($row);
			unset($cs);
			if($sql2 -> db_Select("content", "content_summary", "content_id=$content_parent")){
				$row = $sql2 -> db_Fetch(); $cs = $row[0];
			}
			$text .= "<tr>
			<td style='width:5%; text-align:center' class='forumheader3'>".($cs ? "<img src='".e_IMAGE."link_icons/$cs' alt='' style='vertical-align:middle' />" : "&nbsp;")."</td>
			<td style='width:75%' class='forumheader3'><a href='".e_BASE."content.php?review.$content_id'>$content_heading</a> [".preg_replace("/-.*-/", "", $content_subheading)."]</td>
			<td style='width:20%; text-align:center' class='forumheader3'>
			".$rs -> form_button("submit", "main_edit", REVLAN_30, "onClick=\"document.location='".e_SELF."?create.edit.$content_id'\"")."
			".$rs -> form_button("submit", "main_delete", REVLAN_31, "onClick=\"confirm_('create')\"")."
			</td>
			</tr>";
		}
		$text .= "</table>";
	}else{
		$text .= "<div style='text-align:center'>".REVLAN_40."</div>";
	}
	$text .= "</div>";
	$ns -> tablerender(REVLAN_41, $text);
}

// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


// ##### Display the create review entry screen ------------------------------------------------------------------------------------------------------------------------------


if($action == "create"){
	if($sub_action == "edit"){
		if($sql -> db_Select("content", "*", "content_id='$id' ")){
			$row = $sql -> db_Fetch(); extract($row);
			$data = $content_content;
			$content_rating = $content_review_score;
			$category = $content_parent;
			if(is_numeric($content_author)){
				$content_author = "";
				$content_author_email = "";
			}else{
				$tmp = explode("^", $content_author);
				$content_author = $tmp[0];
				$content_author_email = $tmp[1];
			}
		}
	}

	require_once(e_HANDLER."userclass_class.php");
	$text = "<div style='text-align:center'>
	".$rs -> form_open("post", e_SELF."?create", "dataform")."

	<table style='width:95%' class='fborder'>
	<tr>
	<td colspan='2' style='text-align:center' class='forumheader2'>
	<input class='button' type='button' onClick='openwindow()'  value='".REVLAN_42."' />
	</td>
	</tr>

	<tr>
	<td style='width:20%; vertical-align:top' class='forumheader3'><u>".REVLAN_43."</u>:</td>
	<td style='width:80%' class='forumheader3'>";

	$sql -> db_Select("content", "*", "content_type=10 ");
	$text .= $rs->form_select_open("category");
	$text .= (!$category ? $rs -> form_option("- ".REVLAN_44." -", 1, -1) : $rs -> form_option("- ".REVLAN_44." -", 0, -1));
	while(list($category_id, $category_name) = $sql-> db_Fetch()){
		$text .= ($category_id == $category ? $rs -> form_option($category_name, 1, $category_id) : $rs -> form_option($category_name, 0, $category_id));
	}
	$text .= $rs -> form_select_close()."
	</td>
	</tr>

	<tr>
	<td style='width:20%; vertical-align:top' class='forumheader3'>".REVLAN_51.":<br /><span class='smalltext'>(".REVLAN_52.")</span></td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='content_author' size='60' value='".($content_author ? $content_author : REVLAN_53)."' maxlength='100' ".($content_author ? "" : "onFocus=\"document.dataform.content_author.value='';\"")." /><br />
	<input class='tbox' type='text' name='content_author_email' size='60' value='".($content_author_email ? $content_author_email : REVLAN_54)."' maxlength='100' ".($content_author_email ? "" : "onFocus=\"document.dataform.content_author_email.value='';\"")." /><br />
	</td>
	</tr>

	<tr>
	<td style='width:20%; vertical-align:top' class='forumheader3'><u>".REVLAN_12."</u>:</td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='content_heading' size='60' value='$content_heading' maxlength='100' />

	</td>
	</tr>
	<tr>
	<td style='width:20%' class='forumheader3'>".REVLAN_13.":</td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='content_subheading' size='60' value='$content_subheading' maxlength='200' />
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".REVLAN_14.":</td>
	<td style='width:80%' class='forumheader3'>
	<textarea class='tbox' name='content_summary' cols='70' rows='5'>$content_summary</textarea>
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'><u>".REVLAN_15."</u>: </td>
	<td style='width:80%' class='forumheader3'>
	<textarea class='tbox' name='data' cols='70' rows='30'>$data</textarea>
	<br />
	<input class='helpbox' type='text' name='helpb' size='100' />
	<br />";
	require_once(e_HANDLER."ren_help.php");
	$text .= ren_help("addtext2", TRUE)."
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".REVLAN_16.":</td>
	<td style='width:80%' class='forumheader3'>
	<select name='content_rating' class='tbox'>
	<option value='0'>".REVLAN_17." ...</option>";
	for($a=1; $a<=100; $a++){
		$text .= ($content_rating == $a ? "<option value='$a' selected>$a</option>" : "<option value='$a'>$a</option>");
	}
	$text .= "</select>
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".REVLAN_18.":</td>
	<td style='width:80%' class='forumheader3'>
	";


	if($content_comment == "0"){
		$text .= REVLAN_19.": <input type='radio' name='content_comment' value='1'>
		".REVLAN_20.": <input type='radio' name='content_comment' value='0' checked>";
	}else{
		$text .= REVLAN_19.": <input type='radio' name='content_comment' value='1' checked>
		".REVLAN_20.": <input type='radio' name='content_comment' value='0'>";
	}

	$text .= "</td></tr>";

	$text.="
	<td style='width:20%' class='forumheader3'>".REVLAN_21.":</td>
	<td style='width:80%' class='forumheader3'>".r_userclass("r_class",$content_class)."
	";

	$text.="
	<tr style='vertical-align:top'>
	<td colspan='2' style='text-align:center' class='forumheader'>
	";


	if($sub_action == "edit"){
		$text .= "<input class='button' type='submit' name='update_review' value='".REVLAN_22."' />
		<input type='hidden' name='content_id' value='$id'>";
	}else{
		$text .= "<input class='button' type='submit' name='create_review' value='".REVLAN_23."' />";
	}

	$text .= "</td>
	</tr>
	</table>
	</form>
	</div>";

	$ns -> tablerender("<div style='text-align:center'>".REVLAN_24."</div>", $text);

}


// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


// ##### Display options --------------------------------------------------------------------------------------------------------------------------------------------------------

$text = "<div style='text-align:center'>";
if(e_QUERY && $action != "confirm"){
	$text .= "<a href='".e_SELF."'><div class='border'><div class='forumheader'>".REVLAN_45."</div></div></a>";
}
if($action != "create"){
	$text .= "<a href='".e_SELF."?create'><div class='border'><div class='forumheader'>".REVLAN_46."</div></div></a>";
}
if($action != "cat"){
	$text .= "<a href='".e_SELF."?cat'><div class='border'><div class='forumheader'>".REVLAN_47."</div></div></a>";
}
$text .= "</div>";
$ns -> tablerender(REVLAN_48, $text);

// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


require_once("footer.php");
?>
<script type="text/javascript">
function addtext(sc){
	document.dataform.category_button.value = sc;
}

function addtext2(sc){
	document.dataform.data.value += sc;
}
function help(help){
	document.dataform.helpb.value = help;
}
</script>
<?php
echo "<script type=\"text/javascript\">
function confirm_(mode){
	if(mode == 'cat'){
		var x=confirm(\"".REVLAN_49."\");
	}else{
		var x=confirm(\"".REVLAN_50."\");
	}
if(x)
	if(mode == 'cat'){
		window.location='".e_SELF."?cat.confirm.$content_id';
	}else{
		window.location='".e_SELF."?confirm.$content_id';
	}
}
</script>";
?>