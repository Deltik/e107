<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2011 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Poll plugin main page display
 *
 * $URL$
 * $Id$
 */

require_once('../../class2.php');
if (!e107::isInstalled('poll')) 
{
	header('Location: '.e_BASE.'index.php');
	exit;
}

require_once(HEADERF);

require(e_PLUGIN.'poll/poll_menu.php');


require_once(FOOTERF);
exit;

?>