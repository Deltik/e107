<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/ypslide_menu.php
|
|	Based on original javascript code from http://youngpup.net, converted in PHP and merged in e107 by Jalist, js and PHP features enhanced by Lolo Irie
|		used with permission
|
|	©Steve Dunstan 2001-2002 / ©Lolo Irie 2004
|	http://e107.org - http://touchatou.org
|	jalist@e107.org - lolo_irie@e107coders.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
define("DEBUGYP",FALSE);
$ver_yp = "1.02";

global $pref;
$sql = new db;
$sql2 = new db;
//$sql3 = new db;
//$menus = ($sql->db_Count("links", "(*)", "WHERE link_category='1' AND link_name REGEXP('submenu') ORDER BY link_order"));
$menus =$sql->db_Select("links", "*", "link_category='1' AND link_name REGEXP('submenu') ORDER BY link_order");
if(DEBUGYP==TRUE){echo "<br />BETA RELEASE : ".$ver_yp;}
if(DEBUGYP==TRUE){echo "<br />menus : ".$menus;}

$menus2 =$sql -> db_Select("links", "*", "link_category='1' AND link_name NOT REGEXP('submenu') ORDER BY link_order");
if(DEBUGYP==TRUE){echo "<br />count main links with submenus : ".$menus."<br />count all main links : ".$menus2;}
($menus<$menus2?$menus=$menus2:"");

// Get default preferences (CORE Prefs)
if($pref['ypmenu_pos']==1||$pref['ypmenu_pos']==2){
	// Get preferences for other themes
	if(USERTHEME != FALSE && USERTHEME != "USERTHEME" && USERTHEME != $pref['sitetheme'] && $sql2 -> db_Select("ypslide_cfsaved","ypslide_cfsaved_value","ypslide_cfsaved_name='".USERTHEME."'")){
		$row3 = $sql2 -> db_Fetch();
		$yptmp = stripslashes($row3[0]);
		$ypmenu_pref=unserialize($yptmp);
		$pref['ypmenu_pos']=$ypmenu_pref['ypmenu_pos'];
		$pref['ypmenu_posx']=$ypmenu_pref['ypmenu_posx'];
		$pref['ypmenu_posy']=$ypmenu_pref['ypmenu_posy'];
		$pref['ypmenu_slidedir']=$ypmenu_pref['ypmenu_slidedir'];
		$pref['ypmenu_subwidth']=$ypmenu_pref['ypmenu_subwidth'];
		$pref['ypmenu_totalwidth']=$ypmenu_pref['ypmenu_totalwidth'];
		$pref['ypmenu_confpro']=$ypmenu_pref['ypmenu_confpro'];
		$pref['ypmenu_aspect']=$ypmenu_pref['ypmenu_aspect'];
		$pref['ypmenu_subpos']=$ypmenu_pref['ypmenu_subpos'];
		$pref['ypmenu_subposx']=$ypmenu_pref['ypmenu_subposx'];
		$pref['ypmenu_subposy']=$ypmenu_pref['ypmenu_subposy'];
	}
	$menu_pos = $pref['ypmenu_pos'];
	$menu_width = $pref['ypmenu_subwidth']+0;
	$x_pos = $pref['ypmenu_posx']+0;
	$y_pos = $pref['ypmenu_posy']+0;
	$x_subpos = $pref['ypmenu_subposx']+0;
	$y_subpos = $pref['ypmenu_subposy']+0;
	$slide = $pref['ypmenu_slidedir'];
	$totalwidth = $pref['ypmenu_totalwidth']+0;
	($pref['ypmenu_pos']==1?$postype = 'absolute':$postype = 'relative');
	$conf_pro = $pref['ypmenu_confpro'];
	$stylemenu = $pref['ypmenu_aspect'];
	$ico_sub = $ypmenu_pref['mb_icosub'];
	($pref['ypmenu_subpos']==2?$subpostype = 'relative':$subpostype = 'absolute');
	if(DEBUGYP==TRUE){echo "<br />Configured !";}
}else{
	// Not yet configured from admin area
	$menu_pos = 2;
	$menu_width = 200;
	$x_pos = 0;
	$y_pos = 0;
	$x_subpos = 5;
	$y_subpos = 18;
	$slide = "down";
	$postype = 'absolute';
	$totalwidth = 800;
	$conf_pro = 0;
	$stylemenu = 1;
	$ico_sub = "";
	$subpostype = 'relative';
	if(DEBUGYP==TRUE){echo "<br />Not yet configured !";}
}
$menu_height =  200;

