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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/log/log_update_check.php $
|     $Revision: 11678 $
|     $Id: log_update_check.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

$dbupdatep['log_07'] =  LAN_UPDATE_8." .617 statistics ".LAN_UPDATE_9." .7 statistics";
function update_log_07($type) {
	global $sql, $mySQLdefaultdb;
	if ($type == 'do') {
			include_once(e_PLUGIN.'log/log_update.php');
	} else {
		if ($sql -> db_Query("SHOW COLUMNS FROM ".MPREFIX."stat_info") && $sql -> db_Select("plugin", "*", "plugin_path = 'log' AND plugin_installflag='1'")) {
			 if ($sql -> db_Count('stat_info','(*)',"WHERE info_type='99'") < 7) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}
}

?>		