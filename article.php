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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/article.php $
|     $Revision: 11678 $
|	  $Id: article.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Id: article.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
// This file is now deprecated and remains in core for backward compatibility reasons.
	
$tmp = explode(".", $_SERVER['QUERY_STRING']);
$action = -1;
$sub_action = 0;
if (isset($tmp[0])) 
{ 
	$action = $tmp[0]; 
	if (isset($tmp[1])) { $sub_action = $tmp[1]; }
}

	
if ($sub_action == 255) 
{
	// content page
	header("Location: content.php?content.{$action}");
	exit;
}

	
if ($action == 0) 
{
	// content page
	header("Location: content.php?article");
	exit;
} 
else 
{
	header("Location: content.php?review");
	exit;
}
	
?>