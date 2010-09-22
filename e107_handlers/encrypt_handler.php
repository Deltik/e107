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
|     $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.7/e107_handlers/encrypt_handler.php $
|     $Revision: 11678 $
|     $Id: encrypt_handler.php 11678 2010-08-22 00:43:45Z e107coders $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

function encode_ip($ip)
{
  if (strpos($ip,':') !== FALSE) return $ip;			// IPV6 addresses - return unaltered
	$ipa = explode('.', $ip);
	return sprintf('%02x%02x%02x%02x', $ipa[0], $ipa[1], $ipa[2], $ipa[3]);
}

function decode_ip($encodedIP)
{
  if (strpos($encodedIP,':') !== FALSE) return $encodedIP;			// IPV6 addresses - return unaltered
	$hexip = explode('.', chunk_split($encodedIP, 2, '.'));
	return hexdec($hexip[0]). '.' . hexdec($hexip[1]) . '.' . hexdec($hexip[2]) . '.' . hexdec($hexip[3]);
}




?>