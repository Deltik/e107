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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/subcontent.php $
|     $Revision: 11678 $
|     $Id: subcontent.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");

//redirection to new content management plugin if it is installed
if ($sql -> db_Select("plugin", "*", "plugin_path = 'content' AND plugin_installflag = '1' ")){ 
	header("location:".e_PLUGIN."content/content_submit.php");
	exit;
} else {
	header("location:".e_BASE."index.php");
	exit;
}
?>