<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/signup.php
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

if($pref['user_reg'][1] == 0){ header("location:index.php"); }

if(IsSet($_POST['register'])){

	if($sql -> db_Select("user", "*", "user_name='".$_POST['name']."' ")){
		$error = LAN_104."<br />";
	}
	
	if($_POST['password1'] != $_POST['password2']){
		$error .= LAN_105."<br />";
	}

	if($_POST['name'] == "" || $_POST['password1'] =="" || $_POST['password2'] = ""){
		$error .= LAN_185."<br />";
	}

	 if(!preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $_POST['email'])){
		 $error .= LAN_106;
	 }

	 if (preg_match('#^www\.#si', $_POST['website'])) {
		$_POST['website'] = "http://$homepage";
	}else if (!preg_match('#^[a-z0-9]+://#si', $_POST['website'])){
		$_POST['website'] = ""; 
    }

	if($error == ""){
	
		$fp = new floodprotect;
		if($fp -> flood("user", "user_join") == FALSE){
			header("location:index.php");
			die();
		}
	
		$ip = getip();
		$sql -> db_Insert("user", "0, '".$_POST['name']."', '".md5($_POST['password1'])."', '', '".$_POST['email']."', 	'".$_POST['website']."', '".$_POST['icq']."', '".$_POST['aim']."', '".$_POST['msn']."', '".$_POST['location']."', '".$_POST['birthday']."', '".$_POST['signature']."', '".$_POST['image']."', '".$_POST['timezone']."', '".$_POST['hideemail']."', '".time()."', '0', '".time()."', '0', '0', '0', '0', '".$ip."', '0', '0', '', '', '', '0' ");
	}
}

require_once(HEADERF);

if($error != ""){
	$ns -> tablerender("<div style=\"text-align:center\">".LAN_20."</div>", $error);
	require_once(FOOTERF);
	exit;
}

$qs = $_SERVER['QUERY_STRING'];

if($qs == "stage2"){
	$text = "<div style=\"text-align:center\">".LAN_107."</div>";
	$ns -> tablerender("<div style=\"text-align:center\">".LAN_108."</div>", $text);
	require_once(FOOTERF);
	exit;
}

if($pref['use_coppa'][1] == 1 && !ereg("stage", $qs)){
	$text = LAN_109."</b></div>";
	$ns -> tablerender("<div style=\"text-align:center\">".LAN_110."</div>", $text);
	require_once(FOOTERF);
	exit;
}

if(!$website){
	$website = "http://";
}

$text = "
<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."?stage2\"  name=\"signupform\">\n
<table style=\"width:95%\">
<tr>
<td style=\"width:20%\"><u>".LAN_7."</u>:</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"name\" size=\"40\" value=\"$name\" maxlength=\"100\" />
</td>
</tr>

<tr>
<td style=\"width:20%\"><u>".LAN_17."</u>:</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"password\" name=\"password1\" size=\"40\" value=\"\" maxlength=\"20\" /> (case sensitive)
</td>
</tr>

<tr>
<td style=\"width:20%\"><u>".LAN_111."</u>:</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"password\" name=\"password2\" size=\"40\" value=\"\" maxlength=\"20\" /> (case sensitive)
</td>
</tr>

<tr>
<td style=\"width:20%\"><u>".LAN_112."</u>:</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"email\" size=\"60\" value=\"$email\" maxlength=\"100\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_113."</td>
<td style=\"width:80%\">";
if($hide_email == 1){
	$text .= "<input type=\"checkbox\" name=\"hideemail\" value=\"1\"  checked>";
}else{
	$text .= "<input type=\"checkbox\" name=\"hideemail\" value=\"1\">";
}

$text .= LAN_114."</td></tr><tr>
<td style=\"width:20%\">Website:</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"website\" size=\"60\" value=\"$website\" maxlength=\"150\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_115."</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"icq\" size=\"20\" value=\"$icq\" maxlength=\"10\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_116."</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"aim\" size=\"30\" value=\"$aim\" maxlength=\"100\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_117."</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"msn\" size=\"30\" value=\"$msn\" maxlength=\"100\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_118."</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"birthday\" size=\"12\" value=\"$birthday\" maxlength=\"20\" /> (yyyy/mm/dd)
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_119."</td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"location\" size=\"60\" value=\"$location\" maxlength=\"200\" />
</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_120."</td>
<td style=\"width:80%\">
<textarea class=\"tbox\" name=\"signature\" cols=\"70\" rows=\"4\">$signature</textarea>
</td>
</tr>

<tr>
<td style=\"width:20%; vertical-align:top\">".LAN_121."<br /><span class=\"smalltext\">(Type path or choose avatar)</span></td>
<td style=\"width:80%\">
<input class=\"tbox\" type=\"text\" name=\"image\" size=\"60\" value=\"$image\" maxlength=\"100\" />

<input class=\"button\" type =\"button\" style=\"\"width: 35px\"; cursor:hand\" size=\"30\" value=\"Choose avatar\" onClick=\"expandit(this)\">
<div style=\"display:none\" style=&{head};>";
$avatarlist[0] = "";
$handle=opendir("themes/shared/avatars/");
while ($file = readdir($handle)){
	if($file != "." && $file != ".."){
		$avatarlist[] = $file;
	}
}
closedir($handle);

for($c=1; $c<=(count($avatarlist)-1); $c++){
	$text .= "<a href=\"javascript:addtext('avatar_$c')\"><img src=\"themes/shared/avatars/".$avatarlist[$c]."\" style=\"border:0\" alt=\"\" />\n";
}

$text .= "<br />
</div>

</td>
</tr>

<tr>
<td style=\"width:20%\">".LAN_122."</td>
<td style=\"width:80%\">
<select name=\"timezone\" class=\"tbox\">\n";

timezone();
$count = 0;
while($timezone[$count]){
	if($timezone[$count] == "GMT"){
		$text .= "<option value=\"".$timezone[$count]."\" selected>(".$timezone[$count].") ".$timearea[$count]."</option>\n";
	}else{
		$text .= "<option value=\"".$timezone[$count]."\">(GMT".$timezone[$count].") ".$timearea[$count]."</option>\n";
	}
	$count++;
}

$text .= "</select>
</td>
</tr>


</tr>
<tr style=\"vertical-align:top\"> 
<td colspan=\"2\"  style=\"text-align:center\">
<input class=\"button\" type=\"submit\" name=\"register\" value=\"".LAN_123."\" />
</td>
</tr>
</table>
</form>
<br />
<br />
<span class=\"smalltext\">
".LAN_10."
</span>
";

$ns -> tablerender(LAN_123, $text);

require_once(FOOTERF);
?>
<script type="text/javascript">
function addtext(sc){
	document.signupform.image.value = sc;
}
</script>