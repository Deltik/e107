<?
/*
+---------------------------------------------------------------+
|        e107 website system
|        /e_PLUGIN."calnder.php
|
|        �Steve Dunstan 2001-2002
|        http://jalist.com
|        stevedunstan@jalist.com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

// get current date information ---------------------------------------------------------------------
define("PAGE_NAME", "Show Calendar");
require_once("../../class2.php");
require_once(HEADERF);
$num = $_POST['num'];

if(IsSet($_POST['viewallevents'])){
Header("Location: ".e_PLUGIN."calendar_menu/event.php?".$_POST['enter_new_val']);
}

if(IsSet($_POST['doit'])){
Header("Location: ".e_PLUGIN."calendar_menu/event.php?ne.".$_POST['enter_new_val']);
}



$ec_dir = e_PLUGIN."calendar_menu/";
$lan_file = $ec_dir."languages/".e_LANGUAGE.".php";
include(file_exists($lan_file) ? $lan_file : e_PLUGIN."calendar_menu/languages/English.php");
 // new part by cam.
   $qs = explode(".", e_QUERY);
    $action = $qs[0];
    if ($action == "") {
        $datearray = getdate();
        $month = $datearray['mon'];
        $year = $datearray['year'];
        $day = $datearray['day'];
    } else {
        $datearray = getdate($action);
        $month = $datearray['mon'];
        $year = $datearray['year'];
    }



// set up arrays for calender display ------------------------------------------------------------------
$week = Array('S','M','T','W','T','F','S');
$months = Array(EC_LAN_0,EC_LAN_1,EC_LAN_2,EC_LAN_3,EC_LAN_4,EC_LAN_5,EC_LAN_6,EC_LAN_7,EC_LAN_8,EC_LAN_9,EC_LAN_10,EC_LAN_11);
$monthabb = Array(EC_LAN_JAN,EC_LAN_FEB,EC_LAN_MAR,EC_LAN_APR,EC_LAN_MAY,EC_LAN_JUN,EC_LAN_JUL,EC_LAN_AUG,EC_LAN_SEP,EC_LAN_OCT,EC_LAN_NOV,EC_LAN_DEC);
$calendar_title = "<a href='".e_PLUGIN."calendar_menu/event.php' class='mmenu'>".$months[$datearray[mon]-1]." ".$current_year."</a>";
// -----------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------

// show events-------------------------------------------------------------------------------------------
// get first and last days of month in unix format---------------------------------------------------
$monthstart= mktime(0,0,0,$month,1,$year);
$firstdayarray = getdate($monthstart);
$monthend = mktime(0,0,0,$month+1,1,$year);
$lastdayarray = getdate($monthend);
// ----------------------------------------------------------------------------------------------------------


// echo current month with links to previous/next months ----------------------------------------

$prevmonth = ($month-1);
$prevyear = $year;
if ($prevmonth == 0) {
    $prevmonth = 12;
    $prevyear = ($year-1);
}
$previous = mktime(0,0,0,$prevmonth,1, $prevyear);

$nextmonth = ($month+1);
$nextyear = $year;
if ($nextmonth == 13) {
    $nextmonth = 1;
    $nextyear = ($year+1);
}
$next = mktime(0,0,0,$nextmonth,1, $nextyear);
$py = $year-1;
$prevlink = mktime(0,0,0,$month,1, $py);
$ny = $year+1;
$nextlink = mktime(0,0,0,$month,1, $ny);
echo "<table style='width:100%' class='fborder'>
<tr>
<td class='forumheader' style='width:18%; text-align:left'><span class='defaulttext'><a href='".e_SELF."?".$previous."'><< ".$months[($prevmonth-1)]."</a></span></td>
<td class='fcaption' style='width:64%; text-align:center' class='mediumtext'><b>".$months[($month-1)]." ".$year."</b></td>
<td class='forumheader' style='width:185%; text-align:right'><span class='defaulttext'><a href='".e_SELF."?".$next."'> ".$months[($nextmonth-1)]." >></a></span> </td>
</tr><tr><td colspan='3'></td></tr><tr>
<td class='forumheader' style='text-align:left'><a href='calendar.php?".$prevlink."'><< ".$py."</a></td>
<td class='fcaption' style='text-align:center; vertical-align:middle'>";
for ($ii = 0; $ii < 13; $ii++){  
	$m = $ii+1;
	$monthjump= mktime(0,0,0,$m,1,$year);
	echo  "<a class='forumlink' href=\"calendar.php?".$monthjump."\">".$monthabb[$ii]."</a> ";
}
echo "<td class='forumheader' style='text-align:right'><a href='calendar.php?".$nextlink."'>".$ny." >></a></td></td></tr></table>";
echo "<div style='text-align:center'>
";


$prop = mktime(0,0,0,$month, 1, $year);

$nowarray = getdate();
$nowmonth = $nowarray['mon'];
$nowyear = $nowarray['year'];
$nowday = $nowarray['mday'];
$current = mktime(0,0,0,$nowmonth, 1, $nowyear);

##### Check for access.


//------------my test stuff------------------------------------------------------



echo "<br /><table border='0' cellpadding='2' cellspacing='3' class='forumheader3'>
<tr><td align=right><form method=post action=".e_SELF."?".e_QUERY.">
<select name='event_cat_ids' class='tbox' style='width:140px; '>
<option value='all'>All</option>";

 $event_cat_id = !isset($_POST['event_cat_ids'])? NULL : $_POST['event_cat_ids'];
        $sql -> db_Select("event_cat");

        while($row = $sql -> db_Fetch()){
                extract($row);
                if($event_cat_id == $_POST['event_cat_ids']){
           //if($event_cat_id == $qs[1]){
                        echo "<option value='$event_cat_id' selected>".$event_cat_name."</option>";
                }else{
                        echo "<option value='$event_cat_id'>".$event_cat_name."</option>";
                }
        }
echo "</td></select><td align='center'>
<input class='button' type='submit' style='width:140px;' name='viewallevents' value='View Events List'>
</td></tr>
<tr><td align='right'><input type='hidden' name='do' value='vc'>
<input class='button' type='submit' style='width:140px;' name='viewcat' value='View Category'>
</td><td align=center><input type='hidden' name='enter_new_val' value='".$prop."'> ";

  if($pref['eventpost_admin'][1] == 1 ){    // start admin preference activated.
 if(ADMIN == TRUE){
  echo "

  <input class='button' type='submit' style='width:140px;' name='doit' value='Enter New Event'>
   ";   }
   }     // end admin preference activated.

     if(!$pref['eventpost_admin'][1] == 1 ){  // start no admin preference
   if(USER == TRUE){ echo "
   <input type='hidden' name='enter_new_val' value='".$prop."'>
   <input class='button' type='submit' style='width:140px;' name='doit' value='Enter New Event'>
   ";}
     }    // end no admin preference


echo "</form></tr></table><br />";

//--------------------------------------------------------------------------------


if ($month != $nowmonth || $year != $nowyear) {
    echo " <span class='button' style='width:120px; '><a href='".e_SELF."?$current'>".EC_LAN_40."</a></span>";
}

echo "</div><br />";


// get events from current month----------------------------------------------------------------------
$sql -> db_Select("event", "*", "(event_start>='$monthstart' AND event_start<= '$monthend')   ORDER BY event_start ASC");

while ($row = $sql -> db_Fetch()) {
    extract($row);
    $evf = getdate($event_start);
    $tmp = $evf['mday'];
	$cevent_title[$tmp] =  $event_title;
    $event_true[$tmp] = $event_category;
}
// -----------------------------------------------------------------------------------------------------------

$start = $monthstart;
$text .= "<div style='text-align:center'>
<table cellpadding='0' cellspacing='1' class='fborder' style='background-color:#DDDDDD; width:580px'><tr>";


foreach($week as $day){
    $text .= "<td class='fcaption' style='z-index: -1;background-color:black; width:90px;height:20px;text-align:center'><strong>".$day."</strong><img src='".THEME."images/blank.gif' height='12%' width='14%'></td>";
}
$text .= "</tr><tr >";
$calmonth = $datearray['mon'];
$calday = $datearray['mday'];
$calyear = $datearray['year'];

for ($c=0; $c<$firstdayarray['wday']; $c++) {
    $text .= "<td style=' width:90px;height:60px;'></td>";
}
$loop = $firstdayarray['wday'];
for ($c=1; $c<=32; $c++) {

        $dayarray = getdate($start+($c*84460));


	$stopp = mktime(24,0,0,$calmonth,$c,$calyear);
	$startt = mktime(0,0,0,$calmonth,$c,$calyear);


	$sql2 = new db;
	$sql2 -> db_Select("event_cat", "*", "event_cat_id='".$event_true[($c)]."' ");
	$event_cat = $sql2 -> db_Fetch();
	extract($event_cat);
	$sql -> db_Select("event", "*", "event_start>='$startt' AND event_start<='$stopp' ORDER BY event_start");
	$events = $sql -> db_Rows();

     // Highlight the current day.
    if ($dayarray['mon'] == $calmonth) {
        if ($nowday == $c && $calmonth == $nowmonth && $calyear == $nowyear) {
            $text .="<td  class='forumheader2' style='z-index: 1;vertical-align:top; width:90px;height:90px;padding-bottom:0px;padding-right:0px; margin-right:0px'>";
            $text .="<span class='smalltext' style='z-index: 2; position:relative; top:1px; height:10px;padding-right:0px'><a style='background-color:black' href='".e_PLUGIN."calendar_menu/event.php?".$startt.".one'><strong>".$c."</a> [today]</strong></span>";
        } else {
            $text .="<td class='forumheader2 ' style='z-index: 1;vertical-align:top;  width:90px;height:90px;padding-bottom:0px;padding-right:0px; margin-right:0px'>";
            $text .="<span style='z-index: 2; position:relative; top:1px; height:10px;padding-right:0px'><a href='".e_PLUGIN."calendar_menu/event.php?".$startt.".one'><strong>".$c."</strong></a></span>";
        }

	while($row = $sql -> db_Fetch()){
		extract($row);
		if (strlen($event_title) > 9){
			$oevent_title = substr($event_title,0,10)."<br />".substr($event_title,10,9);
			if (strlen($event_title) > 15){
				$oevent_title .= "..";
			}
		} else {
			$oevent_title = $event_title;
		}

	        if ($event_true[($c)]) {
	            $linkut = mktime(0 ,0 ,0 ,$datearray['mon'], $c, $datearray['year']);
		    if(($_POST['do'] == NULL || $_POST['event_cat_ids'] == "all") || ($_POST['event_cat_ids'] == $event_cat_id)){

		            $text .="<span class='smalltext' style='background-color:#CBD0DF; border:1px solid #808080; position: relative; z-index: 1; top: -18px; left:-2px; height:96%; width:100% ; padding-top:5px;padding-right:0px;padding-bottom:0px ' ><div style='padding-top:15px; padding-left:3px'><img style='border:0' src='".$ec_dir."images/".$event_cat_icon."' alt='' height='8' width='8'><a href='".e_PLUGIN."calendar_menu/event.php?".$linkut.".one'><span class='smalltext' style='color:black' > ".$oevent_title."</span></a></span></div>";
		            }
		}

       }

    }
    $text .= "</td>\n";

    $loop++;
    if ($loop == 7) {
        $loop = 0;
        $text .= "</tr><tr>";
    }
}


$text .= "</tr></table></div>";

 echo $text;
require_once(FOOTERF);
?>