<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/classes/form_class.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

class form{

	function form_open($form_method, $form_action, $form_name="", $form_target = ""){
		$method = ($form_method ? "method='".$form_method."'" : ""); 
		$target = ($form_target ? " target='".$form_target."'" : "");
		$name = ($form_name ? " name='".$form_name."'" : "");
		return "\n<form action='".$form_action."' ".$method.$target.$name.">";
	}

	function form_text($form_name, $form_size, $form_value, $form_maxlength, $form_class="tbox", $form_readonly="", $form_tooltip=""){
		$value = ($form_value ? " value='".$form_value."'" : "");
		$size = ($form_size ? " size='".$form_size."'" : "");
		$maxlength = ($form_maxlength ? " maxlength='".$form_maxlength."'" : "");
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input class='".$form_class."' type='text' name='".$form_name."' ".$value.$size.$maxlength.$readonly.$tooltip." />";
	}

	function form_button($form_type, $form_name, $form_value, $form_js="", $form_image="", $form_tooltip=""){
		$name =  ($form_name ? " name='".$form_name."' " : "");
		$image = ($form_image ? " src='".$form_image."' " : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."' " : "");
		return "\n<input class='button' type='".$form_type."' ".$form_js." name='".$form_name."' value='".$form_value."'".$image.$tooltip."/>";
	}

	function form_textarea($form_name, $form_columns, $form_rows, $form_value, $form_js="", $form_style="", $form_wrap="", $form_readonly="", $form_tooltip=""){
		$readonly = ($form_readonly ? " readonly='readonly'" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		$wrap = ($form_wrap ? " wrap='".$form_wrap."'" : "");
		$style = ($form_style ? " style='".$form_style."'" : "");
		return "\n<textarea class='tbox' name='".$form_name."' cols='".$form_columns."' rows='".$form_rows."' ".$form_js.$style.$wrap.$readonly.$tooltip.">".$form_value."</textarea>";
	}

	function form_checkbox($form_name, $form_value, $form_checked=0, $form_tooltip=""){
		$checked = ($form_checked ? " checked" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input type='checkbox' name='".$form_name."' value='".$form_value."'".$checked.$tooltip." />";

	}

	function form_radio($form_name, $form_value, $form_checked=0, $form_tooltip=""){
		$checked = ($form_checked ? " checked" : "");
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "\n<input type='radio' name='".$form_name."' value='".$form_value."'".$checked.$tooltip." />";

	}

	function form_file($form_name, $form_size, $form_tooltip=""){
		$tooltip = ($form_tooltip ? " title='".$form_tooltip."'" : "");
		return "<input type='file' class='tbox' name='".$form_name."' size='".$form_size."'".$tooltip." />";
	}

	function form_select_open($form_name){
		return "\n<select name='".$form_name."' class='tbox'>";
	}

	function form_select_close(){
		return "\n</select>";
	}

	function form_option($form_option, $form_selected="", $form_value=""){
		$selected = ($form_value ? " value='".$form_value."'" : "");
		$value = ($form_selected ? " selected" : "");
		return "\n<option".$value.$selected.">".$form_option."</option>";
	}

	function form_hidden($form_name, $form_value){
		return "\n<input type='hidden' name='".$form_name."' value='".$form_value."' />";
	}

	function form_close(){
		return "\n</form>";
	}
}

/*
Usage
echo $rs -> form_open("post", e_SELF, "_blank");
echo $rs -> form_text("testname", 100, "this is the value", 100, 0, "tooltip");
echo $rs -> form_button("submit", "testsubmit", "SUBMIT!", "", "Click to submit");
echo $rs -> form_button("reset", "testreset", "RESET!", "", "Click to reset");
echo $rs -> form_textarea("textareaname", 10, 10, "Value", "overflow:hidden");
echo $rs -> form_checkbox("testcheckbox", 1, 1);
echo $rs -> form_checkbox("testcheckbox2", 2);
echo $rs -> form_hidden("hiddenname", "hiddenvalue");
echo $rs -> form_radio("testcheckbox", 1, 1);
echo $rs -> form_radio("testcheckbox", 1);
echo $rs -> form_file("testfile", "20");
echo $rs -> form_select_open("testselect");
echo $rs -> form_option("Option 1");
echo $rs -> form_option("Option 2");
echo $rs -> form_option("Option 3", 1, "defaultvalue");
echo $rs -> form_option("Option 4");
echo $rs -> form_select_close();
echo $rs -> form_close();
*/


?>