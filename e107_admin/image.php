<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/image.php
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
if(!getperms("5")){ header("location:".e_BASE."index.php"); exit; }
require_once("auth.php");
require_once(e_HANDLER."form_handler.php");
require_once(e_HANDLER."userclass_class.php");
$rs = new form;

if(IsSet($_POST['update_options'])){

	$pref['image_post'] = $_POST['image_post'];
	$pref['resize_method'] = $_POST['resize_method'];
	$pref['im_path'] = $_POST['im_path'];
	$pref['image_post_class'] = $_POST['image_post_class'];
	$pref['image_post_disabled_method'] = $_POST['image_post_disabled_method'];
	save_prefs();
	$message = IMALAN_9;
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='width:85%' class='fborder'>

<tr>
<td style='width:75%' class='forumheader3'>
".IMALAN_1."<br />
<span class='smalltext'>".IMALAN_2."</span>
</td>
<td style='width:25%' class='forumheader3' style='text-align:center'>".
($pref['image_post'] ? "<input type='checkbox' name='image_post' value='1' checked>" : "<input type='checkbox' name='image_post' value='1'>")."
</td>
</tr>


<tr>
<td style='width:75%' class='forumheader3'>
".IMALAN_10."<br />
<span class='smalltext'>".IMALAN_11."</span>
</td>
<td style='width:25%' class='forumheader3' style='text-align:center'>


<select class='tbox' name='image_post_class'>
<option value='".e_UC_PUBLIC."'".($pref['image_post_class'] == e_UC_PUBLIC ? " selected" : "").">Everyone (public)</option>
<option value='".e_UC_MEMBER."'".($pref['image_post_class'] == e_UC_MEMBER ? " selected" : "").">Members only</option>
<option value='".e_UC_ADMIN."'".($pref['image_post_class'] == e_UC_ADMIN ? " selected" : "").">Admin only</option>\n";


if($sql -> db_Select("userclass_classes")){
	while($row = $sql -> db_Fetch()){
		extract($row);
		$text .= "<option value='".$userclass_id."'".($pref['image_post_class'] == $userclass_id ? " selected" : "").">$userclass_name</option>\n";
	}
}
$text .= "</select>

</td>
</tr>




<tr>
<td style='width:75%' class='forumheader3'>
".IMALAN_12."<br />
<span class='smalltext'>".IMALAN_13."</span>
</td>
<td style='width:25%' class='forumheader3' style='text-align:center'>
<select name='image_post_disabled_method' class='tbox'>".
($pref['image_post_disabled_method'] == "0" ? "<option value='1' selected>".IMALAN_14."</option>" : "<option value='0'>".IMALAN_14."</option>").
($pref['image_post_disabled_method'] == "1" ? "<option value='1' selected>".IMALAN_15."</option>" : "<option value='1'>".IMALAN_15."</option>")."
</td>
</tr>












<tr>
<td style='width:75%' class='forumheader3'>".IMALAN_3."<br /><span class='smalltext'>".IMALAN_4."</span></td>
<td style='width:25%' class='forumheader3'  style='text-align:center'>
<select name='resize_method' class='tbox'>".
($pref['resize_method'] == "gd1" ? "<option selected>gd1</option>" : "<option>gd1</option>").
($pref['resize_method'] == "gd2" ? "<option selected>gd2</option>" : "<option>gd2</option>").
($pref['resize_method'] == "ImageMagick" ? "<option selected>ImageMagick</option>" : "<option>ImageMagick</option>")."
</select>
</td>
</tr>

<tr>
<td style='width:75%' class='forumheader3'>".IMALAN_5."<br /><span class='smalltext'>".IMALAN_6."</span></td>
<td style='width:25%' class='forumheader3'  style='text-align:center'>
<input class='tbox' type='text' name='im_path' size='40' value='".$pref['im_path']."' maxlength='200' />
</tr>

<tr> 
<td colspan='2' style='text-align:center' class='forumheader'>
<input class='button' type='submit' name='update_options' value='".IMALAN_8."' />
</td>
</tr>

</table></form></div>";
$ns -> tablerender("<div style='text-align:center'>".IMALAN_7."</div>", $text);


require_once("footer.php");



$pref['resize_method'] = $_POST['resize_method'];
	$pref['im_path'] = $_POST['im_path'];


?>