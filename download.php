<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/e107.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("class2.php");
require_once(HEADERF);
unset($text);
if(!e_QUERY){
	// no qs - render categories ...

	if(!$sql -> db_Select("download_category", "*", "download_category_parent='0' ")){
		$ns -> tablerender(LAN_dl_18, "<div style='text-align:center'>".LAN_dl_2."</div>");
		require_once(FOOTERF);
		exit;
	}else{

		$text = "<div style='text-align:center'>
		<table style='width:95%' class='fborder'>
		<td style='width:3%; text-align:center' class='fcaption'>&nbsp;</td>
		<td style='width:60%; text-align:center' class='fcaption'>".LAN_dl_19."</td>
		<td style='width:10%; text-align:center' class='fcaption'>".LAN_dl_20."</td>
		<td style='width:17%; text-align:center' class='fcaption'>".LAN_dl_21."</td>
		<td style='width:10%; text-align:center' class='fcaption'>".LAN_dl_18."</td>
		</tr>";

		$sql2 = new db; $sql3 = new db;
		while($row = $sql-> db_Fetch()){
		extract($row);

		if(($download_category_class && check_class($download_category_class)) || !$download_category_class || ($download_category_class == 254 && USER) ){
			
				$text .= "<tr><td colspan='5' class='forumheader'>".
				($download_category_icon ? "<img src='".THEME."images/".$download_category_icon."' alt='' style='float-left' />" : "&nbsp;")."
				".$download_category_name."</b></td></tr>";
				$parent_status == "open";

		}else{
			$parent_status == "closed";
		}

		$categories = $sql2 -> db_Select("download_category", "*", "download_category_parent='".$download_category_id."' ");
		if(!$categories){
			$text .= "<tr><td colspan='5' class='forumheader3' style='text-align:center'>".LAN_dl_3."</td></tr>";
		}else{
			while($row = $sql2-> db_Fetch()){
				extract($row);
				
				$total_filesize=0; $total_downloadcount=0;
				if($filecount = $sql3 -> db_Select("download", "*", "download_category='$download_category_id'")){
					while($row = $sql3 -> db_Fetch()){
						extract($row);
						$total_filesize += $download_filesize;
						$total_downloadcount += $download_requested;
					}
					$total_filesize = parsesize($total_filesize);	
				}

				$new = (USER && $sql3 -> db_Count("download", "(*)", "WHERE download_category='$download_category_id' AND download_datestamp>".USERLV) ? "<img src='".e_IMAGE."generic/new.png' alt='' />" : "");
				
				if(!$download_category_class || ($download_category_class && check_class($download_category_class))){
					$text .= "<tr><td class='forumheader3' style='text-align:center'>".
					($download_category_icon ? "<img src='".THEME."images/".$download_category_icon."' alt='' style='float-left'  />" : "&nbsp;")."</td>
					<td class='forumheader2'> $new ".($filecount ? "<a href='".e_SELF."?list.".$download_category_id."'>".$download_category_name."</a>" : $download_category_name)."<br />
					 <span class='smalltext'>".$download_category_description ."</span></td>
					<td class='forumheader3' style='text-align:center'>".$filecount."</td>
					<td class='forumheader3' style='text-align:center'>".$total_filesize."</td>
					<td class='forumheader3' style='text-align:center'>".$total_downloadcount."
					</td></tr>";
					
				}
			}
		}
	}
	$text .= "
	</table><br />
	<img src='".e_IMAGE."generic/new.png' alt='' /> ".LAN_dl_36."
	</div>";
	$ns -> tablerender(LAN_dl_18.$type, $text);
	require_once(FOOTERF);
	exit;
}
}

$tmp = explode(".", e_QUERY);
if(is_numeric($tmp[0])){
	$from = $tmp[0];
	$action = $tmp[1];
	$id = $tmp[2];
	$view = $tmp[3];
	$order = $tmp[4];
	$sort = $tmp[5];
}else{
	$action = $tmp[0];
	$id = $tmp[1];
}


