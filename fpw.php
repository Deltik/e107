<?php

/*
+---------------------------------------------------------------+
|	e107 website system
|	/fpw.php
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
require_once(HEADERF);

if(e_QUERY){
	if($sql -> db_Select("user", "*", "user_sess='".e_QUERY."' ")){
		$row = $sql -> db_Fetch(); extract($row);
		$sql -> db_Update("user", "user_password='$user_sess', user_sess='' WHERE user_id='$user_id' ");
		setcookie('userkey', '', 0, '/', '', 0);
		$_SESSION["userkey"] = "";
		$ns -> tablerender(LAN_03, "<div style='text-align:center'>".LAN_217."</div>");
		require_once(FOOTERF);
		exit;
	}
}

if(IsSet($_POST['pwsubmit'])){
	$email = $_POST['email'];
	if($sql -> db_Select("user", "*", "user_email='".$_POST['email']."' ")){

		$row = $sql -> db_Fetch();
		extract($row);
		$pwlen = rand(6, 12);
		for($a=0; $a<=$pwlen;$a++){
			$newpw .= chr(rand(97, 122));
		}

		$mdnewpw = md5($newpw);

		$sql -> db_Update("user", "user_sess='$mdnewpw' WHERE user_email='".$_POST['email']."' ");
		$returnaddress = (substr(SITEURL, -1) == "/" ? SITEURL."fpw.php" : SITEURL."/fpw.php");
		$message = LAN_215.$newpw."\n\n".LAN_216."\n\n".$returnaddress."?".$mdnewpw;

		require_once(e_HANDLER."mail.php");
		if(sendemail($_POST['email'], "Password reset from ".SITENAME, $message)){
			$text = "<div style='text-align:center'>".LAN_01."</div>";
		}else{
			$text = "<div style='text-align:center'>".LAN_02."</div>";
		}

		$ns -> tablerender(LAN_03, $text);
		require_once(FOOTERF);
		exit;
	}else{
		$text = LAN_213;
		$ns -> tablerender(LAN_214, "<div style='text-align:center'>".$text."</div>");
	}
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>\n
<table style='width:85%' class='fborder'>

<tr>
<td class='forumheader3' colspan='2' style='text-align:center'>".LAN_05."</td>
</tr>

<tr>
<td class='forumheader3' style='width:20%'>".LAN_112."</td>
<td class='forumheader3' style='width:80%' style='text-align:center'>
<input class='tbox' type='text' name='email' size='60' value='' maxlength='100' />
</td>
</tr>

</tr>
<tr style='vertical-align:top'> 
<td class='forumheader' colspan='2'  style='text-align:center'>
<input class='button' type='submit' name='pwsubmit' value='".LAN_156."' />
</td>
</tr>
</table>
</form>
</div>";

$ns -> tablerender(LAN_03, $text);

require_once(FOOTERF);
?>