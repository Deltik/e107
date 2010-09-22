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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_languages/English/admin/help/cache.php $
|     $Revision: 11678 $
|     $Id: cache.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Caching";
$text = "If you have caching turned on it will vastly improve speed on your site and minimize the amount of calls to the sql database.<br /><br /><b>IMPORTANT! If you are making your own theme turn caching off as any changes you make will not be reflected.</b>";
$ns -> tablerender($caption, $text);
?>