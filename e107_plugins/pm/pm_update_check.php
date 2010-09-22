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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/pm/pm_update_check.php $
|     $Revision: 11678 $
|     $Id: pm_update_check.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

include_lan(e_PLUGIN."pm/languages/admin/".e_LANGUAGE.".php");
$dbupdatep['pm_07'] =  LAN_UPDATE_8." .617 ".ADLAN_PM_58." ".LAN_UPDATE_9." .7 ".ADLAN_PM_58;
function update_pm_07($type) 
{
	global $sql, $mySQLdefaultdb;
	if ($type == 'do') 
	{
			include_once(e_PLUGIN.'pm/pm_update.php');
	} 
	else 
	{
		if (isset($pref['plug_installed']['pm']))
		{
			if ($sql -> db_Count('pm_messages', '(*)')) 
			{
				return FALSE;
			} 
			else 
			{
				return TRUE;
			}
		} 
		else 
		{
			return TRUE;
		}
	}
}

?>		
