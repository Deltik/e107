<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/forum/templates/forum_preview_template.php $
|     $Revision: 11678 $
|     $Id: forum_preview_template.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
if(!defined("USER_WIDTH")){ define("USER_WIDTH","width:95%;margin-left:auto;margin-right:auto"); }

$FORUM_PREVIEW = "<div style='text-align:center'>
<table style='".USER_WIDTH."' class='fborder'>
<tr>
<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_323.
($action != "nt" ? "</td>" : " ( ".LAN_62.$tsubject." )</td>")."
<tr>
<td class='forumheader3' style='width:20%; vertical-align:top'><b>".$poster."</b></td>
<td class='forumheader3' style='width:80%'>
<div class='smallblacktext' style='text-align:right'>".IMAGE_post2." ".LAN_322.$postdate."</div>".$tpost."</td>
</tr>
</table>
</div>";

?>