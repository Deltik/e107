<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	YPSlideMenu by Youngpup.net (original code)/ Jalist (Convert for e107)/ Lisa (Dutch file and support) and Lolo Irie (Javascript and PHP fix, plugins features)
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once("../../class2.php");
global $PLUGINS_DIRECTORY;
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit; }
$lan_file = e_PLUGIN."ypslide_menu/languages/admin/".e_LANGUAGE.".php";
@include(file_exists($lan_file) ? $lan_file : e_PLUGIN."ypslide_menu/languages/admin/English.php");

require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;
$message2 = "";
// Load existing configs
if(isset($_POST['ypslide_loadconfig_submit'])){
	$sql->db_Select("ypslide_cfsaved","*","ypslide_cfsaved_name='".$_POST['ypslide_loadconfig']."'");
	$row = $sql->db_Fetch();
	$yptmp = stripslashes($row['ypslide_cfsaved_value']);
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
	unset($ypmenu_pref['ypmenu_pos'],$ypmenu_pref['ypmenu_posx'],$ypmenu_pref['ypmenu_posy'],$ypmenu_pref['ypmenu_slidedir'],$ypmenu_pref['ypmenu_subwidth'],$ypmenu_pref['ypmenu_totalwidth'],$ypmenu_pref['ypmenu_confpro'],$ypmenu_pref['ypmenu_aspect'],$ypmenu_pref['ypmenu_subpos'],$ypmenu_pref['ypmenu_subposx'],$ypmenu_pref['ypmenu_subposy']);
	$ypconf_loaded = 1;
	save_prefs();
	$tmp = addslashes(serialize($ypmenu_pref));
	$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='ypslide_pref'");
	$message = ypslide_LAN64;
}

// Delete a config
if($_POST['ypslide_deleteconfirm']==2){
	$sql->db_Delete("ypslide_cfsaved","ypslide_cfsaved_name='".$_POST['ypslide_loadconfig']."'");
	$message2 = ypslide_LAN65." ".$_POST['ypslide_loadconfig'];
}else if($_POST['ypslide_deleteconfirm']==-1){
	$message2 = ypslide_LAN74;
}

// Save existing configs
if(isset($_POST['ypslide_saveconfig_submit'])&&$_POST['ypslide_saveconfig']!=""){
	$sql->db_Select("core", "*", "e107_name='ypslide_pref' ");
	$row = $sql -> db_Fetch();
	$tmpyp = stripslashes($row['e107_value']);
	$ypmenu_pref=unserialize($tmpyp);
	
	$ypmenu_pref['ypmenu_pos']=$pref['ypmenu_pos'];
	$ypmenu_pref['ypmenu_posx']=$pref['ypmenu_posx'];
	$ypmenu_pref['ypmenu_posy']=$pref['ypmenu_posy'];
	$ypmenu_pref['ypmenu_slidedir']=$pref['ypmenu_slidedir'];
	$ypmenu_pref['ypmenu_subwidth']=$pref['ypmenu_subwidth'];
	$ypmenu_pref['ypmenu_totalwidth']=$pref['ypmenu_totalwidth'];
	$ypmenu_pref['ypmenu_confpro']=$pref['ypmenu_confpro'];
	$ypmenu_pref['ypmenu_aspect']=$pref['ypmenu_aspect'];
	$ypmenu_pref['ypmenu_subpos']=$pref['ypmenu_subpos'];
	$ypmenu_pref['ypmenu_subposx']=$pref['ypmenu_subposx'];
	$ypmenu_pref['ypmenu_subposy']=$pref['ypmenu_subposy'];

	$tmp = addslashes(serialize($ypmenu_pref));
	
	if(!$sql->db_Select("ypslide_cfsaved","ypslide_cfsaved_name","ypslide_cfsaved_name='".$_POST['ypslide_saveconfig']."'")){
		$sql->db_Insert("ypslide_cfsaved","'".$_POST['ypslide_saveconfig']."', '".$tmp."'");
		$message = ypslide_LAN63;
	}else{
		$sql->db_Update("ypslide_cfsaved","ypslide_cfsaved_value='".$tmp."' WHERE ypslide_cfsaved_name='".$_POST['ypslide_loadconfig']."'");
		$message = ypslide_LAN71;
	}
	unset($ypslide_saveconfig);
}


