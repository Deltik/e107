<?
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/header.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $sitename; ?></title>
    <link rel="stylesheet" href="<?php echo "../".THEME; ?>style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="content-style-type" content="text/css" />
  </head>
<body>
<?php

$ns = new table;
echo "
<div style=\"text-align:center\">";
if($admin_logo == "1"){
	echo "<table style=\"width:100%\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td style=\"background-color:#E2E2E2; text-align:".$logo_align."\">
	\n<img src=\"logo.png\" alt=\"Logo\" />
	</td></tr>
	<tr>
	<td style=\"background-color:#000\"></td>
	</tr>
	<tr>
	<td style=\"background-color:#fff\"></td>
	</tr>
	<tr>
	<td style=\"background-color:#ccc\">&nbsp;";
	if(ADMIN == TRUE){
		echo "Logged in: ".ADMINNAME.", level '".ADMINPERMS."' administrator";
	}else{
		echo "Login required to progress to secure admin area ...";
	}
	echo "</td>
	</tr>
	<tr>
	<td style=\"background-color:#000\"></td>
	</tr>
	</table>";
}else{
	echo "<img src=\"logo".$admin_logo.".png\" alt=\"Logo\" />
	<br />";
	if(ADMIN == TRUE){
		echo "Logged in: ".ADMINNAME.", level '".ADMINPERMS."' administrator";
	}else{
		echo "Login required to progress to secure admin area ...";
	}
}

echo "<table style=\"width:100%\" cellspacing=\"10\" cellpadding=\"10\">
<tr> 
<td style=\"width:15%; vertical-align: top;\">";

// security update added by que
if(ADMIN == TRUE){
 if(!eregi("/admin.php", $PHP_SELF)){
	 $text = "<a href=\"admin.php\">Admin Front Page</a>
<br />
<a href=\"../index.php\">Leave Admin Area</a>
<br />
<br />";

if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"newspost.php\">News</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"news_category.php\">News Categories</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1){
	$text .= "<a href=\"prefs.php\">Preferences</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1){
	$text .= "<a href=\"menus.php\">Menus</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1){
	$text .= "<a href=\"administrator.php\">Administrators</a><br />";
}
$text .= "<a href=\"updateadmin.php\">Update admin settings</a><br />";
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"forum.php\">Forums</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"article.php\">Articles/Content/Reviews</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"links.php\">Links</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"link_category.php\">Link Categories</a>";
}
$text .= "<br />";

if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"wmessage.php\">Welcome Message</a><br />";
}

if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"upload.php\">Upload</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"submitnews.php\">Submitted News</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1 || ADMINPERMS == 2){
	$text .= "<a href=\"banlist.php\">Bans</a><br />";
}
if(ADMINPERMS == 0 || ADMINPERMS == 1){
	$text .= "<a href=\"users.php\">Users</a><br />";
}

	$text .= "<a href=\"admin.php?logout\">Logout</a>";
	$ns -> tablerender("Admin Navigation", $text);

 }else{
	$text = "<a href=\"../index.php\">Leave Admin Area</a>";
	$ns -> tablerender("Admin Navigation", $text);
	unset($text);
 }
if($sql -> db_Select("submitnews", "*", "submitnews_auth ='0' ")){
	$text = "<div class=\"defaulttext\" style=\"text-align:center\">
<b>You have had a news item submitted.</b>
</div>
Please click <a href=\"submitnews.php\">here</a> to review.";
	$ns -> tablerender("Story Submitted", $text);
}

if(ADMINPERMS == 0){
	$sql -> db_Select("admin", "*", "admin_permissions='0'");
	list($a_id, $a_name, $null, $a_email, $null, $a_perms, $a_pwchange) = $sql-> db_Fetch();
	if(($a_pwchange+612000) < time()){
		$text = "<div style=\"mediumtext; text-align:center\">It has been more than 30 days since you changed the main administrator password - <a href=\"updateadmin.php\">Click here to change it now</a></div>";
		$ns -> tablerender("Security", $text);
	}
 }



$handle=opendir("help/");
	$text = "";
	while ($file = readdir($handle)){	
		if($file != "." && $file != ".."){
//			 echo "help/".$file."<br />";
			 if(eregi($file, $_SERVER['PHP_SELF'])){
				require_once("help/".$file);
			 }
		}
	}
	closedir($handle);
}
?>
<br />
</td>
<td style="width:60%; vertical-align: top;">