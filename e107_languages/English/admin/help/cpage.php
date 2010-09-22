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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_languages/English/admin/help/cpage.php $
|     $Revision: 11678 $
|     $Id: cpage.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "From this screen you can create custom menus or custom pages with your own content in them.<br /><br />";
// $text .= "Please see <a href='http://docs.e107.org/Using Custom Pages and Custom Menus'>http://docs.e107.org/Using Custom Pages and Custom Menus</a> for an explanation of all the features.";

$ns -> tablerender('Custom Menus/Pages Help', $text);
?>