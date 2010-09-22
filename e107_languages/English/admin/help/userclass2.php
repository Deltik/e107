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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_languages/English/admin/help/userclass2.php $
|     $Revision: 11737 $
|     $Id: userclass2.php 11737 2010-09-03 12:18:37Z e107steved $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "User Class Help";
$text = "You can create or edit/delete existing classes from this page.<br />This is useful for restricting users to certain parts of your site. For example, you could create a class called TEST, then create a forum which only allowed users in the TEST class to access it.";
$ns -> tablerender($caption, $text);
?>