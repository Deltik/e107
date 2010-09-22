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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_handlers/user_func.php $
|     $Revision: 11678 $
|     $Id: user_func.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

function e107_userGetuserclass($user_id)
{
	global $cachevar;
	$key = 'userclass_'.$user_id;
	$val = getcachedvars($key);
	if ($val)
	{
		return $cachevar[$key];
	}
	else
	{
		$uc_sql = new db;
		if ($uc_sql->db_Select("user", "user_class, user_admin", "user_id=".intval($user_id)))
		{
			$row = $uc_sql->db_Fetch();
			$uc = $row['user_class'];
			$uc .= ",".e_UC_MEMBER;
			if($row['user_admin'])
			{
				$uc .= ",".e_UC_ADMIN;
			}
			return $uc;
		}
		else
		{
			return "";
		}
	}
}
?>