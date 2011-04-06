<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2011 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_plugins/poll/poll.php $
|     $Revision: 12040 $
|     $Id: poll.php 12040 2011-01-14 18:28:57Z e107steved $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
require_once('../../class2.php');
if (!isset($pref['plug_installed']['poll']))
{
	header('Location: '.e_BASE.'index.php');
	exit;
}
require_once(HEADERF);

require(e_PLUGIN.'poll/poll_menu.php');


require_once(FOOTERF);
exit;

?>