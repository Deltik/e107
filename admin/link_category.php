<?php
/*
+---------------------------------------------------------------+
|	e107 website system													|
|	/admin/link_category.php											|
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
if(!getperms("8")){ header("location:../index.php"); }
require_once("auth.php");

if(IsSet($_POST['add_category'])){
	$sql -> db_Insert("link_category", "0, '".$_POST['category_name']."', '".$_POST['category_description']."' ");
	$message = "Category added to database.";
	unset($category_name, $category_description);
}

if(IsSet($_POST['update_category'])){
	$sql -> db_Update("link_category", "link_category_name='".$_POST['category_name']."', link_category_description='".$_POST['category_description']."' WHERE link_category_id='".$_POST['category_id']."' ");
	$message = "Category updated in database.";
	unset($category_name, $category_description);
}

if(IsSet($_POST['confirm'])){
	$sql -> db_Delete("link_category", "link_category_id='".$_POST['existing']."' ");
	$message = "Category deleted.";
}

if(IsSet($_POST['delete'])){
	$sql -> db_Select("link_category", "*", "link_category_id='".$_POST['existing']."' ");
	list($category_id, $category_name, $category_icon) = $sql-> db_Fetch();
	
	$text = "<div style=\"text-align:center\">
	<b>Please confirm you wish to delete the '".$category_name."' link category - once deleted it cannot be retrieved</b>
<br /><br />
<form method=\"post\" action=\"$PHP_SELF\">
<input class=\"button\" type=\"submit\" name=\"cancel\" value=\"Cancel\" /> 
<input class=\"button\" type=\"submit\" name=\"confirm\" value=\"Confirm Delete\" /> 
<input type=\"hidden\" name=\"existing\" value=\"".$_POST['existing']."\">
</form>
</div>";
$ns -> tablerender("Confirm Delete Category", $text);
	
	require_once("footer.php");
	exit;
}
if(IsSet($_POST['cancel'])){
	$message = "Delete cancelled.";
}

if(IsSet($_POST['edit'])){
	
	$sql -> db_Select("link_category", "*", "link_category_id='".$_POST['existing']."' ");
	list($category_id, $category_name, $category_description) = $sql-> db_Fetch();
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

$sql -> db_Select("link_category");
$category_total = $sql -> db_Rows();

if($category_total == "0"){
	$text = "No link categories set yet.
	<br />
	<div style=\"text-align:center\">";
}else{
	$text = "<div style=\"text-align:center\">
	<form method=\"post\" action=\"$PHP_SELF\">
	
	Existing categories: 
	<select name=\"existing\" class=\"tbox\">";
	while(list($cat_id, $cat_name, $cat_description) = $sql-> db_Fetch()){
		$text .= "<option value=\"$cat_id\">".$cat_name."</option>";
	}
	$text .= "</select> 
	<input class=\"button\" type=\"submit\" name=\"edit\" value=\"Edit\" /> 
	<input class=\"button\" type=\"submit\" name=\"delete\" value=\"Delete\" />
	</form>
	</div>
	<br />";
}


$text .= "
<form method=\"post\" action=\"$PHP_SELF\">
<table style=\"width:95%\">
<tr>
<td style=\"width:30%\">Link Category Name: </td>
<td style=\"width:70%\">
<input class=\"tbox\" type=\"text\" name=\"category_name\" size=\"60\" value=\"$category_name\" maxlength=\"100\" />
</td>
</tr>
<tr>
<td style=\"width:30%\">Link Category Description: </td>
<td style=\"width:70%\">
<input class=\"tbox\" type=\"text\" name=\"category_description\" size=\"60\" value=\"$category_description\" maxlength=\"250\" />
</td>
</tr>
<tr style=\"vertical-align:top\"> 
<td colspan=\"2\"  style=\"text-align:center\">";

if(IsSet($_POST['edit'])){

	$text .= "<input class=\"button\" type=\"submit\" name=\"update_category\" value=\"Update link category\" />
<input type=\"hidden\" name=\"category_id\" value=\"$category_id\">";
}else{
	$text .= "<input class=\"button\" type=\"submit\" name=\"add_category\" value=\"Add link category\" />";
}
$text .= "</td>
</tr>
</table>
</form>";

$ns -> tablerender("<div style=\"text-align:center\">Link Categories</div>", $text);

require_once("footer.php");
?>	