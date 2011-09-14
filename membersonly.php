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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/membersonly.php $
|     $Revision: 12296 $
|     $Id: membersonly.php 12296 2011-06-29 05:39:14Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");

include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_membersonly.php");

if(is_readable(THEME."membersonly_template.php"))
{
	require_once(THEME."membersonly_template.php");
}
else
{
	require_once(e_THEME."templates/membersonly_template.php");
}

$HEADER=""; 
$FOOTER=""; 

include_once(HEADERF);

echo $MEMBERSONLY_BEGIN;
$ns->tablerender($MEMBERSONLY_CAPTION, $MEMBERSONLY_TABLE); 
echo $MEMBERSONLY_END;

require_once(FOOTERF);
?>
