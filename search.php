<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	search.php V53b2
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


$_POST['searchquery'] = trim(chop($_POST['searchquery']));

if(IsSet($_POST['searchquery']) && $_POST['searchtype'] == "0"){ header("location:http://www.google.com/search?q=".stripslashes(str_replace(" ", "+", $_POST['searchquery']))); exit; }

require_once(HEADERF);

$search_info=array();

//load all core search routines
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_news.php', 'qtype' => LAN_98,'refpage' => 'news.php');
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_comment.php','qtype' => LAN_99,'refpage' => 'comment.php');
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_article.php', 'qtype' => LAN_100);
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_review.php', 'qtype' => LAN_190);
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_content.php', 'qtype' => LAN_191);
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_chatbox.php', 'qtype' => LAN_101,'refpage' => 'chatbox.php');
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_links.php', 'qtype' => LAN_102, 'refpage' => 'links.php');
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_forum.php', 'qtype' => LAN_103, 'refpage' => 'forum.php');
$search_info[]=array( 'sfile' => e_HANDLER.'search/search_user.php', 'qtype' => LAN_140, 'refpage' => 'user.php');


//load all plugin search routines
$handle=opendir(e_PLUGIN);
while(false !== ($file = readdir($handle))){
	if($file != "." && $file != ".." && is_dir(e_PLUGIN.$file)){
		$plugin_handle=opendir(e_PLUGIN.$file."/");
		while(false !== ($file2 = readdir($plugin_handle))){
			if($file2 == "e_search.php"){
				require_once(e_PLUGIN.$file."/".$file2);
			}
		}
	}
}

$search_info[99]=array( 'sfile' => '','qtype' => LAN_192);
						
$con=new convert;
if(!$refpage = substr($_SERVER['HTTP_REFERER'], (strrpos($_SERVER['HTTP_REFERER'], "/")+1))){ $refpage = "index.php"; }

if(IsSet($_POST['searchquery']) && $_POST['searchquery'] != ""){ $query = $_POST['searchquery']; }

if($_POST['searchtype']){
	$searchtype = $_POST['searchtype'];
}else{
	foreach($search_info as $key=>$si){
		if($si['refpage']){
			if(eregi($si['refpage'], $refpage)){ $searchtype = $key; }
		}
	}
	if(eregi("article.php", $refpage)){
		preg_match("/\?(.*?)\./", $refpage, $result);
		$sql -> db_Select("content", "*", "content_id='".$result[1]."'");
		$row = $sql -> db_Fetch(); extract($row);
		if($content_type == 0){ $searchtype = 3; }
		if($content_type == 3){ $searchtype = 4; }
		if($content_type == 1){ $searchtype = 5; }
	}
	if(!$searchtype){ $searchtype = 1; }
}

$text = "<div style='text-align:center'><form method='post' action='".e_SELF."'>
<p>
Search for <input class='tbox' type='text' name='searchquery' size='20' value='$query' maxlength='50' />
&nbsp;in <select name='searchtype' class='tbox'>";

foreach($search_info as $key => $si){
	($searchtype==$key) ? $sel=" selected" : $sel="";
	$text.="<option value='{$key}' {$sel}>{$si['qtype']}</option>\n";
}

$text .= "
<option value='0'>Google</option>
</select>
<input class='button' type='submit' name='searchsubmit' value='".LAN_180."' />
</p>
</form></div>";

$ns -> tablerender("Search ".SITENAME, $text);

// only search when a query is filled.
if($_POST['searchquery'] && $searchtype != 99){
	
	unset($text);
	require_once($search_info[$searchtype]['sfile']);
	$ns -> tablerender(LAN_195." ".$search_info[$searchtype]['qtype']." :: ".LAN_196.": ".$results, $text);
}else if($searchtype == 99 && $_POST['searchquery']){
	foreach ($search_info as $key => $si){
		if($key != 99){
			unset($text);
			if(file_exists($search_info[$key]['sfile'])){
				@require_once($search_info[$key]['sfile']);
				$ns -> tablerender(LAN_195." ".$search_info[$key]['qtype']." :: ".LAN_196.": ".$results, $text);
			}
		}
	}
}

function parsesearch($text, $match){
	$text = strip_tags($text);
	$temp = stristr($text,$match);
	$pos = strlen($text)-strlen($temp);
	if($pos < 70){
		$text = "...".substr($text, 0, 100)."...";
	}else{
		$text = "...".substr($text, ($pos-70), 140)."...";
	}
	$text = eregi_replace($match, "<u><b>$match</b></u>", $text);	
	return($text);
}

require_once(FOOTERF);
?>