// Update pref if subscribe to Touchatou
if($_POST['refer_plug_lolo']=="Touchatou" && $ypconf_loaded != 1){
	$pref['ypslide_touchatou']=2;
	save_prefs();
	$caption = SUB_TOUCHATOU_8;
	$text = SUB_TOUCHATOU_7;
	$ns->tablerender($caption,$text);
}

// Get existing configs
if(isset($_POST['ypmenuslidedir'])&&$_POST['ypmenuslidedir']!="" && $ypconf_loaded != 1){
	
	//+++ Design Prefs
	$ypmenu_pref = array();
	//require_once(e_PLUGIN."ypslide_menu/def_pref.php");
	
	// Mainbar
	$ypmenu_pref['mb_colorlink'] = $_POST['mbcolorlink'];
	$ypmenu_pref['mb_backimg'] = $_POST['mbbackimg'];
	$ypmenu_pref['mb_border'] = $_POST['mbborder'];
	$ypmenu_pref['mb_textdeco'] = $_POST['mbtextdeco'];
	$ypmenu_pref['mb_backcol'] = $_POST['mbbackcol'];
	$ypmenu_pref['mb_fontsize'] = $_POST['mbfontsize'];
	$ypmenu_pref['mb_fontfamily'] = $_POST['mbfontfamily'];
	$ypmenu_pref['mb_textalign'] = $_POST['mbtextalign'];
	$ypmenu_pref['mb_icosub'] = $_POST['mbicosub'];
	$ypmenu_pref['mb_custom'] = $_POST['mbcustom'];
	
	// Mainbar activated links
	$ypmenu_pref['mba_backcol'] = $_POST['mbabackcol'];
	$ypmenu_pref['mba_colorlink'] = $_POST['mbacolorlink'];
	$ypmenu_pref['mba_backimg'] = $_POST['mbabackimg'];
	$ypmenu_pref['mba_custom'] = $_POST['mbacustom'];
	
	// Sublinks
	$ypmenu_pref['links_backcol'] = $_POST['linksbackcol'];
	$ypmenu_pref['links_backimg'] = $_POST['linksbackimg'];
	$ypmenu_pref['links_border'] = $_POST['linksborder'];
	$ypmenu_pref['links_colorlinks'] = $_POST['linkscolorlinks'];
	$ypmenu_pref['links_textdeco'] = $_POST['linkstextdeco'];
	$ypmenu_pref['links_fontsize'] = $_POST['linksfontsize'];
	$ypmenu_pref['links_fontfamily'] = $_POST['linksfontfamily'];
	$ypmenu_pref['links_custom'] = $_POST['linkscustom'];
	
	// Sublinks activated
	$ypmenu_pref['linksa_backcol'] = $_POST['linksabackcol'];
	$ypmenu_pref['linksa_colorlink'] = $_POST['linksacolorlink'];
	$ypmenu_pref['linksa_backimg'] = $_POST['linksabackimg'];
	$ypmenu_pref['linksa_custom'] = $_POST['linksacustom'];
	
	$tmp = addslashes(serialize($ypmenu_pref));
	$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='ypslide_pref'");
	
	// Global prefs
	$pref['ypmenu_pos'] = $_POST['ypmenupos'];
	$pref['ypmenu_posx'] = $_POST['ypmenuposx'];
	$pref['ypmenu_posy'] = $_POST['ypmenuposy'];
	$pref['ypmenu_subpos'] = $_POST['ypmenusubpos'];
	$pref['ypmenu_subposx'] = $_POST['ypmenusubposx'];
	$pref['ypmenu_subposy'] = $_POST['ypmenusubposy'];
	$pref['ypmenu_slidedir'] = $_POST['ypmenuslidedir'];
	$pref['ypmenu_subwidth'] = $_POST['ypmenusubwidth'];
	$pref['ypmenu_totalwidth'] = $_POST['ypmenutotalwidth'];
	$pref['ypmenu_confpro'] = 1;
	$pref['ypmenu_aspect'] = $_POST['ypmenuaspect'];
	//echo "XX ".$_POST['ypmenuaspect'];
	save_prefs();
	if(($message==""||!isset($message))&&$message2==""){$message = ypslide_LAN19;}else if($message2!=""){$message=$message2;}
}

