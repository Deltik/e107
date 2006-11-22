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
|     $Source: /cvsroot/e107/e107_0.7/e107_themes/lamb/forum_post_template.php,v $
|     $Revision: 1.11 $
|     $Date: 2006/10/24 13:45:10 $
|     $Author: mrpete $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

// the user box and subject box are not always displayed, therefore we need to define them /in case/ they are, if not they'll be ignored.

$userbox = "<tr>
<td class='forumheader2' style='width:20%'>".LAN_61."</td>
<td class='forumheader2' style='width:80%'>
<input class='tbox' type='text' name='anonname' size='71' value='".$anonname."' maxlength='20' />
</td>
</tr>";

$subjectbox = "<tr>
<td class='forumheader2' style='width:20%'>".LAN_62."</td>
<td class='forumheader2' style='width:80%'>
<input class='tbox' type='text' name='subject' size='71' value='".$subject."' maxlength='100' />
</td>
</tr>";

// the poll is optional, be careful when changing the values here, only change if you know what you're doing ...

require_once(e_PLUGIN."poll/poll_class.php");
$pollo = new poll;
$poll = $pollo -> renderPollForm("forum");

// finally, file attach is optional, again only change this if you know what you're doing ...

$fileattach = "<tr><td colspan='2' class='nforumcaption2'>".($pref['image_post'] ? LAN_390 : LAN_416)."</td></tr>
<tr><td style='width:20%' class='forumheader3'>".LAN_392."</td>
<td style='width:80%' class='forumheader3'>".LAN_393." | ".$allowed_filetypes." |<br />".LAN_394."<br />".LAN_395.": ".($pref['upload_maxfilesize'] ? $pref['upload_maxfilesize'].LAN_396 : ini_get('upload_max_filesize'))."
<br />

<div id='fiupsection'>
<span id='fiupopt'><input class='tbox' name='file_userfile[]' type='file' size='47' /></span>
</div>
<input class='button' type='button' name='addoption' value='".LAN_417."' onclick=\"duplicateHTML('fiupopt','fiupsection')\" />
</td>
</tr>
</td>
</tr>";


// ------------

$FORUMPOST = "
<div style='text-align:center'>
{FORMSTART}
<table style='width:100%' class='fborder'>
<tr>
<td colspan='2' class='nforumcaption'>{BACKLINK}
</td>
</tr>
{USERBOX}
{SUBJECTBOX}
<tr>
<td class='forumheader2' style='width:20%'>{POSTTYPE}</td>
<td class='forumheader2' style='width:80%'>
{POSTBOX}<br />{EMAILNOTIFY}<br />{POSTTHREADAS}
</td>
</tr>

{POLL}

{FILEATTACH}

<tr style='vertical-align:top'>
<td colspan='2' class='forumheader' style='text-align:center'>
{BUTTONS}
</table>
{FORMEND}
</div>
{FORUMJUMP}
";

$FORUMTHREADPOSTED = "
<table style='width:100%' class='fborder'>
<tr>
<td class='nforumcaption2' colspan='2'>".LAN_133."</td>
</tr><tr>
<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
<td style='vertical-align:center; width:80%' class='forumheader2'>
<br />".LAN_324."<br />
<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?".$thread_id."'>".LAN_325."</a><br />
<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
</td></tr></table>";


$FORUMREPLYPOSTED = "
<table style='width:100%' class='fborder'>
<tr>
<td class='fcaption' colspan='2'>".LAN_133."</td>
</tr><tr>
<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
<td style='vertical-align:center; width:80%' class='forumheader2'>
<br />".LAN_324."<br />
<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?{$iid}.last'>".LAN_325."</a><br />
<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
</td></tr></table>";



?>