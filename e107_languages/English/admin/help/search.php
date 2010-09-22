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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_languages/English/admin/help/search.php $
|     $Revision: 11678 $
|     $Id: search.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "If your MySQL server version supports it you can switch 
to the MySQL sort method which is faster than the PHP sort method. See preferences.<br /><br />
If your site includes Ideographic languages such as Chinese and Japanese you must 
use the PHP sort method and switch whole word matching off.";
$ns -> tablerender("Search Help", $text);
?>