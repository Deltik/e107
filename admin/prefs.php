<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	//prefs.php
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

if(!getperms("1")){ header("location:".e_HTTP."index.php"); exit;}

if(IsSet($_POST['updateprefs'])){

	$pref['sitename'][1] = stripslashes($_POST['sitename']);
	$pref['siteurl'][1] = stripslashes($_POST['siteurl']);
	$pref['sitebutton'][1] = stripslashes($_POST['sitebutton']);
	$pref['sitetag'][1] = stripslashes($_POST['sitetag']);
	$pref['sitedescription'][1] = stripslashes($_POST['sitedescription']);
	$pref['siteadmin'][1] = stripslashes($_POST['siteadmin']);
	$pref['siteadminemail'][1] = stripslashes($_POST['siteadminemail']);
	$pref['sitetheme'][1] = $_POST['sitetheme'];
	$pref['sitedisclaimer'][1] = stripslashes($_POST['sitedisclaimer']);
	$pref['newsposts'][1] = $_POST['newsposts'];
	$pref['flood_protect'][1] = $_POST['flood_protect'];
	$pref['anon_post'][1] = $_POST['anon_post'];
	$pref['user_reg'][1] = $_POST['user_reg'];
	$pref['profanity_filter'][1] = $_POST['profanity_filter'];
	$pref['profanity_replace'][1] = stripslashes($_POST['profanity_replace']);
	$pref['use_coppa'][1] = $_POST['use_coppa'];
	$pref['shortdate'][1] = $_POST['shortdate'];
	$pref['longdate'][1] = $_POST['longdate'];
	$pref['forumdate'][1] = $_POST['forumdate'];
	$pref['sitelanguage'][1] = $_POST['sitelanguage'];
	$pref['time_offset'][1] = $_POST['time_offset'];
	$pref['flood_hits'][1] = $_POST['flood_hits'];
	$pref['flood_time'][1] = $_POST['flood_time'];
	$pref['user_reg_veri'][1] = $_POST['user_reg_veri'];
	$pref['user_tracking'][1] = $_POST['user_tracking'];
	

	save_prefs();
	header("location:".e_SELF);
	exit;
}


//added prefs since v2.0 ...
$flood_protect = $pref['flood_protect'][1];
$anon_post = $pref['anon_post'][1];
$user_reg = $pref['user_reg'][1];
$profanity_filter = $pref['profanity_filter'][1];
$profanity_replace = $pref['profanity_replace'][1];
$use_coppa = $pref['use_coppa'][1];
$shortdate = $pref['shortdate'][1];
$longdate = $pref['longdate'][1];
$forumdate = $pref['forumdate'][1];
$sitelocale = $pref['sitelocale'][1];
$time_offset = $pref['time_offset'][1];
$flood_hits = $pref['flood_hits'][1];
$flood_time = $pref['flood_time'][1];
$user_reg_veri = $pref['user_reg_veri'][1];
$user_tracking = $pref['user_tracking'][1];

require_once("auth.php");

