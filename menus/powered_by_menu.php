<?php
$text = "
<div style='text-align:center'>
<a href='http://e107.org' onclick='window.open(\"http://e107.org\"); return false;'><img src='".e_BASE."button.png' alt='e107' style='border:0' /></a>
<br />
<a href='http://php.net' onclick='window.open(\"http://php.net\"); return false;'><img src='".e_BASE."files/images/php-small-trans-light.gif' alt='PHP' style='border:0' /></a>
<br />
<a href='http://mysql.com' onclick='window.open(\"http://mysql.com\"); return false;'><img src='".e_BASE."files/images/poweredbymysql-88.png' alt='mySQL' style='border:0' /></a>
</div>";
$ns -> tablerender("Powered by",  $text);
?>