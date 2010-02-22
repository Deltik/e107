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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/login_menu/config.php,v $
|     $Revision: 11346 $
|     $Date: 2010-02-17 13:56:14 -0500 (Wed, 17 Feb 2010) $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/

$eplug_admin = TRUE;
require_once("../../class2.php");
if (!getperms("4")) { header("location:".e_BASE."index.php"); exit ;}

include_lan(e_PLUGIN."login_menu/languages/".e_LANGUAGE.".php");
require_once(e_ADMIN."auth.php");

if ($_POST['update_menu']) {
	unset($menu_pref['login_menu']);
	$menu_pref['login_menu'] = $_POST['pref'];
	$tmp = addslashes(serialize($menu_pref));
	$sql->db_Update("core", "e107_value='$tmp' WHERE e107_name='menu_pref' ");
	$ns->tablerender("", '<div style=\'text-align:center\'><b>'.LAN_SETSAVED.'</b></div>');
}

$text = '
	<div style="text-align:center">
	<form action="'.e_SELF.'?'.e_QUERY.'" method="post">
	<table style="width:85%" class="fborder" >

	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L31.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_news]" value="1"'.($menu_pref['login_menu']['new_news'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>

	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L32.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_articles]" value="1"'.($menu_pref['login_menu']['new_articles'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>

	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L33.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_chatbox]" value="1"'.($menu_pref['login_menu']['new_chatbox'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>

	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L34.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_comments]" value="1"'.($menu_pref['login_menu']['new_comments'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>


	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L35.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_forum]" value="1"'.($menu_pref['login_menu']['new_forum'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>

	<tr>
	<td style="width:30%" class="forumheader3">'.LOGIN_MENU_L36.'</td>
	<td style="width:70%" class="forumheader3">
	<input type="checkbox" name="pref[new_members]" value="1"'.($menu_pref['login_menu']['new_members'] == 1 ? ' checked="checked"' : '').' />
	</td>
	</tr>

	<tr>
	<td colspan="2" class="forumheader" style="text-align: center;"><input class="button" type="submit" name="update_menu" value="'.LAN_SAVE.'" /></td>
	</tr>
	</table>
	</form>
	</div>
	';

$ns->tablerender(LOGIN_MENU_L41, $text);

require_once(e_ADMIN."footer.php");

?>