if(IsSet($message)){
	$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$handle=opendir(e_BASE."themes/");
while ($file = readdir($handle)){
	if($file != "." && $file != ".." && $file != "templates" && $file != "shared"){
		$dirlist[] = $file;
	}
}
closedir($handle);

$handle=opendir(e_BASE."languages/");
while ($file = readdir($handle)){
	if($file != "." && $file != ".." && $file != "/" && $file != "languages.zip"){
		$lanlist[] = eregi_replace("lan_|.php", "", $file);
	}
}
closedir($handle);


$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='width:95%' class='fborder' cellspacing='1' cellpadding='0'>
<tr>

<td colspan='2'>
<div class='caption'>Site Information</div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Site Name: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='sitename' size='50' value='".SITENAME."' maxlength='100' />
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>Site URL: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='siteurl' size='50' value='".SITEURL."' maxlength='150' />
</td>
</tr>


<tr>
<td style='width:50%' class='forumheader3'>Site Link Button: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='sitebutton' size='50' value='".SITEBUTTON."' maxlength='150' />
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Site Tagline: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<textarea class='tbox' name='sitetag' cols='59' rows='3'>".SITETAG."</textarea>
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Site Description: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<textarea class='tbox' name='sitedescription' cols='59' rows='3'>".SITEDESCRIPTION."</textarea>
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Main site admin: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='siteadmin' size='50' value='".SITEADMIN."' maxlength='100' />
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Main site admin email: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='siteadminemail' size='50' value='".SITEADMINEMAIL."' maxlength='100' />
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Site Disclaimer: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<textarea class='tbox' name='sitedisclaimer' cols='59' rows='3'>".SITEDISCLAIMER."</textarea>
</td>
</tr>

<tr>
<td colspan='2'>
<div class='border'><div class='caption'>Theme</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Site Theme: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='sitetheme' class='tbox'>\n";
$counter = 0;
while(IsSet($dirlist[$counter])){
	if($dirlist[$counter] == $pref['sitetheme'][1]){
		$text .= "<option selected>".$dirlist[$counter]."</option>\n";
	}else{
		$text .= "<option>".$dirlist[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
</td>
</tr>

<td colspan='2'>
<div class='border'><div class='caption'>Language</div></div>
</td>
</tr>
<tr>

<td style='width:50%' class='forumheader3'>Site Language: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='sitelanguage' class='tbox'>\n";
$counter = 0;
$sellan = eregi_replace("lan_*.php", "", $pref['sitelanguage'][1]);
while(IsSet($lanlist[$counter])){
	if($lanlist[$counter] == $sellan){
		$text .= "<option selected>".$lanlist[$counter]."</option>\n";
	}else{
		$text .= "<option>".$lanlist[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
</td>
</tr>

<tr>
<td colspan='2'>
<div class='border'><div class='caption'>News options</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>News posts to display per page?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='newsposts' class='tbox'>";
if(ITEMVIEW == 5){
	$text .= "<option selected>5</option>\n";
}else{
	$text .= "<option>5</option>\n";
}
if(ITEMVIEW == 10){
	$text .= "<option selected>10</option>\n";
}else{
	$text .= "<option>10</option>\n";
}
if(ITEMVIEW == 15){
	$text .= "<option selected>15</option>\n";
}else{
	$text .= "<option>15</option>\n";
}
if(ITEMVIEW == 20){
	$text .= "<option selected>20</option>\n";
}else{
	$text .= "<option>20</option>\n";
}
if(ITEMVIEW == 25){
	$text .= "<option selected>5</option>\n";
}else{
	$text .= "<option>25</option>\n";
}

$text .= "</select>
</td>
</tr>

<tr>
<td colspan='2'>
<div class='border'><div class='caption'>Date display options</div></div>
</td>
</tr>
<tr>";

$ga = new convert;
$date1 = $ga -> convert_date(time(), "short");
$date2 = $ga -> convert_date(time(), "long");
$date3 = $ga -> convert_date(time(), "forum");


$text .= "<td style='width:50%' class='forumheader3'>Short date format: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='shortdate' size='40' value='$shortdate' maxlength='50' /> 
<br />example: $date1
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>Long date format: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='longdate' size='40' value='$longdate' maxlength='50' /> 
<br />example: $date2
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>Forum date format: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='forumdate' size='40' value='$forumdate' maxlength='50' /> 
<br />example: $date3
</td>
</tr>

<tr>
<td colspan='2' style='text-align:center' class='forumheader3'>
(For more information on date formats see the <a href='http://www.php.net/manual/en/function.date.php' target='_blank'>date function page at php.net</a>)
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>Time offset: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='time_offset' class='tbox'>\n";
$toffset = array("-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "0", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9", "+10", "+11", "+12", "+13");
$counter = 0;
while(IsSet($toffset[$counter])){
	if($toffset[$counter] == $pref['time_offset'][1]){
		$text .= "<option selected>".$toffset[$counter]."</option>\n";
	}else{
		$text .= "<option>".$toffset[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
</td></tr>

<tr>
<td colspan='2' style='text-align:center' class='forumheader3'>
(Example, if you set this to +2, all times on your site will have two hours added to them)
</td>
</tr>

<tr>
<td colspan='2'>
<div class='border'><div class='caption'>User registration/posting</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Activate user registration system?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($user_reg == 1){
	$text .= "<input type='checkbox' name='user_reg' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='user_reg' value='1'>";
}

$text .= " (allow users to register as members on your site)



</tr><tr>

<td style='width:50%' class='forumheader3'>Use email verification for signups?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($user_reg_veri == 1){
	$text .= "<input type='checkbox' name='user_reg_veri' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='user_reg_veri' value='1'>";
}

$text .= "
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Allow anonymous posting?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($anon_post == 1){
	$text .= "<input type='checkbox' name='anon_post' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='anon_post' value='1'>";
}

$text .= "(if left unchecked only registered members can post comments etc)
</td>
</tr>
<tr>

<td colspan='2'>
<div class='border'><div class='caption'>Security</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Enable flood protection?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($flood_protect == 1){
	$text .= "<input type='checkbox' name='flood_protect' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='flood_protect' value='1'>";
}

$text .= "
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Flood hits: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='flood_hits' size='10' value='$flood_hits' maxlength='4' />
</td>
</tr>

</td>
</tr>
<tr>
<td style='width:50%; vertical-align:top' class='forumheader3'>Flood time: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='flood_time' size='10' value='$flood_time' maxlength='20' />
<br />
example, flood hits set to 100 and flood time set to 50: if any single page on your site gets 100 hits in 50 seconds the page will be inaccessable for a further 50 seconds. 
</td>
</tr>

<tr>

<td colspan='2'>
<div class='border'><div class='caption'>Protection of minors options</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Filter profanities?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($profanity_filter == 1){
	$text .= "<input type='checkbox' name='profanity_filter' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='profanity_filter' value='1'>";
}

$text .= "(if checked swearing will be replaced with string below)
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>Replace string: </td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input class='tbox' type='text' name='profanity_replace' size='30' value='$profanity_replace' maxlength='20' />
</td>
</tr>

<tr>

<td style='width:50%' class='forumheader3'>Use COPPA on signup page?: </td>
<td style='width:50%; text-align:right' class='forumheader3'>";
if($use_coppa == 1){
	$text .= "<input type='checkbox' name='use_coppa' value='1'  checked>";
}else{
	$text .= "<input type='checkbox' name='use_coppa' value='1'>";
}


$text .= "(for more info on COPPA see <a href='http://www.cdt.org/legislation/105th/privacy/coppa.html'>here</a>)
</td>
</tr>


<td colspan='2'>
<div class='border'><div class='caption'>User tracking</div></div>
</td>
</tr><tr>

<td style='width:50%' class='forumheader3'>Tracking method: </td>
<td style='width:50%; text-align:right' class='forumheader3'>".
($user_tracking == "cookie" ? "<input type='radio' name='user_tracking' value='cookie' checked> Cookies" : "<input type='radio' name='user_tracking' value='cookie'> Cookies").
($user_tracking == "session" ? "<input type='radio' name='user_tracking' value='session' checked> Sessions" : "<input type='radio' name='user_tracking' value='session'> Sessions")."

</td>
</tr>






<tr>
<td colspan='2' class='forumheader3'>
<div class='border'><div class='caption'>e107</div></div>
<br />
<div style='text-align:center'><input class='button' type='submit' name='newver' value='Click here to check latest version of e107' /></div>
</td>
</tr><tr>

<tr><td colspan='2'  class='forumheader3'><br /></td></tr>

<tr style='vertical-align:top'> 
<td colspan='2'  style='text-align:center' class='forumheader3'>
<br />
<input class='caption' type='submit' name='updateprefs' value='Save Changes' />
</td>
</tr>
</table>
</form>
</div>";




$ns -> tablerender("<div style='text-align:center'>Site Preferences</div>", $text);

require_once("footer.php");
?>	