// Create advanced prefs if necessary
if($pref['ypmenu_confpro']!=1 && $ypconf_loaded!=1){
	require_once(e_PLUGIN."ypslide_menu/def_pref.php");
	$tmp = addslashes(serialize($ypmenu_pref));
	$sql->db_Insert("core", "'ypslide_pref','".$tmp."'");
	
}else if($ypconf_loaded!=1){
	$sql->db_Select("core", "*", "e107_name='ypslide_pref' ");
	$row = $sql -> db_Fetch();
	$tmpyp = stripslashes($row['e107_value']);
	$ypmenu_pref=unserialize($tmpyp);
}

// Display messages
if(isset($message)){
        $ns -> tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

echo "\n
<script type=\"text/javascript\" >\n
function chgcol(numfiecol){\n
	document.getElementById(numfiecol).value='';\n
	document.getElementById('nbrcolor').value=numfiecol;\n
	window.open('chcolor.php','CHCOLOR','scrollbars=no,toolbar=no,titlebar=no,width=280,height=180,top=250,left=300,status=no,menubar=no,location=no,resizable=yes');\n
}\n
function addtext(sc,butn){\n
	butn.value = sc;\n
}\n

</script>\n";

if(strpos(SITEDISCLAIMER,'youngpup.net')===FALSE){
	 $ns -> tablerender(ypslide_LAN38,ypslide_LAN34."<br /><b>".ypslide_LAN33."</b>");
}

// Button Update
$textbutupd = "
<tr style='vertical-align:top'>
<td colspan='2' style='text-align:center' class='forumheader'>
<input class='button' type='submit' name='submit' value='".ypslide_LAN1."' /></td>
</tr>";

//++++++ HTML Help
$caption = ypslide_LAN35;
$text = "º <a href=\"javascript:void(0);\" onclick=\"expandit(this);\" >".ypslide_LAN37."</a>\n<span style=\"display: none;\" >".ypslide_LAN36."</span>";

$ns -> tablerender($caption, $text);

//++++++ HTML Save/Load

// Get existing configs
$i=0;
if($sql->db_Select("ypslide_cfsaved","ypslide_cfsaved_name")){
	while($row = $sql->db_Fetch()){
		$ypslide_exist[$i] = $row[0];
		$i++;
	}
}
//

$text = "\n";

$caption = ypslide_LAN58;
$text .= "º <a href=\"javascript:void(0);\" onclick=\"expandit(this);\" >".ypslide_LAN44."</a>\n
<span style=\"display: none;\" >\n
<br /><br /><div style=\"text-align:center\">\n";


$text .= $rs -> form_open("post", e_SELF, "ypslide_config", "", "onsubmit=\"return insorupd();\"");

$text .= "<table style=\"width:94%\" class=\"fborder\" >\n";

if($i>0){
	// Load/Delete Config
	$text .="
	<tr>
		<td colspan=\"2\" >
		<b>".ypslide_LAN69."</b>
		</td>
	</tr>";
	
	$text .="<tr>
	<td style=\"width:40%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN61."</td>
	<td style=\"width:60%\" class=\"forumheader3\">";
	$text .= $rs -> form_select_open("ypslide_loadconfig");
	for($j=0;$j<$i;$j++){
		$text .= $rs -> form_option($ypslide_exist[$j], false, $ypslide_exist[$j]);
	}
	$text .= $rs -> form_select_close();
	$text .= " ";
	$text .= $rs -> form_button("submit", "ypslide_loadconfig_submit", ypslide_LAN62);
	$text .= " ";
	$text .= $rs -> form_checkbox("ypslide_deleteconfirm", 1, 0, ypslide_LAN73);
	$text .= $rs -> form_button("submit", "ypslide_deleteconfig_submit", ypslide_LAN67, " onclick=\"confdel();\" ");
	$text .= "<br /><b class=\"smalltext\" >".ypslide_LAN72."</b></td></tr>";
}

// Save Config
$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN68."</b>
	</td>
</tr>";

$text .="<tr>
<td style=\"width:40%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN60."</td>
<td style=\"width:60%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypslide_saveconfig",24,$ypslide_saveconfig,24);
$text .= " ";

