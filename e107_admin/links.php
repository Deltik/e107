<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/links.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if(!getperms("I")){ header("location:".e_BASE."index.php"); }

require_once("auth.php");
require_once(e_HANDLER."userclass_class.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;
$aj = new textparse;
$linkpost = new links;

if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

// ##### Main loop -----------------------------------------------------------------------------------------------------------------------

if($action == "dec"){
	$qs = explode(".", e_QUERY);
	$action = $qs[0];
	$linkid = $qs[1];
	$link_order = $qs[2];
	$location = $qs[3];
	$sql -> db_Update("links", "link_order=link_order-1 WHERE link_order='".($link_order+1)."' AND link_category='$location' ");
	$sql -> db_Update("links", "link_order=link_order+1 WHERE link_id='$linkid' AND link_category='$location' ");
	header("location: links.php?order");
}

if($action == "inc"){
	$qs = explode(".", e_QUERY);
	$action = $qs[0];
	$linkid = $qs[1];
	$link_order = $qs[2];
	$location = $qs[3];
	$sql -> db_Update("links", "link_order=link_order+1 WHERE link_order='".($link_order-1)."' AND link_category='$location' ");
	$sql -> db_Update("links", "link_order=link_order-1 WHERE link_id='$linkid' AND link_category='$location' ");
	header("location: links.php?order");
}

if(IsSet($_POST['create_category'])){
	$_POST['link_category_name'] = $aj -> formtpa($_POST['link_category_name'], "admin");
	$sql -> db_Insert("link_category", " '0', '".$_POST['link_category_name']."', '".$_POST['link_category_description']."', '".$_POST['link_category_icon']."'");
	$linkpost -> show_message(LCLAN_51);
}

if(IsSet($_POST['update_category'])){
	$_POST['category_name'] = $aj -> formtpa($_POST['category_name'], "admin");
	$sql -> db_Update("link_category", "link_category_name ='".$_POST['link_category_name']."', link_category_description='".$_POST['link_category_description']."',  link_category_icon='".$_POST['link_category_icon']."' WHERE link_category_id='".$_POST['link_category_id']."'");
	$linkpost -> show_message(LCLAN_52);
}

if(IsSet($_POST['update_order'])){
	extract($_POST);
	while(list($key, $id) = each($link_order)){
		$tmp = explode(".", $id);
		$sql -> db_Update("links", "link_order=".$tmp[1]." WHERE link_id=".$tmp[0]);
	}
	$linkpost -> show_message(LCLAN_6);
}

if(IsSet($_POST['updateoptions'])){
	$pref['linkpage_categories'] = $_POST['linkpage_categories'];
	$pref['link_submit'] = $_POST['link_submit'];
	$pref['link_submit_class'] = $_POST['link_submit_class'];
	$pref['linkpage_screentip'] = $_POST['linkpage_screentip'];
	save_prefs();
	$linkpost -> show_message(LCLAN_1);
}

if($action == "order"){
	$linkpost -> set_order();
}

if($action == "main" && $sub_action == "confirm"){
	if($sql -> db_Delete("links", "link_id='$id' ")){
		$linkpost -> show_message(LCLAN_53." #".$id." ".LCLAN_54);
	}
}

if($action == "cat" && $sub_action == "confirm"){
	if($sql -> db_Delete("link_category", "link_category_id='$id' ")){
		$linkpost -> show_message(LCLAN_55." #".$id." ".LCLAN_54);
		unset($id);
	}
}

if(IsSet($_POST['add_link'])){
	$linkpost -> submit_link($sub_action, $id);
	unset($id);
}

if($action == "create"){
	$linkpost -> create_link($sub_action, $id);
}

if(!e_QUERY || $action == "main"){
	$linkpost -> show_existing_items();
}

if($action == "cat"){
	$linkpost -> show_categories($sub_action, $id);
}

if($action == "sn"){
	if($sub_action == "confirm"){
		$sql -> db_Delete("tmp", "tmp_time='$id' ");
		$linkpost -> show_message(LCLAN_77);
	}
	$linkpost -> show_submitted($sub_action, $id);
}

if($action == "opt"){
	$linkpost -> show_pref_options();
}



$linkpost -> show_options($action);

require_once("footer.php");
?>
<script type="text/javascript">
function addtext(sc){
	document.linkform.link_button.value = sc;
}
function addtext2(sc){
	document.linkform.link_category_icon.value = sc;
}
</script>
<?php
echo "<script type=\"text/javascript\">
function confirm_(mode, link_id){
	if(mode == 'cat'){
		var x=confirm(\"".LCLAN_56." [ID: \" + link_id + \"]\");
	}else if(mode == 'sn'){
		var x=confirm(\"".LCLAN_57." [ID: \" + link_id + \"]\");
	}else{
		var x=confirm(\"".LCLAN_58." [ID: \" + link_id + \"]\");
	}
if(x)
	if(mode == 'cat'){
		window.location='".e_SELF."?cat.confirm.' + link_id;
	}else if(mode == 'sn'){
		window.location='".e_SELF."?sn.confirm.' + link_id;
	}else{
		window.location='".e_SELF."?main.confirm.' + link_id;
	}
}
</script>";

exit;

// ##### End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------


class links{

	function show_existing_items(){
		global $sql;
		if($sql -> db_Select("link_category")){
			while($row = $sql -> db_Fetch()){
				extract($row);
				$cat[$link_category_id] = $link_category_name;
			}
		}else{
			$sql -> db_Insert("link_category", "0, 'Main', 'Any links with this category will be displayed in main navigation bar.', '' ");
			$sql -> db_Insert("link_category", "0, 'Misc', 'Miscellaneous links.', '' ");
		}

		// ##### Display scrolling list of existing links ---------------------------------------------------------------------------------------------------------
		global $sql, $rs, $ns, $aj;
		$text = "<div style='text-align:center'><div style='border : solid 1px #000; padding : 4px; width : auto; height : 200px; overflow : auto; '>";
		if($link_total = $sql -> db_Select("links", "*", "ORDER BY link_category, link_id ASC", "nowhere")){
			$text .= "<table class='fborder' style='width:100%'>
			<tr>
			<td style='width:5%' class='forumheader2'>ID</td>
			<td style='width:10%' class='forumheader2'>".LCLAN_59."</td>
			<td style='width:50%' class='forumheader2'>".LCLAN_53."</td>
			<td style='width:18%' class='forumheader2'>".LCLAN_60."</td>
			</tr>";
			while($row = $sql -> db_Fetch()){
				extract($row);
				$text .= "<tr>
				<td style='width:5%' class='forumheader3'>$link_id</td>
				<td style='width:10%' class='forumheader3'>".$cat[$link_category]."</td>
				<td style='width:50%' class='forumheader3'><a href='".e_BASE."comment.php?$link_id'></a>$link_name</td>
				<td style='width:25%; text-align:center' class='forumheader3'>".
				$rs -> form_button("submit", "main_edit", "Edit", "onClick=\"document.location='".e_SELF."?create.edit.$link_id'\"").
				$rs -> form_button("submit", "main_delete", "Delete", "onClick=\"confirm_('create', $link_id)\"")."
				</td>
				</tr>";
			}
			$text .= "</table>";
		}else{
			$text .= "<div style='text-align:center'>".LCLAN_61."</div>";
		}
		$text .= "</div></div>";
		$ns -> tablerender(LCLAN_8, $text);
	}

	function show_options($action){
		// ##### Display options ---------------------------------------------------------------------------------------------------------
		global $sql, $rs, $ns;
		$text = "<div style='text-align:center'>";
		if(e_QUERY && $action != "main"){
			$text .= "<a href='".e_SELF."'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_62."</div></div></a>";
		}
		if($action != "create"){
			$text .= "<a href='".e_SELF."?create'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_63."</div></div></a>";
		}

		if($action != "order"){
			$text .= "<a href='".e_SELF."?order'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_64."</div></div></a>";
		}

		if($action != "cat" && getperms("8")){
			$text .= "<a href='".e_SELF."?cat'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_65."</div></div></a>";
		}
		if($action != "sn"){
			$text .= "<a href='".e_SELF."?sn'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_66."</div></div></a>";
		}

		if($action != "opt"){
			$text .= "<a href='".e_SELF."?opt'><div class='border'><div class='forumheader'><img src='".e_IMAGE."generic/location.png' style='vertical-align:middle; border:0' alt='' /> ".LCLAN_67."</div></div></a>";
		}


		$text .= "</div>";
		$ns -> tablerender(LCLAN_68, $text);
	}

	function show_message($message){
		// ##### Display comfort ---------------------------------------------------------------------------------------------------------
		global $ns;
		$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}


	function create_link($sub_action, $id){
		global $sql, $rs, $ns;

		if($sub_action == "edit" && !$_POST['submit']){
			if($sql -> db_Select("links", "*", "link_id='$id' ")){
				$row = $sql-> db_Fetch();
				extract($row);
			}
		}

		if($sub_action == "sn"){
			if($sql -> db_Select("tmp", "*", "tmp_time='$id'")){
				$row = $sql-> db_Fetch();
				extract($row);
				$submitted = explode("^", $tmp_info);
				$link_category = $submitted[0];
				$link_name = $submitted[1];
				$link_url = $submitted[2];
				$link_description = $submitted[3]."\n[i]Submitted by ".$submitted[5]."[/i]";
				$link_button = $submitted[4];
			}
		}



		$handle=opendir(e_IMAGE."link_icons");
		while ($file = readdir($handle)){
			if($file != "." && $file != ".." && $file != "/"){
				$iconlist[] = $file;
			}
		}
		closedir($handle);

		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."' name='linkform'>
		<table style='width:95%' class='fborder'>
		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_12.": </td>
		<td style='width:70%' class='forumheader3'>";

		if(!$link_cats = $sql -> db_Select("link_category")){
			$text .= LCLAN_13."<br />";
		}else{
			$text .= "
			<select name='cat_id' class='tbox'>";

			while(list($cat_id, $cat_name, $cat_description) = $sql-> db_Fetch()){
				if($link_category == $cat_id || ($cat_id == $linkid && $action == "add")){
					$text .= "<option value='$cat_id' selected>".$cat_name."</option>";
				}else{
					$text .= "<option value='$cat_id'>".$cat_name."</option>";
				}
			}
			$text .= "</select>";
		}
		$text .= "<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_15.": </td>
		<td style='width:70%' class='forumheader3'>
		<input class='tbox' type='text' name='link_name' size='60' value='$link_name' maxlength='100' />
		</td>
		</tr>

		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_16.": </td>
		<td style='width:70%' class='forumheader3'>
		<input class='tbox' type='text' name='link_url' size='60' value='$link_url' maxlength='200' />
		</td>
		</tr>

		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_17.": </td>
		<td style='width:70%' class='forumheader3'>
		<textarea class='tbox' name='link_description' cols='59' rows='3'>$link_description</textarea>
		</td>
		</tr>

		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_18.": </td>
		<td style='width:70%' class='forumheader3'>
		<input class='tbox' type='text' name='link_button' size='60' value='$link_button' maxlength='100' />

		<br />
		<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='".LCLAN_39."' onClick='expandit(this)'>
		<div style='display:none' style=&{head};>";



		while(list($key, $icon) = each($iconlist)){
			$text .= "<a href='javascript:addtext(\"$icon\")'><img src='".e_IMAGE."link_icons/".$icon."' style='border:0' alt='' /></a> ";
		}

		$text .= "</td>
		</tr>
		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_19.": </td>
		<td style='width:70%' class='forumheader3'>
		<select name='linkopentype' class='tbox'>".
		($link_open == 0 ? "<option value='0' selected>".LCLAN_20."</option>" : "<option value='0'>".LCLAN_20."</option>").

		($link_open == 1 ? "<option value='1' selected>".LCLAN_21."</option>" : "<option value='1'>".LCLAN_21."</option>").
		($link_open == 2 ? "<option value='2' selected>".LCLAN_22."</option>" : "<option value='2'>".LCLAN_22."</option>").
		($link_open == 3 ? "<option value='3' selected>".LCLAN_23."</option>" : "<option value='3'>".LCLAN_23."</option>").
		($link_open == 4 ? "<option value='4' selected>".LCLAN_24."</option>" : "<option value='4'>".LCLAN_24."</option>")."
		</select>
		</td>
		</tr>

		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_25.":<br /><span class='smalltext'>(".LCLAN_26.")</span></td>
		<td style='width:70%' class='forumheader3'>".r_userclass("link_class",$link_class)."

		</td></tr>


		<tr style='vertical-align:top'>
		<td colspan='2' style='text-align:center' class='forumheader'>";
		if($id && $sub_action == "edit"){
			$text .= "<input class='button' type='submit' name='add_link' value='".LCLAN_27."' />\n<input type='hidden' name='link_id' value='$link_id'>";
		}else{
			$text .= "<input class='button' type='submit' name='add_link' value='".LCLAN_28."' />";
		}
		$text .= "</td>
		</tr>
		</table>
		</form>
		</div>";
		$ns -> tablerender("<div style='text-align:center'>".LCLAN_29."</div>", $text);

	}


	function submit_link($sub_action, $id){
		// ##### Format and submit link ---------------------------------------------------------------------------------------------------------
		global $aj, $sql;
		$link_name = $aj -> formtpa($_POST['link_name'], "admin");
		$link_url = $aj -> formtpa($_POST['link_url'], "admin");
		$link_description = $aj -> formtpa($_POST['link_description'], "admin");
		$link_button = $aj -> formtpa($_POST['link_button'], "admin");

		$link_t = $sql -> db_Count("links", "(*)", "WHERE link_category='".$_POST['cat_id']."'");

		if($id && $sub_action != "sn"){
			$sql -> db_Update("links", "link_name='$link_name', link_url='$link_url', link_description='$link_description', link_button= '$link_button', link_category='".$_POST['cat_id']."', link_open='".$_POST['linkopentype']."', link_class='".$_POST['link_class']."' WHERE link_id='$id'");
			$this->show_message(LCLAN_3);
		}else{
			$sql -> db_Insert("links", "0, '$link_name', '$link_url', '$link_description', '$link_button', '".$_POST['cat_id']."', '".($link_t+1)."', '0', '".$_POST['linkopentype']."', '".$_POST['link_class']."'");
			$this->show_message(LCLAN_2);
		}
		if($sub_action == "sn"){
			$sql -> db_Delete("tmp", "tmp_time='$id' ");
		}
	}


	function set_order(){
		global $sql, $ns, $aj;
		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?order'>
		<table style='width:85%' class='fborder'>";

		$sql -> db_Select("link_category");
		$sql2 = new db;
		while(list($link_category_id, $link_category_name, $link_category_description) = $sql-> db_Fetch()){
			if($lamount = $sql2 -> db_Select("links", "*", "link_category ='$link_category_id' ORDER BY link_order ASC ")){
				$text .= "<tr><td colspan='3' class='forumheader'>$link_category_name Category</td></tr>";
				while(list($link_id, $link_name, $link_url, $link_description, $link_button, $link_category, $link_order, $link_refer) = $sql2-> db_Fetch()){
					$text .= "<tr>\n<td style='width:30%' class='forumheader3'>".$link_order." - ".$link_name."</td>\n<td style='width:30%; text-align:center' class='forumheader3'>\n<select name='link_order[]' class='tbox'>";
					for($a=1; $a<= $lamount; $a++){
						$text .= ($link_order == $a ? "<option value='$link_id.$a' selected>$a</option>\n" : "<option value='$link_id.$a'>$a</option>\n");
					}

					$text .= "</select> <select name='activate' onChange='urljump(this.options[selectedIndex].value)' class='tbox'>
					<option value='links.php' selected></option>
					<option value='links.php?inc.".$link_id.".".$link_order.".".$link_category."'>".LCLAN_30."</option>
					<option value='links.php?dec.".$link_id.".".$link_order.".".$link_category."'>".LCLAN_31."</option>
					</select>
					</td>
					<td style='width:40%' class='forumheader3'>&nbsp;".$aj->tpa($link_description)."</td>
					</tr>";
				}	
			}
		}
		$text .= "
		<tr>
		<td colspan='3' style='text-align:center' class='forumheader'>
		<input class='button' type='submit' name='update_order' value='".LCLAN_32."' />
		</td>
		</tr>
		</table>
		</form>
		</div>";

		$ns -> tablerender(LCLAN_33, $text);
	}



	function show_categories($sub_action, $id){
		// ##### Display scrolling list of existing categories ---------------------------------------------------------------------------------------------------------
		global $sql, $rs, $ns, $aj;
		$text = "<div style='border : solid 1px #000; padding : 4px; width :auto; height : 200px; overflow : auto; '>\n";
		if($category_total = $sql -> db_Select("link_category")){
			$text .= "<table class='fborder' style='width:100%'>
			<tr>
			<td style='width:5%' class='forumheader2'>&nbsp;</td>
			<td style='width:75%' class='forumheader2'>".LCLAN_59."</td>
			<td style='width:20%; text-align:center' class='forumheader2'>".LCLAN_60."</td>
			</tr>";
			while($row = $sql -> db_Fetch()){
				extract($row);

				$text .= "<tr>
				<td style='width:5%; text-align:center' class='forumheader3'>".($link_category_icon ? "<img src='".e_IMAGE."link_icons/$link_category_icon' alt='' style='vertical-align:middle' />" : "&nbsp;")."</td>
				<td style='width:75%' class='forumheader3'>$link_category_name<br /><span class='smalltext'>$link_category_description</span></td>
				<td style='width:20%; text-align:center' class='forumheader3'>
				".$rs -> form_button("submit", "category_edit", LCLAN_9, "onClick=\"document.location='".e_SELF."?cat.edit.$link_category_id'\"")."
				".$rs -> form_button("submit", "category_delete", LCLAN_10, "onClick=\"confirm_('cat', '$link_category_id');\"")."
				</td>
				</tr>\n";
			}
			$text .= "</table>";
		}else{
			$text .= "<div style='text-align:center'>".LCLAN_69."</div>";
		}
		$text .= "</div>";
		$ns -> tablerender(LCLAN_70, $text);

		unset($link_category_name, $link_category_description, $link_category_icon);

		$handle=opendir(e_IMAGE."link_icons");
		while ($file = readdir($handle)){
			if($file != "." && $file != ".."){
				$iconlist[] = $file;
			}
		}
		closedir($handle);

		if($sub_action == "edit"){
			if($sql -> db_Select("link_category", "*", "link_category_id ='$id' ")){
				$row = $sql -> db_Fetch(); extract($row);
			}
		}

		$text = "<div style='text-align:center'>
		".$rs -> form_open("post", e_SELF."?cat", "linkform")."
		<table class='fborder' style='width:auto'>
		<tr>
		<td class='forumheader3' style='width:30%'><span class='defaulttext'>".LCLAN_71."</span></td>
		<td class='forumheader3' style='width:70%'>".$rs -> form_text("link_category_name", 50, $link_category_name, 200)."</td>
		</tr>
		<tr>
		<td class='forumheader3' style='width:30%'><span class='defaulttext'>".LCLAN_72."</span></td>
		<td class='forumheader3' style='width:70%'>".$rs -> form_text("link_category_description", 60, $link_category_description, 200)."</td>
		</tr>
		<tr>
		<td class='forumheader3' style='width:30%'><span class='defaulttext'>".LCLAN_73."</span></td>
		<td class='forumheader3' style='width:70%'>
		".$rs -> form_text("link_category_icon", 60, $link_category_icon, 100)."
		<br />
		<input class='button' type ='button' style=''width: 35px'; cursor:hand' size='30' value='View Images' onClick='expandit(this)'>
		<div style='display:none'>";
		while(list($key, $icon) = each($iconlist)){
			$text .= "<a href='javascript:addtext2(\"$icon\")'><img src='".e_IMAGE."link_icons/".$icon."' style='border:0' alt='' /></a> ";
		}
		$text .= "</td>
		</tr>
		
		<tr><td colspan='2' style='text-align:center' class='forumheader'>";
		if($id){
			$text .= "<input class='button' type='submit' name='update_category' value='".LCLAN_74."'> 
			".$rs -> form_button("submit", "category_clear", "Clear Form").
			$rs -> form_hidden("link_category_id", $id)."
			</td></tr>";
		}else{
			$text .= "<input class='button' type='submit' name='create_category' value='".LCLAN_75."'></td></tr>";
		}
		$text .= "</table>
		".$rs -> form_close()."
		</div>";

		$ns -> tablerender(LCLAN_75, $text);
	}

	
	function show_submitted($sub_action, $id){
		global $sql, $rs, $ns, $aj;
		$text = "<div style='border : solid 1px #000; padding : 4px; width :auto; height : 200px; overflow : auto; '>\n";
		if($submitted_total = $sql -> db_Select("tmp", "*", "tmp_ip='submitted_link' ")){
			$text .= "<table class='fborder' style='width:100%'>
			<tr>
			<td style='width:50%' class='forumheader2'>".LCLAN_53."</td>
			<td style='width:30%' class='forumheader2'>".LCLAN_45."</td>
			<td style='width:20%; text-align:center' class='forumheader2'>".LCLAN_60."</td>
			</tr>";
			while($row = $sql -> db_Fetch()){
				extract($row);
				$submitted = explode("^", $tmp_info);
				if(!strstr($submitted[2], "http")){ $submitted[2] = "http://".$submitted[2]; }
				$text .= "<tr>
				<td style='width:50%' class='forumheader3'><a href='".$submitted[2]."' onclick=\"window.open('".$submitted[2]."'); return false;\">".$submitted[2]."</a></td>
				<td style='width:30%' class='forumheader3'>".$submitted[5]."</td>
				<td style='width:20%; text-align:center; vertical-align:top' class='forumheader3'>
				".$rs -> form_button("submit", "category_edit", "Post", "onClick=\"document.location='".e_SELF."?create.sn.$tmp_time'\"")."
				".$rs -> form_button("submit", "category_delete", "Delete", "onClick=\"confirm_('sn', $tmp_time);\"")."
				</td>
				</tr>\n";
			}
			$text .= "</table>";
		}else{
			$text .= "<div style='text-align:center'>".LCLAN_76."</div>";
		}
		$text .= "</div>";
		$ns -> tablerender(LCLAN_66, $text);
	}

	function show_pref_options(){
		global $pref, $ns;
		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."'>\n
		<table style='width:auto' class='fborder'>
		<tr>
		<td style='width:70%' class='forumheader3'>
		".LCLAN_40."<br />
		<span class='smalltext'>".LCLAN_34."</span>
		</td>
		<td style='width:30%' class='forumheader2' style='text-align:center'>".
		($pref['linkpage_categories'] ? "<input type='checkbox' name='linkpage_categories' value='1' checked>" : "<input type='checkbox' name='linkpage_categories' value='1'>")."
		</td>
		</tr>

		<tr>
		<td style='width:70%' class='forumheader3'>
		".LCLAN_78."<br />
		<span class='smalltext'>".LCLAN_79."</span>
		</td>
		<td style='width:30%' class='forumheader2' style='text-align:center'>".
		($pref['linkpage_screentip'] ? "<input type='checkbox' name='linkpage_screentip' value='1' checked>" : "<input type='checkbox' name='linkpage_screentip' value='1'>")."
		</td>
		</tr>

		<tr>
		<td style='width:70%' class='forumheader3'>
		".LCLAN_41."<br />
		<span class='smalltext'>".LCLAN_42."</span>
		</td>
		<td style='width:30%' class='forumheader2' style='text-align:center'>".
		($pref['link_submit'] ? "<input type='checkbox' name='link_submit' value='1' checked>" : "<input type='checkbox' name='link_submit' value='1'>")."
		</td>
		</tr>

		<tr>
		<td style='width:70%' class='forumheader3'>
		".LCLAN_43."<br />
		<span class='smalltext'>".LCLAN_44."</span>
		</td>
		<td style='width:30%' class='forumheader2' style='text-align:center'>".r_userclass("link_submit_class", $pref['link_submit_class'])."</td>
		</tr>

		<tr style='vertical-align:top'> 
		<td colspan='2'  style='text-align:center' class='forumheader'>
		<input class='button' type='submit' name='updateoptions' value='".LCLAN_35."' />
		</td>
		</tr>

		</table>
		</form>
		</div>";
		$ns -> tablerender(LCLAN_36, $text);
	}


}