// Get advanced preferences (OTHER Prefs)
if($conf_pro==1){
		$sql2->db_Select("core", "*", "e107_name='ypslide_pref' ");
		$row = $sql2 -> db_Fetch();
		$tmpyp = stripslashes($row['e107_value']);
		$ypmenu_pref=unserialize($tmpyp);
		/*echo "<br>".$postype;
		echo "<br>".$slide;
		echo "<br>".$x_pos;*/
		if(DEBUGYP==TRUE){echo "<br />Conf pro =1";}
}else{
	 require_once(e_PLUGIN."ypslide_menu/def_pref.php");
	 if(DEBUGYP==TRUE){echo "<br />Default prefs";}  
}

if($postype == 'relative' && $subpostype == 'absolute'){
echo "<div id=\"bidon\" style=\"position: absolute;\" ></div>";}

echo "<script type='text/javascript' src='".e_PLUGIN."ypslide_menu/ypSlideOutMenusC.js'></script>
<script type='text/javascript'>

var menus = [";
for($a=1; $a<=$menus; $a++){
	$menustr .= "\nnew ypSlideOutMenu(\"menu".$a."\", \"".$slide."\", ".floor($x_pos+($menu_width*$a-1)/2).", ".$y_pos.", ".$menu_width.", ".$menu_height."),";
}
$menustr = substr($menustr, 0, -1);
echo $menustr."]\n";

?>	
	for (var i = 0; i < menus.length; i++) {
		menus[i].onactivate = new Function("document.getElementById('act" + (i+1) + "').className='active';");
		menus[i].ondeactivate = new Function("document.getElementById('act" + (i+1) + "').className='';");
	}
</script>

<style type="text/css">
<?php

echo "
#menubar {\n";
if($postype=="absolute"){echo "	position:".$postype.";";
}
echo "
	top: ".$y_pos."px;
	left: ".$x_pos."px;
	background-color:transparent;
	padding:2px;
	width: ".$totalwidth."px;
	line-height: 18px;
	text-align: ".$ypmenu_pref['mb_textalign'].";
	".$ypmenu_pref['mb_custom']."
}

#menubar a {
	color:".$ypmenu_pref['mb_colorlink'].";
	letter-spacing:1px;
	".(strlen($ypmenu_pref['mb_backimg'])>4?"background-image : url(".$ypmenu_pref['mb_backimg'].");":"")."
	border:".$ypmenu_pref['mb_border'].";
	padding-left:5px;
	padding-right:5px;
	padding-bottom:1px;
	text-decoration:".$ypmenu_pref['mb_textdeco'].";
	background-color:".$ypmenu_pref['mb_backcol'].";
	font-family :".$ypmenu_pref['mb_fontfamily'].";
	font-size :".$ypmenu_pref['mb_fontsize']."px;
	white-space: nowrap;
}

.menu .options {
	margin-right:1px;
	margin-bottom:1px;
	border:".$ypmenu_pref['links_border'].";
	background-color:".$ypmenu_pref['links_backcol'].";
	".(strlen($ypmenu_pref['links_backimg'])>4?"background-image : url(".$ypmenu_pref['links_backimg'].");":"")."
	font-family :".$ypmenu_pref['links_fontfamily'].";
	font-size :".$ypmenu_pref['links_fontsize']."px;
	".$ypmenu_pref['links_custom']."
}

.menu a {
	color:".$ypmenu_pref['links_colorlinks'].";
	display:block;
	padding:2px 4px;
	text-decoration:".$ypmenu_pref['links_textdeco'].";
	background-color:transparent;
}

.menu a:hover {
	background-color:".$ypmenu_pref['linksa_backcol'].";
	color:".$ypmenu_pref['linksa_colorlink'].";
	".(strlen($ypmenu_pref['linksa_backimg'])>4?"background-image : url(".$ypmenu_pref['linksa_backimg'].");":"")."
	".$ypmenu_pref['linksa_custom']."
}

	
#menubar a.active {
	border-bottom-color:#000040;
	border-right-color:#000040;
	border-left-color:#000040;
	border-top-color:#000040;
	background-color:".$ypmenu_pref['mba_backcol'].";
	color:".$ypmenu_pref['mba_colorlink'].";
	".(strlen($ypmenu_pref['mba_backimg'])>4?"background-image : url(".$ypmenu_pref['mba_backimg'].");":"")."
	".$ypmenu_pref['mba_custom']."
}