$text .= $rs -> form_button("submit", "ypslide_saveconfig_submit", ypslide_LAN59);

$text .= "</td>\n
</tr>\n
</table>\n
</div>
</span>\n\n
<script type=\"text/javascript\" >\n
var ypcfg_exist = new Array();\n";
for($j=0;$j<$i;$j++){
	$text .= "ypcfg_exist[".$j."]=\"".$ypslide_exist[$j]."\";\n";
}
$text .= "function confdel(){\n
	if(document.getElementById('ypslide_deleteconfirm1').checked!=true){\n
		document.getElementById('ypslide_deleteconfirm1').checked=true;\n
		document.getElementById('ypslide_deleteconfirm1').value=-1;\n
	}\n
	else{\n
		document.getElementById('ypslide_deleteconfirm1').value=2;\n
	}\n
}\n
	
function insorupd(){\n
	var testfind = 0;\n
	for(j=0;j<".$i.";j++){if(document.getElementById('ypslide_saveconfig').value==ypcfg_exist[j]&&ypcfg_exist[j]!=''){testfind=1;}}\n
	if(testfind==1){\n
		 return confirm('".ypslide_LAN70."');\n
	}\n
	else{\n
	return true;\n
	}\n
}\n
</script>\n\n";

$ns -> tablerender($caption, $text);

//++++++ HTML General options
$caption = ypslide_LAN2;
$text = "º <a href=\"javascript:void(0)\" onclick=\"expandit(this)\" style=\"cursor: hand; cursor: pointer;\" >".ypslide_LAN44."</a>\n
<span style=\"display: none;\" >
<br /><br /><div style=\"text-align:center\">\n";

$text .= "
<table style=\"width:94%\" class=\"fborder\">\n
";

$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN22."</b>
	</td>
</tr>";

// Disposition
$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN20."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
if($pref['ypmenu_aspect']==1){
	 $text .= $rs -> form_checkbox("ypmenuaspect",1,1);
}else{
	 $text .= $rs -> form_checkbox("ypmenuaspect",1,0);
}
$text .= "<br /><b class=\"smalltext\" >".ypslide_LAN21."</b></td></tr>";

// Position
$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN4.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
if($pref['ypmenu_pos']==1){
	 $text .= $rs -> form_radio("ypmenupos",1,1);
}else{
	 $text .= $rs -> form_radio("ypmenupos",1,0);
}
$text .= "<b>".ypslide_LAN5."</b><br />";
if($pref['ypmenu_pos']==2){
	 $text .= $rs -> form_radio("ypmenupos",2,1);
}else{
	 $text .= $rs -> form_radio("ypmenupos",2,0);
}
$text .= "<b>".ypslide_LAN6."</b><br />";
$text .= "<b class=\"smalltext\" >".ypslide_LAN7."</b></td></tr>";

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN11.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenuposx",8,$pref['ypmenu_posx'],8);
$text .= " pixels<br /><b class=\"smalltext\" >".ypslide_LAN13."</b></td></tr>";

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN12.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenuposy",8,$pref['ypmenu_posy'],8);
$text .=" pixels<br /><b class=\"smalltext\" >".ypslide_LAN14."</b></td></tr>";

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN16.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenutotalwidth",4,$pref['ypmenu_totalwidth'],8);
$text .=" pixels<br /><b class=\"smalltext\" >".ypslide_LAN17."</b></td></tr>";

