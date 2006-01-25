global $pref;
if (preg_match("#\.php\?.*#",$code_text)){return "";}
global $IMAGES_DIRECTORY, $FILES_DIRECTORY, $e107;
$search = array('"', '{E_IMAGE}', '{E_FILE}');
$replace = array('&#039;', $e107->http_path.$IMAGES_DIRECTORY, $e107->http_path.$FILES_DIRECTORY);
$code_text = str_replace($search, $replace, $code_text);
unset($imgParms);
$imgParms['class']="bbcode";  
$imgParms['alt']='';
$imgParms['style']="vertical-align:middle; border:0";



if($parm) {
	$parm = preg_replace('#onerror *=#i','',$parm);
	$parm = str_replace("amp;", "&", $parm);
	parse_str($parm,$tmp);
	foreach($tmp as $p => $v) {
		$imgParms[$p]=$v;
	}
}
$parmStr="";
foreach($imgParms as $k => $v) {
	$parmStr .= "$k='{$v}' ";
}


if(file_exists(e_IMAGE."newspost_images/".$code_text))
{
	$code_text = e_IMAGE."newspost_images/".$code_text;
}

if (!$postID) {
	return "<img src='{$code_text}' {$parmStr} />";
} else {
	if(strstr($postID,'class:')) {
		$uc = substr($postID,6);
	}
	if ($pref['image_post']) {
		if($uc == '') {
			if (!function_exists('e107_userGetuserclass')) {
				require_once(e_HANDLER.'user_func.php');
			}
			$uc = e107_userGetuserclass($postID);
		}
		if (check_class($pref['image_post_class'],$uc)) {
			return "<img src='{$code_text}' {$parmStr} />";
		}
		else
		{
			return ($pref['image_post_disabled_method'] ? "[ image disabled ]" : "Image: $code_text");
		}
	}
	else
	{
		if ($pref['image_post_disabled_method']) {
			return '[ image disabled ]';
		} else {
			return "Image: $code_text";
		}
	}
}