if($action == "list"){

	if(IsSet($_POST['view'])){
		extract($_POST);
	}

	if(!$from) $from=0;
	if(!$order) $order = "download_datestamp";
	if(!$sort) $sort = "DESC";
	if(!$view) $view = 10;

	$total_downloads = $sql -> db_Select("download", "*", "download_category='$id' AND download_active='1'");
	if(!$total_downloads){ require_once(FOOTERF); exit; }


	$sql -> db_Select("download_category", "*", "download_category_id='$id'");
	$row = $sql -> db_Fetch(); extract($row);
	$core_total = $sql -> db_Count("download WHERE download_category='$id' AND download_active=1");
	$type = $download_category_name." [ ".$download_category_description." ]";


	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?".e_QUERY."'>
	<table style='width:95%' class='fborder'>
	<tr>
	<td colspan='7' style='text-align:center' class='forumheader'>
	<span class='defaulttext'>View</span> 
	<select name='view' class='tbox'>".
	($view == 5 ? "<option selected>5</option>" : "<option>5</option>").
	($view == 10 ? "<option selected>10</option>" : "<option>10</option>").
	($view == 15 ? "<option selected>15</option>" : "<option>15</option>").
	($view == 20 ? "<option selected>20</option>" : "<option>20</option>")."
	</select>
	&nbsp;
	<span class='defaulttext'>Order by</span>  
	<select name='order' class='tbox'>".
	($order == "download_datestamp" ? "<option value='download_datestamp' selected>".LAN_dl_22."</option>" : "<option value='download_datestamp'>".LAN_dl_22."</option>").
	($order == "download_requested" ? "<option value='download_requested' selected>".LAN_dl_18."</option>" : "<option value='download_requested'>".LAN_dl_18."</option>").
	($order == "download_name" ? "<option value='download_name' selected>".LAN_dl_23."</option>" : "<option value='download_name'>Filename</option>").
	($order == "download_author" ? "<option value='download_author' selected>".LAN_dl_24."</option>" : "<option value='download_author'>".LAN_dl_24."</option>")."
	</select>
	&nbsp;
	<span class='defaulttext'>Sort</span> 
	<select name='sort' class='tbox'>".
	($sort == "ASC" ? "<option value='ASC' selected>".LAN_dl_25."</option>" : "<option value='ASC'>".LAN_dl_25."</option>").
	($sort == "DESC" ? "<option value='DESC' selected>".LAN_dl_26."</option>" : "<option value='DESC'>".LAN_dl_26."</option>")."
	</select>
	&nbsp;
	<input class='button' type='submit' name='goorder' value='".LAN_dl_27."' />
	</td></tr>
	<tr>
	<td style='width:35%; text-align:center' class='fcaption'>".LAN_dl_28."</td>
	<td style='width:15%; text-align:center' class='fcaption'>".LAN_dl_22."</td>
	<td style='width:20%; text-align:center' class='fcaption'>".LAN_dl_24."</td>
	<td style='width:10%; text-align:center' class='fcaption'>".LAN_dl_21."</td>
	<td style='width:5%; text-align:center' class='fcaption'>".LAN_dl_29."</td>
	<td style='width:10%; text-align:center' class='fcaption'>".LAN_dl_12."</td>
	<td style='width:5%; text-align:center' class='fcaption'>".LAN_dl_8."</td>
	</tr>";
	
	$gen = new convert;
	require_once(e_HANDLER."rate_class.php");
	$rater = new rater;
	$sql2 = new db;
	
	$filetotal = $sql -> db_Select("download", "*", "download_category='$id' AND download_active='1' ORDER BY $order $sort LIMIT $from, $view");
	$ft = ($filetotal < $view ? $filetotal : $view);
	while($row = $sql -> db_Fetch()){
		extract($row);

		$new = (USER && $download_datestamp>USERLV ? "<img src='".e_IMAGE."generic/new.png' alt='' style='float:left' />" : "");
		
		$datestamp = $gen->convert_date($download_datestamp, "short");
		$download_filesize = parsesize($download_filesize);
		$ratearray = $rater -> getrating("download", $download_id);
		if(!$ratearray[0]){
			$rating = LAN_dl_13;
		}else{
			$rating = ($ratearray[2] ? $ratearray[1].".".$ratearray[2]."/".$ratearray[0] : $ratearray[1]."/".$ratearray[0]);
		}

		$text .= "<tr>
		<td class='forumheader3' style='vertical-align:middle'><span class='smalltext'> $new <b><a href='".e_SELF."?view.$download_id'>$download_name</a></b></span></td>
		<td style='text-align:center' class='forumheader3'><span class='smalltext'>$datestamp</span></td>
		<td style='text-align:center' class='forumheader3'><span class='smalltext'>$download_author</span></td>
		<td style='text-align:center' class='forumheader3'><span class='smalltext'>$download_filesize</span></td>
		<td style='text-align:center' class='forumheader3'><span class='smalltext'>$download_requested</span></td>
		<td style='text-align:center' class='forumheader3'><span class='smalltext'>$rating</span></td>
		<td style='text-align:center' class='forumheader3'><a href='request.php?".$download_id."'><img src='".e_IMAGE."generic/download.png' alt='' style='border:0' /></a></td>
		</tr>";

		$tdownloads += $download_requested;
	}
	
	$text .= "</table><form><br /><span class='smalltext'>$tdownloads ".LAN_dl_16." $ft ".LAN_dl_17."</span><br />
	</div>";
	$ns -> tablerender($type, $text);

	echo "<div style='text-align:center'><span style='width:200px'><div class='nextprev'><a href='".e_SELF."'>".LAN_dl_9."</a></div></span></div>";

	require_once(e_HANDLER."np_class.php");
	$ix = new nextprev("download.php", $from, $view, $total_downloads, "Downloads", "list.".$id.".".$view.".".$order.".".$sort);
	require_once(FOOTERF);
	exit;
}


