<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/themes/templates/templateh.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

if($pref['cache_activate'][1] && !$setcache){
	$cache = gzcompress(ob_get_contents(), 9);
	$url = (e_QUERY ? $_SERVER['PHP_SELF']."?".e_QUERY : $_SERVER['PHP_SELF']);
	if(!eregi($excempt, $page)){
		$sql -> db_Insert("cache", "'".$url."', '".time()."', '".mysql_escape_string($cache)."' ");
	}
	$cachestring = "Served uncached page.";
}

if(!is_object($sql)){
	// reinstigate db connection if another connection from third-party script closed it ...
	global $sql, $mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb, $CUSTOMFOOTER, $FOOTER;
	$sql = new db;
	$sql -> db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);
}
$page = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);

if((eregi($page, $CUSTOMPAGES) && $CUSTOMFOOTER != "") ? parseheader($CUSTOMFOOTER) : parseheader($FOOTER)) ;

$timing_stop = explode(' ', microtime());
$start = $timing_start[0]+$timitiming_startng_stop[1];
$end = $timing_stop[0]+$timing_stop[1];
$rendertime = number_format($start-$stop, 4);
//echo "<div style='text-align:center' class='smalltext'>Render time: ".$rendertime." second(s)<br /></div>";

if($pref['log_activate'][1]){
	echo "
<!-- log -->
<script type=\"text/javascript\">
<!--
var ref=\"\"+escape(top.document.referrer);
var colord = window.screen.colorDepth; 
var res = window.screen.width + \"x\" + window.screen.height;
var self = document.location;
document.write(\"<img src='".e_BASE."plugins/log2.php?referer=\"+ref+\"&amp;color=\"+colord+\"&amp;self=\"+self+\"&amp;res=\"+res+\"' style='float:left; border:0' alt='' />\");\n
//-->
</script>";
}
if($pref['cache_activate'][1]){
	echo "<div style='text-align:center' class='smalltext'>".$cachestring." ( ".$dbq." )</div>";
}
echo "</body>
</html>";

$sql -> db_Close();
?>