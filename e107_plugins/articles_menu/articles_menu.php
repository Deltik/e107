<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/articles_menu.php
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
if($cache = retrieve_cache("article_menu")){
	$aj = new textparse;
	$cache = str_replace("e107_themes/", e_THEME, $cache);
	$cache = str_replace("<a href=&#39;", "<a href=&#39;".e_BASE, $cache);
	echo $aj -> formtparev($cache);
}else{
	ob_start();
	$text = ($menu_pref['articles_mainlink'] ? "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."content.php?article'> ".($menu_pref['articles_mainlink'] ? $menu_pref['articles_mainlink'] : ARTICLE_MENU_L1)."</a><br/>" : "");

	$sql2=new db;

	if($menu_pref['articles_parents']){
		$text.="<br />";
		if($i = $sql -> db_Select("content", "*", "content_type='0' AND content_parent='0' ")){
			$text .= "<img src='".THEME."images/bullet2.gif' alt='' /> <a href='".e_BASE."content.php?article.cat.0'>".ARTICLE_MENU_L2."</a> (".$i.")<br />";
		}

		if($sql -> db_Select("content", "*", "content_type='6' ORDER BY content_heading ASC")){
			while($row = $sql -> db_Fetch()){
				extract($row);
				if(check_class($content_class)){
					if($i = $sql2 -> db_Select("content", "*", "content_type='0' AND content_parent='".$content_id."' ")){
						$text .= "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."content.php?article.cat.".$content_id."'>".$content_heading."</a> (".$i.")<br />";
					}
				}
			}
		}
		$text.="<br />";
	}
		
	if($sql -> db_Select("content", "*", "content_type='0' ORDER BY content_datestamp DESC limit 0, ".$menu_pref['articles_display'])){
		while($row = $sql-> db_Fetch()){
			extract($row);
			if(check_class($content_class)){
				$ok=0;
				if($content_parent==0){
					$ok=1;
				} else {
					if($sql2 -> db_Select("content","content_class","content_id = '{$content_parent}'")){
						$row2 = $sql2 -> db_Fetch();
						if(check_class($row2['content_class'])){
							$ok=1;
						}
					}
				}
				if($ok){
					$text .= "<img src='".THEME."images/bullet2.gif' alt='bullet' /> <a href='".e_BASE."content.php?article.".$content_id."'>".$content_heading."</a><br />";
				}
			}
		}
		$ns -> tablerender($menu_pref['article_caption'], $text);
	}

	if($pref['cachestatus']){
		$aj = new textparse;
		$cache = $aj -> formtpa(ob_get_contents(), "admin");
		set_cache("article_menu", $cache);
	}
}

?>
