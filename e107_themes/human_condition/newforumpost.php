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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_themes/human_condition/newforumpost.php $
|     $Revision: 11678 $
|     $Id: newforumpost.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$NEWFORUMPOSTSTYLE_HEADER = "
<!-- newforumposts -->\n<ul type='square'>\n";

$NEWFORUMPOSTSTYLE_MAIN = "<li><span class='smalltext'>{THREAD} ".NFPM_LAN_7." {POSTER} [ ".NFPM_LAN_3.": {VIEWS}, ".NFPM_LAN_4.": {REPLIES}, ".NFPM_LAN_5.": {LASTPOST} ]\n</span>\n</li>\n";

$NEWFORUMPOSTSTYLE_FOOTER = "<br /><br />\n</ul><span class='smalltext'>".NFPM_LAN_6.": <b>{TOTAL_TOPICS}</b> | ".NFPM_LAN_4.": <b>{TOTAL_REPLIES}</b> | ".NFPM_LAN_3.": <b>{TOTAL_VIEWS}</b></span>";


?>