// options -------------------------------------------------------------------------------------------------------------------------------------------------------------------

if($action == "view"){

	require_once(e_HANDLER."rate_class.php");
	$gen = new convert;
	$rater = new rater;
	$aj = new textparse;
	$sql2 = new db;
	

	if(!$sql -> db_Select("download", "*", "download_id='$id'")){
		require_once(FOOTERF);
		exit;
	}

	$row = $sql -> db_Fetch(); extract($row);

	$sql2 -> db_Select("download_category", "*", "download_category_id='$download_category'");
	$row = $sql2 -> db_Fetch(); extract($row);
	$type = $download_category_name." [ ".$download_category_description." ]";

	$text = "<div style='text-align:center'>
	<table style='width:95%' class='fborder'>
	<tr>
	<td colspan='2' style='text-align:center' class='forumheader'><b>$download_name</b></td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_24.": </td>
	<td style='width:80%' class='forumheader3'>$download_author</td>
	</tr>";

	if($download_author_email){

		$text .= "<tr>
		<td style='width:20%' class='forumheader3'>".LAN_dl_30.": </td>
		<td style='width:80%' class='forumheader3'>".str_replace("@", ".at.", $download_author_email)."</td>
		</tr>";
	}

	if($download_author_website){
		$text .= "<tr>
		<td style='width:20%' class='forumheader3'>".LAN_dl_31.": </td>
		<td style='width:80%' class='forumheader3'>$download_author_website</td>
		</tr>";
	}

	$text .= "<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_7.": </td>
	<td style='width:80%' class='forumheader3'>".$aj -> tpa($download_description)."</td>
	</tr>";

	if($download_thumb){
		$text .= "<tr>
		<td style='width:20%' class='forumheader3'>Image: </td>
		<td style='width:80%' class='forumheader3'>".
		($download_image ? "<a href='".e_FILE."downloadimages/$download_image'><img src='".e_FILE."downloadthumbs/$download_thumb' alt='' style='border:0' /></a>" : "<img src='".e_FILE."downloadthumbs/$download_thumb' alt='' />")."
		</td>
		</tr>";
	}else if($download_image){
		$text .= "<tr>
		<td style='width:20%' class='forumheader3'>".LAN_dl_11.": </td>
		<td style='width:80%' class='forumheader3'>
		<a href='".e_BASE."request.php?download.".$download_id."'>Click here for screenshot</a>
		</td>
		</tr>";
	}

	$text .= "<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_10.": </td>
	<td style='width:80%' class='forumheader3'>".parsesize($download_filesize)."</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_18.": </td>
	<td style='width:80%' class='forumheader3'>$download_requested</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_32.": </td>
	<td style='width:80%' class='forumheader3'><a href='request.php?".$download_id."'><img src='".e_IMAGE."generic/download.png' alt='' style='border:0' /></a></td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".LAN_dl_12.": </td>
	<td style='width:80%' class='forumheader3'>

	<table style='width:100%'>
	<tr>
	<td style='width:50%'>";
	
	if($ratearray = $rater -> getrating("download", $download_id)){
		for($c=1; $c<= $ratearray[1]; $c++){
			$text .= "<img src='".e_IMAGE."rate/star.png' alt=''>";
		}
		if($ratearray[2]){
			$text .= "<img src='".e_IMAGE."rate/".$ratearray[2].".png'  alt=''>";
		}
		if($ratearray[2] == ""){ $ratearray[2] = 0; }
			$text .= "&nbsp;".$ratearray[1].".".$ratearray[2]." - ".$ratearray[0]."&nbsp;";
			$text .= ($ratearray[0] == 1 ? "vote" : "votes");
		}else{
			$text .= LAN_dl_13;
		}

		$text .= "</td><td style='width:50%; text-align:right'>";

		if(!$rater -> checkrated("download", $download_id) && USER){
			$text .= $rater -> rateselect("&nbsp;&nbsp;&nbsp;&nbsp; <b>".LAN_dl_14, "download", $download_id)."</b>";
		}else if(!USER){
			$text .= "&nbsp;";
		}else{
			$text .= LAN_dl_15;
		}
	
	$text .= "</td></tr></table></td></tr>";

	$dl_id = $download_id;
	if($sql -> db_Select("download", "*", "download_category='$download_category_id' AND download_id<$dl_id ORDER BY download_datestamp DESC")){
		$row = $sql -> db_Fetch(); extract($row);
		$prev = "<div class='nextprev'><a href='".e_SELF."?view.$download_id'><< ".LAN_dl_33." [$download_name]</a></div>";
	}else{
		$prev = "&nbsp;";
	}
	if($sql -> db_Select("download", "*", "download_category='$download_category_id' AND download_id>$dl_id ORDER BY download_datestamp ASC")){
		$row = $sql -> db_Fetch(); extract($row);
		$next = "<div class='nextprev'><a href='".e_SELF."?view.$download_id'>[$download_name] ".LAN_dl_34." >></a></div>";
	}else{
		$next = "&nbsp;";
	}


	if($prev || $next){
		$text .= "<tr><td colspan='2'>
		<table style='width:100%'>
		<tr>
		<td style='width:40%'>$prev</td>
		<td style='width:20%'><div class='nextprev'><a href='".e_SELF."?list.$download_category'>".LAN_dl_35."</a></div></td>
		<td style='width:40%'>$next</td>
		</tr>
		</table>
		</td></tr>";
	}

	$text .= "
	</table>";

	$ns -> tablerender($type, $text);
	require_once(FOOTERF);
}

//$ns -> tablerender(LAN_dl_18, LAN_dl_2);
//require_once(FOOTERF);

function parsesize($size){
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if($size < $kb) {
		return $size." b";
	}else if($size < $mb) {
		return round($size/$kb,2)." kb";
	}else if($size < $gb) {
		return round($size/$mb,2)." mb";
	}else if($size < $tb) {
		return round($size/$gb,2)." gb";
	}else {
		return round($size/$tb,2)." tb";
	}
}
?>