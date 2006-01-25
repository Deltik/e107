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
|     $Source: /cvsroot/e107/e107_0.7/e107_themes/templates/userposts_template.php,v $
|     $Revision: 1.4 $
|     $Date: 2005/12/14 19:28:53 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

// ##### USERPOSTS_COMMENTS TABLE -----------------------------------------------------------------
if(!$USERPOSTS_COMMENTS_TABLE_START){
	$USERPOSTS_COMMENTS_TABLE_START = "
	<div style='text-align:center'>
	<table class='fborder' style='width:95%'>\n";
}
if(!$USERPOSTS_COMMENTS_TABLE){
	$USERPOSTS_COMMENTS_TABLE = "
	<tr>
	<td class='fcaption'>
	{USERPOSTS_COMMENTS_HREF_PRE}<b>{USERPOSTS_COMMENTS_HEADING}</b></a> 
	<span class='smalltext'>{USERPOSTS_COMMENTS_DATESTAMP} ({USERPOSTS_COMMENTS_TYPE})</span>
	</td>
	</tr>
	<tr>
	<td class='forumheader3'>
	{USERPOSTS_COMMENTS_COMMENT}&nbsp;
	</td>
	</tr>
	";
}
if(!$USERPOSTS_COMMENTS_TABLE_END){
	 $USERPOSTS_COMMENTS_TABLE_END = "
	</table>
	</div>";
}
// ##### ------------------------------------------------------------------------------------------

// ##### USERPOSTS FORUM TABLE --------------------------------------------------------------------
if(!$USERPOSTS_FORUM_TABLE_START){
	$USERPOSTS_FORUM_TABLE_START = "
	<div style='text-align:center'>
	<form method='post' action='".e_SELF."?".e_QUERY."'>
	<table class='fborder' style='width:95%'>";
}
if(!$USERPOSTS_FORUM_TABLE){
	$USERPOSTS_FORUM_TABLE .= "
	<tr>
	<td class='fcaption'>
	{USERPOSTS_FORUM_TOPIC_HREF_PRE}<b>{USERPOSTS_FORUM_TOPIC_PRE} {USERPOSTS_FORUM_TOPIC}</b></a>
	<span class='smalltext'>({USERPOSTS_FORUM_NAME_HREF_PRE}<b>{USERPOSTS_FORUM_NAME}</b></a>)</span>
	<span class='smalltext'>{USERPOSTS_FORUM_DATESTAMP}</span>
	</td>
	</tr>
	<tr>
	<td class='forumheader3'>
	{USERPOSTS_FORUM_THREAD}
	</td>
	</tr>
	";
}
if(!$USERPOSTS_FORUM_TABLE_END){
	$USERPOSTS_FORUM_TABLE_END = "
	<tr>
	<td class='forumheader' style='text-align:right'>
	{USERPOSTS_FORUM_SEARCH}
	</td>
	</tr>
	</table>
	</form>
	</div>";
}
// ##### ------------------------------------------------------------------------------------------


?>