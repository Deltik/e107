<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/download.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("R")){ header("location:".e_BASE."index.php"); exit; }
require_once("auth.php");
$aj = new textparse;

$e_file = str_replace("../", "", e_FILE);

if($file_array = getfiles($e_file."downloads/")){ sort($file_array); } unset($t_array);
if($image_array = getfiles($e_file."downloadimages/")){ sort($image_array); } unset($t_array);
if($thumb_array = getfiles($e_file."downloadthumbs/")){ sort($thumb_array); } unset($t_array);

$qs = explode(".", $_SERVER['QUERY_STRING']);
$action = $qs[0];
$id = $qs[1];

// dlm -------------------------------------------------------------------------------------------------------------------------------------------------------------------

if($action == "dlm"){
	$sql -> db_Select("upload", "*", "upload_id=$id");
	$row = $sql -> db_Fetch(); extract($row);
	$download_name = $upload_name ." - " . $upload_version;
	$download_url = $upload_file;
	$download_author_email = $upload_email;
	$download_author_website = $upload_website;
	$download_description = $upload_description;
	$download_image = $upload_ss;
	$download_filesize = $upload_filesize;
	$file_array[] = $download_url;
	$image_array[] = $upload_ss;
	$download_author = substr($upload_poster, (strpos($upload_poster, ".")+1));
}

// from -------------------------------------------------------------------------------------------------------------------------------------------------------------------

if($action == "from"){

	$sql -> db_Select("userfile", "*", "userfile_id='$id'");
	$row = $sql -> db_Fetch(); extract($row);

	$download_name = $userfile_filename;
	$download_url = $userfile_file;
	$download_author = $userfile_sender;
	$download_author_email = $userfile_email;
	$download_author_website = $userfile_site;
	$download_description = $userfile_description;
	$download_image = $userfile_image;

}

If(IsSet($_POST['submit'])){

	if($_POST['download_url_external']){
		$durl = $_POST['download_url_external'];
		$filesize = $_POST['download_filesize_external'];
	}else{
		$durl = $_POST['download_url'];
		$filesize = ($_POST['download_filesize_external'] ? $_POST['download_filesize_external'] : filesize($_SERVER["DOCUMENT_ROOT"].e_FILE."downloads/".$_POST['download_url']));
	}

	$_POST['download_description'] = $aj -> formtpa($_POST['download_description'], "admin");
	$sql -> db_Insert("download", "0, '".$_POST['download_name']."', '".$durl."', '".$_POST['download_author']."', '".$_POST['download_author_email']."', '".$_POST['download_author_website']."', '".$_POST['download_description']."', '".$filesize."', '0', '".$_POST['download_category']."', '".$_POST['download_active']."', '".time()."', '".$_POST['download_thumb']."', '".$_POST['download_image']."' ");
	unset($download_name, $download_url, $download_author, $download_author_email, $download_author_website, $download_description, $download_filesize, $download_type, $download_thumb, $download_image);
	$message = DOWLAN_1;
}

If(IsSet($_POST['update'])){
	if($_POST['download_url_external']){
		$durl = $_POST['download_url_external'];
		$filesize = $_POST['download_filesize_external'];
	}else{
		$durl = $_POST['download_url'];
		$filesize = filesize($_SERVER["DOCUMENT_ROOT"].e_FILE."downloads/".$_POST['download_url']);
	}
	$_POST['download_description'] = $aj -> formtpa($_POST['download_description'], "admin");
	$sql -> db_Update("download", "download_name='".$_POST['download_name']."', download_url='".$durl."', download_author='".$_POST['download_author']."', download_author_email='".$_POST['download_author_email']."', download_author_website='".$_POST['download_author_website']."', download_description='".$_POST['download_description']."', download_filesize='".$filesize."', download_category='".$_POST['download_category']."', download_active='".$_POST['download_active']."', download_datestamp='".time()."', download_thumb='".$_POST['download_thumb']."', download_image='".$_POST['download_image']."' WHERE download_id='".$_POST['download_id']."' ");
	unset($download_name, $download_url, $download_author, $download_author_email, $download_author_website, $download_description, $download_category, $download_thumb, $download_image);
	$message = DOWLAN_2;
}


If(IsSet($_POST['edit'])){
	$sql -> db_Select("download", "*", "download_id='".$_POST['existing']."' ");
	list($download_id, $download_name, $download_url, $download_author, $download_author_email, $download_author_website, $download_description, $download_filesize, $download_requested, $download_category, $download_active, $download_datestamp, $download_thumb, $download_image) = $sql-> db_Fetch();
}

if(IsSet($_POST['delete'])){
	if($_POST['confirm']){
		$sql = new db;
		$sql -> db_Delete("download", "download_id='".$_POST['existing']."' ");
		$message = DOWLAN_3;
	}else{
		$message = DOWLAN_4;
	}
}

