<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/e107_themes/templates/usersettings_template.php,v $
|     $Revision: 1.4 $
|     $Date: 2006/01/12 19:54:17 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

global $usersettings_shortcodes, $pref;


$sc_style['CUSTOMTITLE']['pre'] = "
<tr>
<td style='width:40%' class='forumheader3'>".LAN_CUSTOMTITLE.":</td>
<td style='width:60%' class='forumheader2'>
";
$sc_style['CUSTOMTITLE']['post'] = "</td></tr>";

$sc_style['PASSWORD_LEN']['pre'] = "<br /><span class='smalltext'>  (".LAN_SIGNUP_1." ";
$sc_style['PASSWORD_LEN']['post'] = " ".LAN_SIGNUP_2.")</span>";

$sc_style['USERCLASSES']['pre'] = "<tr>
<td style='width:40%;vertical-align:top' class='forumheader3'>".LAN_USET_5.":
<br /><span class='smalltext'>".LAN_USET_6."</span>
</td>
<td style='width:60%' class='forumheader2'>";
$sc_style['USERCLASSES']['post'] = "</td></tr>";

$sc_style['AVATAR_UPLOAD']['pre'] = "<tr>
<td style='width:40%; vertical-align:top' class='forumheader3'>".LAN_415."<br /></td>
<td style='width:60%' class='forumheader2'>
";
$sc_style['AVATAR_UPLOAD']['post'] = "</td></tr>";

$sc_style['PHOTO_UPLOAD']['pre'] = "
<tr>
<td colspan='2' class='forumheader'>".LAN_425."</td>
</tr>

<tr>
<td style='width:40%; vertical-align:top' class='forumheader3'>".LAN_414."<br /><span class='smalltext'>".LAN_426."</span></td>
<td style='width:60%' class='forumheader2'>
";
$sc_style['PHOTO_UPLOAD']['post'] = "</td></tr>";


$sc_style['XUP']['pre'] = "
<tr>
<td colspan='2' class='forumheader'>".LAN_435."</td>
</tr>
<tr>
<td style='width:20%; vertical-align:top' class='forumheader3'>".LAN_433."<br /><span class='smalltext'><a href='http://e107.org/generate_xup.php' rel='external'>".LAN_434."</a></span></td>
<td style='width:80%' class='forumheader2'>
";
$sc_style['XUP']['post'] = "</td></tr>";

$USER_EXTENDED_CAT = "<tr><td colspan='2' class='forumheader'>{CATNAME}</td></tr>";
$USEREXTENDED_FIELD = "
<tr>
<td style='width:40%' class='forumheader3'>
{FIELDNAME}
</td>
<td style='width:60%' class='forumheader3'>
{FIELDVAL} {HIDEFIELD}
</td>
</tr> 
";
$REQUIRED_FIELD = "{FIELDNAME}<span style='text-align:right;font-size:15px; color:red'> *</span>";

$USERSETTINGS_EDIT = "
<div style='text-align:center'>
	<table style='width:auto' class='fborder'>

	<tr>
	<td colspan='2' class='forumheader'>".LAN_418."</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_7."<br /><span class='smalltext'>".LAN_8."</span></td>
	<td style='width:60%' class='forumheader2'>
	{USERNAME}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_9."<br /><span class='smalltext'>".LAN_10."</span></td>
	<td style='width:60%' class='forumheader2'>
	{LOGINNAME}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_308.req($signupval[0])."</td>
	<td style='width:60%' class='forumheader2'>
	{REALNAME}
	</td>
	</tr>

	{CUSTOMTITLE}

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_152."<br /><span class='smalltext'>".LAN_401."</span></td>
	<td style='width:60%' class='forumheader2'>
	{PASSWORD1}
	{PASSWORD_LEN}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_153."<br /><span class='smalltext'>".LAN_401."</span></td>
	<td style='width:60%' class='forumheader2'>
	{PASSWORD2}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_112."</td>
	<td style='width:60%' class='forumheader2'>
	{EMAIL}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_113."<br /><span class='smalltext'>".LAN_114."</span></td>
	<td style='width:60%' class='forumheader2'><span class='defaulttext'>
	{HIDEEMAIL=radio}
	</span>
	</td>
	</tr>

	{USERCLASSES}
	{USEREXTENDED_ALL}

	<tr><td colspan='2' class='forumheader'>".LAN_USET_8."</td></tr>
	<tr>
	<td style='width:40%;vertical-align:top' class='forumheader3'>".LAN_120.req($signupval[7])."</td>
	<td style='width:60%' class='forumheader2'>
	{SIGNATURE=cols=58&rows=4}
	<br />
	{SIGNATURE_HELP}
	</td>
	</tr>

	<tr>
	<td style='width:40%' class='forumheader3'>".LAN_122.req($signupval[9])."</td>
	<td style='width:60%' class='forumheader2'>
	{TIMEZONE}
	</td>
	</tr>

	<tr>
	<td colspan='2' class='forumheader'>".LAN_420."</td>
	</tr>

	<tr>
	<td colspan='2' class='forumheader3' style='text-align:center'>".LAN_404.($pref['im_width'] || $pref['im_height'] ? "<br />".($pref['im_width'] ? MAX_AVWIDTH.$pref['im_width']." pixels. " : "").($pref['im_height'] ? MAX_AVHEIGHT.$pref['im_height']." pixels." : "") : "")."</td>
	</tr>

	<tr>
	<td style='width:40%; vertical-align:top' class='forumheader3'>".LAN_422.req($signupval[8])."<br /><span class='smalltext'>".LAN_423."</span></td>
	<td style='width:60%' class='forumheader2'>
	{AVATAR_REMOTE}
	</td>
	</tr>

	<tr>
	<td style='width:40%; vertical-align:top' class='forumheader3'>".LAN_421."<br /><span class='smalltext'>".LAN_424."</span></td>
	<td style='width:60%' class='forumheader2'>
	{AVATAR_CHOOSE}
	</td>
	</tr>
	
	{AVATAR_UPLOAD}
	{PHOTO_UPLOAD}
	{XUP}

	<tr style='vertical-align:top'>
	<td colspan='2' style='text-align:center' class='forumheader'><input class='button' type='submit' name='updatesettings' value='".LAN_154."' /></td>
	</tr>
	</table>
	</div>
	";
	

?>