<?php
function shortcuts($mode=FALSE){
	if($mode == TRUE){
		$shc = "<input class=\"button\" type=\"button\" value=\"newpage\" onclick=\"addtext('[newpage]')\"> 
		<input class=\"button\" type=\"button\" value=\"preserve\" onclick=\"addtext('[preserve] [/preserve]')\"> ";
	}
	$shc .= "<input class=\"button\" type=\"button\" value=\"link\" onclick=\"addtext('[link][/link]')\">
<input class=\"button\" type=\"button\" value=\"b\" onclick=\"addtext('[b][/b]')\">
<input class=\"button\" type=\"button\" value=\"i\" onclick=\"addtext('[i][/i]')\">
<input class=\"button\" type=\"button\" value=\"u\" onclick=\"addtext('[u][/u]')\">
<input class=\"button\" type=\"button\" value=\"img\" onclick=\"addtext('[img][/img]')\">
<input class=\"button\" type=\"button\" value=\"center\" onclick=\"addtext('[center][/center]')\">
<input class=\"button\" type=\"button\" value=\"left\" onclick=\"addtext('[left][/left]')\">
<input class=\"button\" type=\"button\" value=\"right\" onclick=\"addtext('[right][/right]')\">
<input class=\"button\" type=\"button\" value=\"blockquote\" onclick=\"addtext('[blockquote][/blockquote]')\">";
	return $shc;
}
?>