$text .= $textbutupd;

$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN30."</b>
	</td>
</tr>";

// Submenus position
$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN31.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
if($pref['ypmenu_subpos']==1){
	 $text .= $rs -> form_radio("ypmenusubpos",1,1);
}else{
	 $text .= $rs -> form_radio("ypmenusubpos",1,0);
}
$text .= "<b>".ypslide_LAN5."</b><br />";
if($pref['ypmenu_subpos']==2){
	 $text .= $rs -> form_radio("ypmenusubpos",2,1);
}else{
	 $text .= $rs -> form_radio("ypmenusubpos",2,0);
}
$text .= "<b>".ypslide_LAN6."</b><br />";
$text .= "<b class=\"smalltext\" >".ypslide_LAN32."</b></td></tr>";

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN11.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenusubposx",8,$pref['ypmenu_subposx'],8);
$text .= " pixels<br /><b class=\"smalltext\" >".ypslide_LAN39."</b></td></tr>";

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN12.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenusubposy",8,$pref['ypmenu_subposy'],8);
$text .=" pixels<br /><b class=\"smalltext\" >".ypslide_LAN40."</b></td></tr>";

// Slide direction
$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN8.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
if($pref['ypmenu_slidedir']=="down"){
	 $text .= $rs -> form_radio("ypmenuslidedir","down",1);
}else{
	 $text .= $rs -> form_radio("ypmenuslidedir","down",0);
}
$text .= "<b>".ypslide_LAN9."</b><br />";
if($pref['ypmenu_slidedir']=="right"){
	 $text .= $rs -> form_radio("ypmenuslidedir","right",1);
}else{
	 $text .= $rs -> form_radio("ypmenuslidedir","right",0);
}
$text .= "<b>".ypslide_LAN10."</b><br />";
$text .= "</td></tr>";

// Size

$text .="<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN18.":</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("ypmenusubwidth",4,$pref['ypmenu_subwidth'],8);
$text .=" pixels</td></tr>";

$text .= $textbutupd;

$text .= "</table>
</div>
</span>";

$ns -> tablerender($caption, $text);


//++++++ HTML Design options
$caption = ypslide_LAN3;
$text = "º <a href=\"javascript:void(0)\" onclick=\"expandit(this)\" style=\"cursor: hand; cursor: pointer;\" >".ypslide_LAN44."</a>\n
<span style=\"display: none;\" >
<br /><br /><div style=\"text-align:center\">
<table style=\"width:94%\" class=\"fborder\">";

// Mainbar
$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN22."</b>
	</td>
</tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN47."</td>
<td style=\"width:70%\" class=\"forumheader3\">
<style>
#prevmenubar {\n";
$text .= "
	background-color:transparent;
	padding:2px;
	width: 350px;
	line-height: 18px;
	".$ypmenu_pref['mb_custom']."
}

#prevmenubar a {
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

.prevmenu .prevoptions {
	margin-right:1px;
	margin-bottom:1px;
	border:1px solid ".$ypmenu_pref['links_border'].";
	background-color:".$ypmenu_pref['links_backcol'].";
	".(strlen($ypmenu_pref['links_backimg'])>4?"background-image : url(".$ypmenu_pref['links_backimg'].");":"")."
	width:".$pref['ypmenu_subwidth']."px;
	".$ypmenu_pref['links_custom']."
}

.prevmenu a {
	font-family:".$ypmenu_pref['links_fontfamily'].";
	font-size:".$ypmenu_pref['links_fontsize'].";
	color:".$ypmenu_pref['links_colorlinks'].";
	display:block;
	padding:2px 4px;
	text-decoration:".$ypmenu_pref['links_textdeco'].";
	background-color:transparent;
}

