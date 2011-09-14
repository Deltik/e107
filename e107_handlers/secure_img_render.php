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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_handlers/secure_img_render.php $
|     $Revision: 12299 $
|     $Id: secure_img_render.php 12299 2011-06-30 10:49:38Z secretr $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/

define('e107_INIT', true);
require_once(realpath(dirname(__FILE__)."/secure_img_handler.php"));

$sim = new secure_image();
$sim->render($_SERVER['QUERY_STRING']);

exit;


?>