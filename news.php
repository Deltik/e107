<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/index.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("class2.php");
require_once(e_HANDLER."news_class.php");
require_once(HEADERF);

if($NEWSHEADER){
	require_once(FOOTERF);
	exit;
}

if(Empty($pref['newsposts']) ? define("ITEMVIEW", 15) : define("ITEMVIEW", $pref['newsposts']));
if(file_exists("install.php")){ echo "<div class='installe' style='text-align:center'><b>*** ".LAN_00."</div><br /><br />"; }

if(!is_object($aj)){ $aj = new textparse; }

$ix = new news;
if(strstr(e_QUERY, "cat")){
	$qs = explode(".", e_QUERY);
	$category = $qs[1];
	if($category != 0){
		$gen = new convert;
		$sql2 = new db;
		$sql -> db_Select("news_category", "*", "category_id='$category'");
		list($category_id, $category_name, $category_icon) = $sql-> db_Fetch();
		$category_name = $aj -> tpa($category_name);
		$category_icon = e_IMAGE."newsicons/".$category_icon;
		
		$count = $sql -> db_SELECT("news", "*",  "news_category='$category' ORDER BY news_datestamp DESC");
		while($row = $sql-> db_Fetch()){
			extract($row);
			$news_title = $aj -> tpa($news_title);
			$news_body = $aj -> tpa($news_body);
			if($news_title == ""){ $news_title = "Untitled"; }
			$datestamp = $gen->convert_date($news_datestamp, "short");
			$news_body = strip_tags(substr($news_body, 0, 100))." ...";
			$comment_total = $sql2 -> db_Count("comments", "(*)",  "WHERE comment_item_id='$news_id' AND comment_type='0' ");
			$text .= "<div class='mediumtext'>
			<img src='".THEME."images/bullet2.gif' alt='bullet' /> ";

			if($news_allow_comments){
				$text .= "<a href='news.php?extend.".$news_id."'>".$news_title."</a>";
			}else{
				$text .= "<a href='comment.php?".$news_id."'>".$news_title."</a>";
			}
			$text .= "<br />
			".LAN_100." ".$datestamp." (".LAN_99.": ";
			if($news_allow_comments){
				$text .= COMMENTOFFSTRING.")";
			}else{
				$text .= $comment_total.")";
			}
			$text .= "</div>
			".$news_body."
			<br /><br />\n";
		}
		$text = "<img src='$category_icon' alt='' /><br />".
		LAN_307.$count."
		<br /><br />".$text;
		$ns -> tablerender(LAN_82." '".$category_name."'", $text);
		require_once(FOOTERF);
		exit;
	}
}


if(eregi("extend", e_QUERY)){
	$extend_id = substr(e_QUERY, (strpos(e_QUERY, ".")+1));
	$sql -> db_Select("news", "*", "news_id='$extend_id' ");
	list($news['news_id'], $news['news_title'], $news['data'], $news['news_extended'], $news['news_datestamp'], $news['admin_id'], $news_category, $news['news_allow_comments'],  $news['news_start'], $news['news_end'], $news['news_class']) = $sql -> db_Fetch();
	$sql -> db_Select("news_category", "*",  "category_id='$news_category' ");
	list($news['category_id'], $news['category_name'], $news['category_icon']) = $sql-> db_Fetch();
	$news['comment_total'] = $sql -> db_Count("comments", "(*)",  "WHERE comment_item_id='".$news['news_id']."' AND comment_type='0' ");
	$sql -> db_Select("user", "user_name", "user_id='".$news['admin_id']."' ");
	list($news['admin_name']) = $sql -> db_Fetch();
	$ix -> render_newsitem($news);
	require_once(FOOTERF);
	exit;
}

if($pref['nfp_display'] == 1){
	require_once(e_PLUGIN."newforumposts_main/newforumposts_main.php");
}

if(!e_QUERY || eregi("cat", e_QUERY)){ $from = 0; }else{ $from = e_QUERY; }
if(Empty($order)){ $order = "news_datestamp"; }

// ---> wmessage
$sql -> db_Select("wmessage");
list($wm_guest, $guestmessage, $wm_active1) = $sql-> db_Fetch();
list($wm_member, $membermessage, $wm_active2) = $sql-> db_Fetch();
list($wm_admin, $adminmessage, $wm_active3) = $sql-> db_Fetch();
if(ADMIN == TRUE && $wm_active3){
	$adminmessage = $aj -> tpa($adminmessage, "on");
	$ns -> tablerender("", "<div style='text-align:center'><b>Administrators</b><br />".$adminmessage."</div>");
}else if(USER == TRUE && $wm_active2){
	$membermessage = $aj -> tpa($membermessage, "on");
	$ns -> tablerender("", "<div style='text-align:center'>".$membermessage."</div>");
}else if(USER == FALSE && $wm_active1){
	$guestmessage = $aj -> tpa($guestmessage, "on");
	$ns -> tablerender("", "<div style='text-align:center'>".$guestmessage."</div>");
}
// ---> wmessage end

$news_total = $sql -> db_Count("news");
if($sql -> db_Select("news", "*", "news_class<255 AND news_class!='' AND (news_start=0 || news_start < ".time().") AND (news_end=0 || news_end>".time().") AND news_class!='' ORDER BY ".$order." DESC LIMIT $from,".ITEMVIEW)){
	$disablecache = TRUE;
}

if(!$disablecache && !e_QUERY){
	if($cache_data = retrieve_cache("news.php")){
		echo $aj -> formtparev($cache_data); 
		$cachestring = "Cache system activated (content originally served ".strftime("%A %d %B %Y - %H:%M:%S", $cache_datestamp).").";
		require_once(e_HANDLER."np_class.php");
		$ix = new nextprev("news.php", $from, ITEMVIEW, $news_total, LAN_84);
		require_once(FOOTERF);
		exit;
	}
}

ob_start();
if(!$sql -> db_Select("news", "*", "news_class<255 AND (news_start=0 || news_start < ".time().") AND (news_end=0 || news_end>".time().") ORDER BY ".$order." DESC LIMIT $from,".ITEMVIEW)){
	echo "<br /><br /><div style='text-align:center'><b>".LAN_83."</b></div><br /><br />";
}else{
	$sql2 = new db;
	while(list($news['news_id'], $news['news_title'], $news['data'], $news['news_extended'], $news['news_datestamp'], $news['admin_id'], $news_category, $news['news_allow_comments'],  $news['news_start'], $news['news_end'], $news['news_class']) = $sql -> db_Fetch()){

		if(check_class($news['news_class'])){

			if($news['admin_id'] == 1 && $pref['siteadmin']){
				$news['admin_name'] = $pref['siteadmin'];
			}else if(!$news['admin_name'] = getcachedvars($news['admin_id'])){
				$sql2 -> db_Select("user", "user_name", "user_id='".$news['admin_id']."' ");
				list($news['admin_name']) = $sql2 -> db_Fetch();
				cachevars($news['admin_id'], $news['admin_name']);
			}
			$sql2 -> db_Select("news_category", "*",  "category_id='$news_category' ");
			list($news['category_id'], $news['category_name'], $news['category_icon']) = $sql2-> db_Fetch();
			$news['comment_total'] = $sql2 -> db_Count("comments", "(*)",  "WHERE comment_item_id='".$news['news_id']."' AND comment_type='0' ");
			$ix -> render_newsitem($news);
		}
	}
}

if(!$disablecache && !e_QUERY){
	$cache = $aj -> formtpa(ob_get_contents(), "admin");

	set_cache("news.php", $cache);
	
	
}else{
	$sql -> db_Delete("cache", "cache_url='news.php' ");
}
require_once(e_HANDLER."np_class.php");
$ix = new nextprev("news.php", $from, ITEMVIEW, $news_total, LAN_84);

if($pref['nfp_display'] == 2){
	require_once(e_PLUGIN."newforumposts_main/newforumposts_main.php");
}

require_once(FOOTERF);
?>