if(IsSet($message)){
	$ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

$category_total = $sql -> db_Select("download_category", "*", "download_category_parent!='0'");
$download_total = $sql -> db_Select("download", "*", "ORDER BY download_name", "no_where");


if(!$category_total){
	$text = "
<div style='text-align:center'>".DOWLAN_5."</div>";
	$ns -> tablerender("No categories", $text);
	require_once("footer.php");
	exit;
}
	

$text = "
<div style=\"text-align:center\">
<form method=\"post\" action=\"".e_SELF."\" name=\"myform\">
<table style=\"width:85%\" class=\"fborder\">
<tr>
<td colspan=\"2\" class=\"forumheader\" style=\"text-align:center\">";

if($download_total == "0"){
	$text .= DOWLAN_6;
}else{
	
	$text .= "<span class=\"defaulttext\">".DOWLAN_7.":</span> 
	<select name=\"existing\" class=\"tbox\">";
	while(list($download_id_, $download_name_) = $sql-> db_Fetch()){
		$text .= "<option value=\"$download_id_\">".$download_name_."</option>";
	}
	$text .= "</select>
	<input class=\"button\" type=\"submit\" name=\"edit\" value=\"".DOWLAN_8."\" />
	<input class=\"button\" type=\"submit\" name=\"delete\" value=\"".DOWLAN_9."\" />
	<input type=\"checkbox\" name=\"confirm\" value=\"1\"><span class=\"smalltext\"> ".DOWLAN_10."</span>
	</td>
	</tr>";
}

$text .= "<tr>
<td style=\"width:20%\" class=\"forumheader3\">".DOWLAN_11.":</td>
<td style=\"width:80%\" class=\"forumheader3\">";

$sql -> db_Select("download_category", "*", "download_category_parent!='0'");
$text .= "<select name=\"download_category\" class=\"tbox\">";
while($row = $sql -> db_Fetch()){
	extract($row);
	if($download_category_id == $download_category){
		$text .= "<option value='$download_category_id' selected>".$download_category_name."</option>\n";
	}else{
		$text .= "<option value='$download_category_id'>".$download_category_name."</option>\n";
	}
}
$text .= "</select>


</td>
</tr>

<tr>
<td style=\"width:20%; vertical-align:top\" class=\"forumheader3\"><u>".DOWLAN_12."</u>:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<input class=\"tbox\" type=\"text\" name=\"download_name\" size=\"60\" value=\"$download_name\" maxlength=\"100\" />
</td>
</tr>

<td style=\"width:20%; vertical-align:top\" class=\"forumheader3\"><u>".DOWLAN_13."</u>:</td>
<td style=\"width:80%\" class=\"forumheader3\">
<select name=\"download_url\" class=\"tbox\">
<option></option>
";

$counter = 0;
while(IsSet($file_array[$counter])){
	if($file_array[$counter] == $download_url){
		$text .= "<option selected>".$file_array[$counter]."</option>\n";
	}else{
		$text .= "<option>".$file_array[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
<br />
<span class='smalltext'> ".DOWLAN_14.": ";


if(ereg("http", $download_url)){
	$download_url_external = $download_url;
}

$text .= "<input class=\"tbox\" type=\"text\" name=\"download_url_external\" size=\"40\" value=\"$download_url_external\" maxlength=\"100\" />
&nbsp;&nbsp;filesize: 
<input class=\"tbox\" type=\"text\" name=\"download_filesize_external\" size=\"8\" value=\"$download_filesize\" maxlength=\"10\" />
</span>

</td>
</tr>

<tr>
<td style=\"width:20%\" class=\"forumheader3\">".DOWLAN_15.":</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='download_author' size='60' value='$download_author' maxlength='100' />
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".DOWLAN_16.":</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='download_author_email' size='60' value='$download_author_email' maxlength='100' />
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".DOWLAN_17.":</td>
<td style='width:80%' class='forumheader3'>
<input class='tbox' type='text' name='download_author_website' size='60' value='$download_author_website' maxlength='100' />
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'><u>".DOWLAN_18."</u>: </td>
<td style='width:80%' class='forumheader3'>
<textarea class='tbox' name='download_description' cols='70' rows='5'>$download_description</textarea>
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".DOWLAN_19.":</td>
<td style='width:80%' class='forumheader3'>
<select name='download_image' class='tbox'>
<option></option>
";
$counter = 0;
while(IsSet($image_array[$counter])){
	if($image_array[$counter] == $download_image){
		$text .= "<option selected>".$image_array[$counter]."</option>\n";
	}else{
		$text .= "<option>".$image_array[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".DOWLAN_20.":</td>
<td style='width:80%' class='forumheader3'>
<select name='download_thumb' class='tbox'>
<option></option>
";
$counter = 0;
while(IsSet($thumb_array[$counter])){
	if($thumb_array[$counter] == $download_thumb){
		$text .= "<option selected>".$thumb_array[$counter]."</option>\n";
	}else{
		$text .= "<option>".$thumb_array[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
</td>
</tr>

<tr>
<td style='width:20%' class='forumheader3'>".DOWLAN_21.":</td>
<td style='width:80%' class='forumheader3'>";


if($download_active == "0"){
	$text .= DOWLAN_22.": <input type='radio' name='download_active' value='1'>
	".DOWLAN_23.": <input type='radio' name='download_active' value='0' checked>";
}else{
	$text .= DOWLAN_22.": <input type='radio' name='download_active' value='1' checked>
	".DOWLAN_23.": <input type='radio' name='download_active' value='0'>";
}

$text .= "</td>
</tr>
<tr style='vertical-align:top'>
<td colspan='2' style='text-align:center' class='forumheader'>";


If(IsSet($_POST['edit'])){
	$text .= "<input class='button' type='submit' name='update' value='".DOWLAN_24."' />
	<input type='hidden' name='download_id' value='$download_id'>";
}else{
	$text .= "<input class='button' type='submit' name='submit' value='".DOWLAN_25."' />";
}

$text .= "</td>
</tr>
</table>
</form>
</div>";


$ns -> tablerender("<div style='text-align:center'>".DOWLAN_26."</div>", $text);

require_once("footer.php");

function getfiles($dir){
	global $t_array;
	$pathdir = e_BASE.$dir;
	$dh = opendir($pathdir);
	$size = 0;
	while($file = readdir($dh)){
		if($file != "." and $file != ".." and $file != "index.html"){
			if(is_file($pathdir.$file)){
				$t_array[] = ereg_replace("../|e107_files/|downloads/|downloadimages/|downloadthumbs/", "", $pathdir.$file);
			}else{
				getfiles($pathdir.$file."/");
			}
		}
	}
	closedir($dh);
	return $t_array;
}

?>