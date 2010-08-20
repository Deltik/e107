<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/search/search_comment.php,v $
|     $Revision: 11346 $
|     $Date: 2010-02-17 12:56:14 -0600 (Wed, 17 Feb 2010) $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

// advanced 
$advanced_where = "";
if (isset($_GET['type']) && $_GET['type'] != 'all') {
	$advanced_where .= " c.comment_type='".(str_replace('s_', '', $tp -> toDB($_GET['type'])))."' AND";
}

if (isset($_GET['time']) && is_numeric($_GET['time'])) {
	$advanced_where .= " c.comment_datestamp ".($_GET['on'] == 'new' ? '>=' : '<=')." '".(time() - $_GET['time'])."' AND";
}

if (isset($_GET['author']) && $_GET['author'] != '') {
	$advanced_where .= " c.comment_author LIKE '%".$tp -> toDB($_GET['author'])."%' AND";
}

//basic
$return_fields = 'c.comment_item_id, c.comment_author, c.comment_datestamp, c.comment_comment, c.comment_type';

foreach ($search_prefs['comments_handlers'] as $h_key => $value) {
	if (check_class($value['class'])) {
		$path = ($value['dir'] == 'core') ? e_HANDLER.'search/comments_'.$h_key.'.php' : e_PLUGIN.$value['dir'].'/search/search_comments.php';
		if (is_readable($path)) {
			require_once($path);
			$in[] = "'".$value['id']."'";
			$join[] = $comments_table[$h_key];
			$return_fields .= ', '.$comments_return[$h_key];
		}
		
	}
}

$search_fields = array('c.comment_comment', 'c.comment_author');
$weights = array('1.2', '0.6');
$no_results = LAN_198;
$where = "comment_type IN (".implode(',', $in).") AND".$advanced_where;
$order = array('comment_datestamp' => DESC);
$table = "comments AS c ".implode(' ', $join);

$ps = $sch -> parsesearch($table, $return_fields, $search_fields, $weights, 'search_comment', $no_results, $where, $order);
$text .= $ps['text'];
$results = $ps['results'];

function search_comment($row) {	
	if (is_callable('com_search_'.$row['comment_type'])) {
		$res = call_user_func('com_search_'.$row['comment_type'], $row);
		return $res;
	}
}

?>