</style>\n\n";

echo "<div id='menubar'>\n";
$count = 0;
while($row = $sql -> db_Fetch()){
	// get top level menu items ...
	extract($row);
	if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
		$link[$count]['url'] = $link_url;
		$link[$count]['name'] = $link_name;
		if($link_button){$link[$count]['ico'] = $link_button;}else{$link[$count]['ico'] = "";}
		$link_name=strip_tags($link_name);
		if($sql2 -> db_Select("links", "*", "link_name REGEXP('submenu.".$link_name."') ORDER BY link_order")){
			while($row = $sql2 -> db_Fetch()){
				//	get sublevel menu items ...
				extract($row);
				if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
					$link[$count]['sublink'] .= $link_url."^".$link_name."^";
				}
			}
		}else{
			$link[$count]['sublink'] = "";
		}
		$count++;
	}
}

$count = 0;
if(ADMIN==TRUE){
	echo "<a href='".e_ADMIN."admin.php' onmouseover=\"this.className='active';\" onmouseout=\"this.className='';\" >Administration</a>\n";
	if($stylemenu==1){echo "<br />";}
}
//echo "XX".$subpostype;
while(list($key, $linkid) = each($link)){
	$numberlink++;
	extract($linkid);
	$countid = "act".($count+1);
	$menuid = "menu".($count+1);
	$container = $menuid."Container";
	$content = $menuid."Content";
	// Check for relative path
	if(strpos($url,"tp:")===FALSE){$url = SITEURL.$url;}
	
	if(!$sublink){
		if($ico){echo "<img src='".e_IMAGE."link_icons/$ico' alt='' style='vertical-align:middle; margin: 5 0 5 0;' /> ";}
		echo "<a href='$url' id='".$countid."' onmouseover=\"this.className='active';\" onmouseout=\"this.className='';\" >$name</a>\n";
		if($stylemenu==1){echo "<br />";}
		$sltext .= "<div id='".$container."' >\n
		<div id='$content' class='menu' >\n
		<div class='options' >\n
		</div>\n
		</div>\n
		</div>\n\n";
	}else{
		if($ico){echo "<img src='".e_IMAGE."link_icons/$ico' alt='' style='vertical-align:middle; margin: 5 0 5 0;' /> ";}
		echo "<a id='$countid' href='$url' onmouseover=\"ypSlideOutMenu.showMenu('".$menuid."','".$subpostype."',event,this.id,'".$menu_width."','".$menu_height."','$menu_pos',$x_subpos,$y_subpos)\" onmouseout=\"ypSlideOutMenu.hideMenu('$menuid')\">$name".(strlen($ypmenu_pref['mb_icosub'])>4 ? " <img style=\"vertical-align: middle; border: 0;\" src=\"".$ypmenu_pref['mb_icosub']."\" />":"")."</a> \n";
		if($stylemenu==1){echo "<br />";}
		

		$sltext .= "<div id='".$container."' ";
		if($menu_pos==2){$sltext .= "style='position: absolute;' ";}
		$sltext .= ">
	<div id='$content' class='menu' >
		<div class='options' >\n";

		$tmp = explode("^", $sublink);

		$lname = str_replace("submenu.".$name.".", "", $tmp[($count3+1)]);

		$count3 = 0;
		while($tmp[$count3]){
			//$sltext .= "<a href='".$tmp[$count3]."'>$lname</a>\n";
			$linkname = str_replace("submenu.".$name.".", "", $tmp[($count3+1)]);
			$sltext .= "<a href='".$tmp[$count3]."'>".$linkname."</a>\n";
			$count3 = $count3+2;
		}
		$sltext .= "		</div>

	</div>
</div>\n\n";
	}
	$count++;
}


echo "</div>\n\n".$sltext;

// Check for mention to author
if(strpos(SITEDISCLAIMER,'youngpup.net')===FALSE){
	$rdmnbr = rand(0,50);
	if($rdmnbr==2){
		echo "\n<script type=\"text/javascript\" >\n
		alert('This Webmaster is using a javascript from http://youngpup.net which require a mention to the original author but he didn\\'t install this mention like mentionned !..\\n\\nPlease ask him to respect original author rights and this alert box will stop to disturb you.');\n
		</script>\n";
	}
}

?>