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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_admin/adminb.php $
|     $Revision: 11678 $
|     $Id: adminb.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (ADMIN) {
	header('Location:'.SITEURL.$ADMIN_DIRECTORY.'admin.php');
	exit;
} else {
	header('Location:'.SITEURL.'index.php');
	exit;
}
?>