global $sql;
global $ns;
global $eMenuList;
global $error_handler;
if (!array_key_exists($parm,$eMenuList)) {
	return;
}



foreach($eMenuList[$parm] as $row)
{

	$show_menu = TRUE;
	if($row['menu_pages']) {
		list($listtype,$listpages) = explode("-",$row['menu_pages']);
		$pagelist = explode("|",$listpages);
		$check_url = e_SELF."?".e_QUERY;
		if($listtype == '1')  //show menu
		{
			$show_menu = FALSE;
			foreach($pagelist as $p) {
				if(strpos($check_url,$p) !== FALSE) {
					$show_menu = TRUE;
				}
			}
		}
		elseif($listtype == '2') //hide menu
		{
			$show_menu = TRUE;
			foreach($pagelist as $p) {
				if(strpos($check_url,$p) !== FALSE) {
					$show_menu = FALSE;
				}
			}
		}
	}
	if($show_menu) {
		$mname = $row['menu_name'];
		if($error_handler->debug == true) {
			echo "\n<!-- Menu Start: ".$mname." -->\n";
		}
		$sql->db_Mark_Time($row['menu_name']);
		if(is_numeric($row['menu_path']))
		{
			global $tp;
			$sql -> db_Select("page", "*", "page_id='".$row['menu_path']."' ");
			$page  = $sql -> db_Fetch();
			$caption = $tp -> toHTML($page['page_title'], TRUE, 'parse_sc, constants');
			$text = $tp -> toHTML($page['page_text'], TRUE, 'parse_sc, constants');
			$ns -> tablerender($caption, $text);
		}
		else
		{
			if(is_readable(e_LANGUAGEDIR.e_LANGUAGE."/plugins/lan_{$row['menu_path']}.php")) {
				include_once(e_LANGUAGEDIR.e_LANGUAGE."/plugins/lan_{$row['menu_path']}.php");
			} elseif (is_readable(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE.".php")) {
				include_once(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE.".php");	
			} elseif (is_readable(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE."/".e_LANGUAGE.".php")) {
				include_once(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE."/".e_LANGUAGE.".php");	
			} elseif (is_readable(e_LANGUAGEDIR."english/plugins/lan_{$row['menu_path']}.php")) {
				include_once(e_LANGUAGEDIR."English/plugins/lan_{$row['menu_path']}.php");
			} elseif (is_readable(e_PLUGIN.$row['menu_path']."/languages/English.php")) {
				include_once(e_PLUGIN.$row['menu_path']."/languages/English.php");
			} 
			
			if(file_exists(e_PLUGIN.$row['menu_path']."/".$mname.".php"))
			{
				include_once(e_PLUGIN.$row['menu_path']."/".$mname.".php");
			}
		}
		$sql->db_Mark_Time("(After ".$mname.")");
		if ($error_handler->debug == true) {
			echo "\n<!-- Menu Start: ".$mname." -->\n";			
		}
	}
}