<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/fpw.php,v $
|     $Revision: 1.13 $
|     $Date: 2006/01/31 03:07:19 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");

if(USER){
	header("location:".e_BASE."index.php");
	exit;
}

$use_imagecode = ($pref['fpwcode'] && extension_loaded("gd"));
if ($use_imagecode) {
	require_once(e_HANDLER."secure_img_handler.php");
	$sec_img = new secure_image;
}



if ($pref['membersonly_enabled']) {
	if (!$FPW_TABLE_HEADER) {
		if (file_exists(THEME."fpw_template.php")) {
			require_once(THEME."fpw_template.php");
		} else {
			require_once(e_BASE.$THEMES_DIRECTORY."templates/fpw_template.php");
		}
	}
	$HEADER = preg_replace("/\{(.*?)\}/e", '$\1', $FPW_TABLE_HEADER);
	$FOOTER = preg_replace("/\{(.*?)\}/e", '$\1', $FPW_TABLE_FOOTER);
}

require_once(HEADERF);

function fpw_error($txt) {
	global $ns;
	$ns->tablerender(LAN_03, "<div style='text-align:center'>".$txt."</div>");
	require_once(FOOTERF);
	exit;
}

if (e_QUERY) {
	$tmp = explode(".", e_QUERY);
	$tmpinfo = preg_replace("#[\W_]#", "", $tp -> toDB($tmp[0], true));
	if ($sql->db_Select("tmp", "*", "tmp_info LIKE '%.{$tmpinfo}' ")) {
		$row = $sql->db_Fetch();
		extract($row);
		$sql->db_Delete("tmp", "tmp_info LIKE '%.{$tmpinfo}' ");
		$newpw = "";
		$pwlen = rand(8, 12);
		for($a = 0; $a <= $pwlen; $a++) {
			$newpw .= chr(rand(97, 122));
		}
		$mdnewpw = md5($newpw);

		list($username, $md5) = explode(".", $tmp_info);
		$sql->db_Update("user", "user_password='$mdnewpw', user_viewed='' WHERE user_name='".$tp -> toDB($username, true)."' ");
		cookie($pref['cookie_name'], "", (time()-2592000));
		$_SESSION[$pref['cookie_name']] = "";

		$txt = LAN_FPW8." {$username} ".LAN_FPW9." {$newpw}<br /><br />".LAN_FPW10." <a href='".e_BASE."index.php'>".LAN_FPW11."</a> ".LAN_FPW12;
		fpw_error($txt);

	} else {
		fpw_error(LAN_FPW7);
	}
}

if (isset($_POST['pwsubmit'])) {
	require_once(e_HANDLER."mail.php");
	$email = $_POST['email'];

	if ($pref['fpwcode'] && extension_loaded("gd")) {
		if (!$sec_img->verify_code($_POST['rand_num'], $_POST['code_verify'])) {
			fpw_error(LAN_FPW3);
		}
	}
	
	$clean_email = check_email($tp -> toDB($_POST['email']));
	$clean_username = $tp -> toDB($_POST['username']);
	if ($sql->db_Select("user", "*", "user_email='{$clean_email}' AND user_loginname='{$clean_username}' ")) {
		$row = $sql->db_Fetch();
		 extract($row);

		if ($user_admin == 1 && $user_perms == "0") {
			sendemail($pref['siteadminemail'], LAN_06, LAN_07."".$e107->getip()." ".LAN_08);
			echo "<script type='text/javascript'>document.location.href='index.php'</script>\n";
			die();
		}

		if ($sql->db_Select("tmp", "*", "tmp_ip = 'pwreset' AND tmp_info LIKE '{$user_name}.%'")) {
			fpw_error(LAN_FPW4);
			exit;
		}

		mt_srand ((double)microtime() * 1000000);
		$maxran = 1000000;
		$rand_num = mt_rand(0, $maxran);
		$datekey = date("r");
		$rcode = md5($_SERVER['HTTP_USER_AGENT'] . serialize($pref). $rand_num . $datekey);

		$link = SITEURL."fpw.php?{$rcode}";
		$message = LAN_FPW5." ".SITENAME." ".LAN_FPW14." : ".$e107->getip().".\n\n".LAN_FPW15."\n\n".LAN_FPW16."\n\n".LAN_FPW17."\n\n{$link}";
		//  $message = LAN_FPW5."\n\n{$link}";

		$deltime = time()+86400 * 2;
		//Set timestamp two days ahead so it doesn't get auto-deleted
		$sql->db_Insert("tmp", "'pwreset',{$deltime},'{$user_name}.{$rcode}'");

		if (sendemail($_POST['email'], "".LAN_09."".SITENAME, $message)) {
			$text = "<div style='text-align:center'>".LAN_FPW6."</div>";
		} else {
			$text = "<div style='text-align:center'>".LAN_02."</div>";
		}

		$ns->tablerender(LAN_03, $text);
		require_once(FOOTERF);
		exit;
	} else {
		$text = LAN_213;
		$ns->tablerender(LAN_214, "<div style='text-align:center'>".$text."</div>");
	}
}


if ($use_imagecode) {
	$FPW_TABLE_SECIMG_LAN = LAN_FPW2;
	$FPW_TABLE_SECIMG_HIDDEN = "<input type='hidden' name='rand_num' value='".$sec_img->random_number."'>";
	$FPW_TABLE_SECIMG_SECIMG = $sec_img->r_image();
	$FPW_TABLE_SECIMG_TEXTBOC = "<input class='tbox' type='text' name='code_verify' size='15' maxlength='20'>";
}

if (!$FPW_TABLE) {
	if (file_exists(THEME."fpw_template.php")) {
		require_once(THEME."fpw_template.php");
	} else {
		require_once(e_BASE.$THEMES_DIRECTORY."templates/fpw_template.php");
	}
}
$text = preg_replace("/\{(.*?)\}/e", '$\1', $FPW_TABLE);

$ns->tablerender(LAN_03, $text);
require_once(FOOTERF);

?>