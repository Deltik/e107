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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_languages/English/admin/help/ugflag.php $
|     $Revision: 11678 $
|     $Id: ugflag.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "If you are upgrading e107 or just need your site to be offline for a while just tick the maintenance box and your visitors will be redirected to a page explaining the site is down for repair. After you've finished un-tick the box to return site to normal.";

$ns -> tablerender("Maintenance", $text);
?>
