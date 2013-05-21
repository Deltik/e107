<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ©Steve Dunstan 2001-2002
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/powered_by_menu/powered_by_menu.php $
|     $Revision: 13103 $
|     $Id: powered_by_menu.php 13103 2013-04-26 22:36:04Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

$text = "
<div style='text-align: center'>
	<a href='http://e107.org/' rel='external'><img src='".e_PLUGIN_ABS."powered_by_menu/images/powered.png' alt='e107' style='max-width:100%;border: 0px;' /></a>
</div>
";
$ns -> tablerender(POWEREDBY_L1,  $text, 'powered_by');
?>