.prevmenu a:hover {
	color:".$ypmenu_pref['linksa_colorlink'].";
	".(strlen($ypmenu_pref['linksa_backimg'])>4?"background-image : url(".$ypmenu_pref['linksa_backimg'].");":"")."
	background-color:".$ypmenu_pref['linksa_backcol'].";
	".$ypmenu_pref['linksa_custom']."
}

	
#prevmenubar a:hover {
	border-bottom-color:#000040;
	border-right-color:#000040;
	border-left-color:#000040;
	border-top-color:#000040;
	background-color:".$ypmenu_pref['mba_backcol'].";
	color:".$ypmenu_pref['mba_colorlink'].";
	".(strlen($ypmenu_pref['mba_backimg'])>4?"background-image : url(".$ypmenu_pref['mba_backimg'].");":"")."
	".$ypmenu_pref['mba_custom']."
}

#prevMenu2Container {
	visibility:visible;
	left:0px;
	top:0px;
	overflow:hidden;
	z-index:10000;
}

</style>
<div id=\"prevmenubar\" >
<a href=\"javascript:void(0);\" >".ypslide_LAN48."1".(strlen($ypmenu_pref['mb_icosub'])>4 ? "<img style=\"vertical-align: middle; border: 0;\" src=\"".$ypmenu_pref['mb_icosub']."\" />":"")."</a> 
<a href=\"javascript:void(0);\" >".ypslide_LAN48."2</a>
</div>
<div id='prevmenu2Container' >
	<div id='prevmenu2Content' class='prevmenu' >
		<div class='prevoptions'>
			<a href='javascript:void(0);'>".ypslide_LAN49."11</a>
			<a href='javascript:void(0);'>".ypslide_LAN49."12</a>
		</div>
	</div>
