<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/menus/articles_menu.php
|
|	�Edwin van der Wal 2003
|	http://e107.org
|	evdwal@xs4all.nl
|	Based on the articles_menu.php
|
|	Released under the terms and conditions of the	
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
$text = ($menu_pref['articles_mainlink'] ? "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."article.php?list.0'> ".$menu_pref['articles_mainlink']."</a><br/>" : "");

if($sql -> db_Select("content", "*", "content_type='0' ORDER BY content_datestamp DESC limit 0, ".$menu_pref['articles_display'])){
	while($row = $sql-> db_Fetch()){
		extract($row);
		$text .= "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."article.php?".$content_id.".0"."'>".$content_heading."</a><br />";
	}
	$ns -> tablerender($menu_pref['article_caption'], $text);
}
?>
