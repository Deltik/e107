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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/blogcalendar_menu/functions.php $
|     $Revision: 11678 $
|     $Id: functions.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
	
if (!defined('e107_INIT')) { exit; }

// format a date as yyyymmdd
function formatDate($year, $month, $day = "") {
	$date = $year;
	$date .= (strlen($month) < 2)?"0".$month:
	$month;
	$date .= (strlen($day) < 2 && $day != "")?"0".$day:
	$day;
	return $date;
}
?>