</div>
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN45."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbfontfamily",40,$ypmenu_pref['mb_fontfamily'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN46."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbfontsize",4,$ypmenu_pref['mb_fontsize'],4);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN23."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbcolorlink",10,$ypmenu_pref['mb_colorlink'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol('mbcolorlink');\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "<input type=\"hidden\" id=\"nbrcolor\" name=\"nbrcolor\" value=\"\"></td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN28."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbbackcol",10,$ypmenu_pref['mb_backcol'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol(document.ypslide_config.mbbackcol);\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$handle=opendir("images/");
while ($file = readdir($handle)){
	if($file != "." && $file != ".." && $file != "/" && strpos($file,"ico_")===FALSE){
		$bglist[] = $file;	
	}
	if($file != "." && $file != ".." && $file != "/" && strpos($file,"submenu")!==FALSE){
		$icosublist[] = $file;	
	}
}
closedir($handle);


$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN25."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbbackimg",100,$ypmenu_pref['mb_backimg'],100);
$text .= "<br />
<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".ypslide_LAN43."' onClick='expandit(this)'>\n
<span style=\"display: none;\" ><br /><br />";
$cc=0;
while(list($key, $icon) = each($bglist)){
	$text .= "<div style=\"background-image:url(".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icon."); width: 50px; float: left; cursor: pointer; cursor: hand;\" onclick=\"addtext('".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icon."',document.ypslide_config.mbbackimg);\" > ".ypslide_LAN41." </div> ";
	$icostored[$cc]=$icon;
	$cc++;
}
$text .= "
<br /><br />
<b class=\"smalltext\" >".ypslide_LAN42."</b></span>
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN26."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbborder",40,$ypmenu_pref['mb_border'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN27."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbtextdeco",40,$ypmenu_pref['mb_textdeco'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN52."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbtextalign",40,$ypmenu_pref['mb_textalign'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN54."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbicosub",100,$ypmenu_pref['mb_icosub'],100);
$text .= "<br />
<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".ypslide_LAN43."' onClick='expandit(this)'>\n
<span style=\"display: none;\" ><br /><br />";
$cc=0;
while(list($key, $icon) = each($icosublist)){
	$text .= "<img src=\"".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icon."\" style=\"margin: 2 4 2 2; cursor: pointer; cursor: hand;\" onclick=\"addtext('".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icon."',document.ypslide_config.mbicosub);\" /> ";
}
$text .= "
<br /><br />
<b class=\"smalltext\" >".ypslide_LAN55."</b></span>
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN56."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbcustom",100,$ypmenu_pref['mb_custom'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24."<br />
".ypslide_LAN57."
</td></tr>";



$text .= $textbutupd;

// Mainbar activated link
$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN29."</b>
	</td>
</tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN23."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbacolorlink",10,$ypmenu_pref['mba_colorlink'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol(document.ypslide_config.mbacolorlink);\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN28."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbabackcol",10,$ypmenu_pref['mba_backcol'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol(document.ypslide_config.mbabackcol);\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN25."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbabackimg",100,$ypmenu_pref['mba_backimg'],100);
$text .= "<br />
<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".ypslide_LAN43."' onClick='expandit(this)'>\n
<span style=\"display: none;\" ><br /><br />";
for($i=0;$i<$cc;$i++){
	$text .= "<div style=\"background-image:url(".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."); width: 50px; float: left; cursor: pointer; cursor: hand;\" onclick=\"addtext('".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."',document.ypslide_config.mbabackimg);\" > ".ypslide_LAN41." </div> ";
}
$text .= "
<br /><br />
<b class=\"smalltext\" >".ypslide_LAN42."</b></span></td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN56."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("mbacustom",100,$ypmenu_pref['mba_custom'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24."<br />
".ypslide_LAN57."
</td></tr>";

$text .= $textbutupd;

// Sublinks
$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN30."</b>
	</td>
</tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN45."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksfontfamily",40,$ypmenu_pref['links_fontfamily'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN46."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksfontsize",4,$ypmenu_pref['links_fontsize'],4);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN23."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linkscolorlinks",10,$ypmenu_pref['links_colorlinks'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol(document.ypslide_config.linkscolorlinks);\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN28."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksbackcol",10,$ypmenu_pref['links_backcol'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol(document.ypslide_config.linksbackcol);\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN25."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksbackimg",100,$ypmenu_pref['links_backimg'],100);
$text .= "<br />
<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".ypslide_LAN43."' onClick='expandit(this)'>\n
<span style=\"display: none;\" ><br /><br />";
for($i=0;$i<$cc;$i++){
	$text .= "<div style=\"background-image:url(".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."); width: 50px; float: left; cursor: pointer; cursor: hand;\" onclick=\"addtext('".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."',document.ypslide_config.linksbackimg);\" > ".ypslide_LAN41." </div> ";
}
$text .= "
<br /><br />
<b class=\"smalltext\" >".ypslide_LAN42."</b></span></td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN26."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksborder",40,$ypmenu_pref['links_border'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN27."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linkstextdeco",40,$ypmenu_pref['links_textdeco'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24." 
</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN56."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linkscustom",100,$ypmenu_pref['links_custom'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24."<br />
".ypslide_LAN57."
</td></tr>";

$text .= $textbutupd;

// Submenus activated link
$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN51."</b>
	</td>
</tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN23."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksacolorlink",10,$ypmenu_pref['linksa_colorlink'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol('linksacolorlink');\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN28."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksabackcol",10,$ypmenu_pref['linksa_backcol'],7);
$text .= " <img style=\"cursor: hand; cursor: pointer;\" onClick=\"chgcol('linksabackcol');\" src=\"".e_PLUGIN."ypslide_menu/images/ico_pickcolor.png\" width=\"24\" height=\"24\" style=\"vertical-align: middle;\" title=\"".colpick_LAN3."\" />";
$text .= "</td></tr>";

$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN25."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksabackimg",100,$ypmenu_pref['linksa_backimg'],100);
$text .= "<br />
<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".ypslide_LAN43."' onClick='expandit(this)'>\n
<span style=\"display: none;\" ><br /><br />";
for($i=0;$i<$cc;$i++){
	$text .= "<div style=\"background-image:url(".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."); width: 50px; float: left; cursor: pointer; cursor: hand;\" onclick=\"addtext('".SITEURL.$PLUGINS_DIRECTORY."ypslide_menu/images/".$icostored[$i]."',document.ypslide_config.linksabackimg);\" > ".ypslide_LAN41." </div> ";
}
$text .= "
<br /><br />
<b class=\"smalltext\" >".ypslide_LAN42."</b></span></td></tr>";


$text .= "<tr>
<td style=\"width:30%; vertical-align:top\" class=\"forumheader3\">".ypslide_LAN56."</td>
<td style=\"width:70%\" class=\"forumheader3\">";
$text .= $rs -> form_text("linksacustom",100,$ypmenu_pref['linksa_custom'],100);
$text .= "<br /><img src=\"images/ico_warning.png\" title=\"Warning\" style=\"vertical-align: middle;\" width=\"16\" height=\"16\" > ".ypslide_LAN24."<br />
".ypslide_LAN57."
</td></tr>";

$text .="
<tr>
	<td colspan=\"2\" >
	<b>".ypslide_LAN53."</b>
	</td>
</tr>";



$text .= $textbutupd;
$text .= "</table>";

$text .= $rs -> form_close();

$text .= "</div>
</span>";

$ns -> tablerender($caption, $text);

//-------- Submit to Touchatou

$url_touchatou = "http://www.touchatou.org/e107/inscription_plug_lolo.php";
//$url_touchatou = "http://localhost/touchatou/e107/inscription_plug_lolo.php";

if($pref['echat_touchatou']!=2){
	$caption = SUB_TOUCHATOU_1;
	$text = "º <a href=\"javascript:void(0)\" onclick=\"expandit(this)\" style=\"cursor: hand; cursor: pointer;\" >".ypslide_LAN44."</a>\n
	<span style=\"display: none;\" >
	<br /><form method=\"post\" action=\"".$url_touchatou."\" target=\"_blank\" name=\"sub_touch_form\" >\n
	<input type=\"hidden\" value=\"ypslide\" name=\"name_plugin\" />\n
	<input type=\"hidden\" value=\"45\" name=\"id_plugin\" />\n
	<input type=\"hidden\" value=\"".SITENAME."\" name=\"link_name\" />\n
	<input type=\"hidden\" value=\"".SITEURL."\" name=\"link_url\" />\n
	<input type=\"hidden\" value=\"".SITEDESCRIPTION."\" name=\"link_description\" />\n
	<input type=\"hidden\" value=\"Touchatou\" name=\"refer_plug_lolo\" />\n
	<br />\n
	<table style=\"width:98%\" border=\"0\" cellspacing=\"10\" >\n
	<tr>\n";

	$text .= "<tr>
	<td style='width:50%' class='forumheader3'>".SUB_TOUCHATOU_2." </td>\n
	<td style='width:50%' class='forumheader3'>".
	($pref['echat_touchatou'] ? "<input type='checkbox' name='echat_touchatou' value='1'  checked />" : "<input type='checkbox' name='echat_touchatou' value='1' />")."
	<a href=\"javascript:void(0);\" onclick=\"expandit(this);\" >".SUB_TOUCHATOU_3."</a>\n
	<div style=\"display: none;\">\n
	<br /><br />\n".SUB_TOUCHATOU_4."\n
	</div>\n
	</td>\n
	</tr>\n
	</table>\n
	<p style=\"text-align: center; width: 100%;\" ><input type=\"button\" class=\"button\" value=\"".SUB_TOUCHATOU_5."\" name=\"\" onclick=\"if(sub_touch_form.echat_touchatou.checked==false){alert('".SUB_TOUCHATOU_6."');}else{sub_touch_form.submit();sub_touch_form.target='_self';sub_touch_form.action='".e_SELF."';sub_touch_form.submit();}\" /></p>\n
	</form>\n
	</span>";
	
}else{
	$caption = SUB_TOUCHATOU_9;
	$text = SUB_TOUCHATOU_10;
}
$ns->tablerender($caption,$text);

require_once(e_ADMIN."footer.php");

?>