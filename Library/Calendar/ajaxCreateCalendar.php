<?php
global $FE;
global $sessionHandler; 
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','view');
if($sessionHandler->getSessionVariable('RoleId') == 5){
UtilityManager::ifManagementNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
require_once(MODEL_PATH . "/CalendarManager.inc.php");

//Purpose:Used to compose calendar date according to mysql date format
//Author:Dipanjan Bhattacharjee
//Date:03.09.2008
function dateCompose($y,$m,$d){
 return ($y."-".(intval($m)<10?"0".intval($m):$m)."-".(intval($d)<10?"0".intval($d):$d)); 
}

//Purpose:To check whether a specific date falls between two given dates
//Author:Dipanjan Bhattacharjee
//Date:03.09.2008
function dateCheck($dformat, $beginDate,$endDate,$middleDate)
{
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $date_parts3=explode($dformat, $middleDate);
    $start_date  = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
    $end_date    = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
    $middle_date = gregoriantojd($date_parts3[1], $date_parts3[2], $date_parts3[0]);

    if(($middle_date - $start_date)>=0 and ($end_date - $middle_date)>=0){
      return 1;
    }
    else{
        return 0;
    }
}


// This year
$y = date('Y');
// This month
$m = date('n');
// This Day
$d = date('j');
$today = array('day'=>$d, 'month'=>$m, 'year'=>$y);
// If user specify Day, Month and Year, reset the var
if (isset($_GET['m'])) {
    $y = $_GET['y'];
    $m = $_GET['m'];
}

$links = array();
/*
if($m<10){
         $foundArray = CalendarManager::getInstance()->getEvent(" AND startDate like '$y-0$m-%' ");
}
else{
        $foundArray = CalendarManager::getInstance()->getEvent(" AND startDate like '$y-$m-%' ");
}
*/

$daysMonth=date("t",mktime(0,0,0,$m,1,$y));

$M=(intval($m)<10?"0".intval($m):$m);

//build the query
$qstr="AND ( (startDate BETWEEN '$y-$M-01' AND '$y-$M-$daysMonth')
OR 
  (endDate between '$y-$M-01' AND '$y-$M-$daysMonth')
OR
( startDate <= '$y-$M-01' AND endDate>= '$y-$M-$daysMonth') )";
//event resultset
$foundArray = CalendarManager::getInstance()->getEvent($qstr);

//colour array
$colours = array(
                 /*
                 "#feeeff","#ffff99","#00ccff","#66ff99","#ffcc00","#d7ebff","#ccffcc",
                 "#7CB0A1","#6A5D1B","#714693","#E32636","#907B71","#AF8F2C","#A9ACB6",
                 "#3B7A57","#A397B4","#7FFFD4","#564FEC","#97CD2D","#97605D","#FFA824",
                 "#F7C8DA","#DA6304","#961C7F","#CAE00D"
                 */
                 "#387094","#4384AF","#659EC5","#88B5D2","#B7D1E3","#E0ECF3","#F4F9FB",
                 "#5F5F5F","#6E6E6E","#8F8F8F","#A7A7A7","#C3C3C3","#D1D1D1",
                 "#770000","#A80000","#E80000","#FF3333","#FF7777","#FFAAAA","#FFCACA","#FFDFDF"
                );
$max = count($colours);


if(is_array($foundArray) && count($foundArray)>0 ) {  
    $cnt=sizeof($foundArray);
    $date=array();
    for($i=0;$i<$cnt;$i++){
        $date=strip_slashes(split("-",$foundArray[$i]['startDate']));
        $cnumber = rand(1,$max); //choose random color
        $links[] = array( 'day'        =>$date[2], 
                          'month'      =>$date[1], 
                          'year'       =>$date[0], 
                          'startDate'  =>strip_slashes($foundArray[$i]['startDate']),
                          'endDate'    =>strip_slashes($foundArray[$i]['endDate']),
                          'rcolor'     =>'bgcolor="'.$colours[$cnumber - 1].'" title="'.htmlentities($foundArray[$i]['eventTitle']).'"',
                          'eventTitle' =>htmlentities(strip_slashes($foundArray[$i]['eventTitle']))
                        );
    }
}

//print_r($links);

// name of css
$css = 'pcalendar';

// Location of the calendar script file from the root
$ajaxPath = HTTP_LIB_PATH . "/Calendar/ajaxCreateCalendar.php";
// if called via ajax, dont display style sheet and javascript again
if (!isset($_GET['ran'])) {
?>

<script language="javascript">

function createQCObject() { 
   var req; 
   if(window.XMLHttpRequest){ 
      // Firefox, Safari, Opera... 
      req = new XMLHttpRequest(); 
   } else if(window.ActiveXObject) { 
      // Internet Explorer 5+ 
      req = new ActiveXObject("Microsoft.XMLHTTP"); 
   } else { 
      alert('Problem creating the XMLHttpRequest object'); 
   } 
   return req; 
} 

// Make the XMLHttpRequest object 
var http = createQCObject(); 

function displayQCalendar(m,y) {
    
   var ran_no=(Math.round((Math.random()*9999))); 
    http.open('get', '<?php echo $ajaxPath; ?>?m='+m+'&y='+y+'&ran='+ran_no);

       http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) { 
              var response = http.responseText;
              if(response) { 
                document.getElementById("quickCalender").innerHTML = http.responseText; 
              } 
           } 
    } 
       http.send(null); 
}
</script>
<?php 
}
?>
<?php
class CreateQCalendarArray {

    var $daysInMonth;
    var $weeksInMonth;
    var $firstDay;
    var $week;
    var $month;
    var $year;

    function CreateQCalendarArray($month, $year) {
        $this->month = $month;
        $this->year = $year;
        $this->week = array();
        $this->daysInMonth = date("t",mktime(0,0,0,$month,1,$year));
        // get first day of the month
        $this->firstDay = date("w", mktime(0,0,0,$month,1,$year));
        $tempDays = $this->firstDay + $this->daysInMonth;
        $this->weeksInMonth = ceil($tempDays/7);
        $this->fillArray();
    }
    
    function fillArray() {
        // create a 2-d array
        for($j=0;$j<$this->weeksInMonth;$j++) {
            for($i=0;$i<7;$i++) {
                $counter++;
                $this->week[$j][$i] = $counter; 
                // offset the days
                $this->week[$j][$i] -= $this->firstDay;
                if (($this->week[$j][$i] < 1) || ($this->week[$j][$i] > $this->daysInMonth)) {    
                    $this->week[$j][$i] = "";
                }
            }
        }
    }
}

class QCalendar {
    
    var $html;
    var $weeksInMonth;
    var $week;
    var $month;
    var $year;
    var $today;
    var $links;
    var $css;

    function QCalendar($cArray, $today, &$links, $css='') {
        $this->month = $cArray->month;
        $this->year = $cArray->year;
        $this->weeksInMonth = $cArray->weeksInMonth;
        $this->week = $cArray->week;
        $this->today = $today;
        $this->links = $links;
        $this->css = $css;
        $this->createHeader();
        $this->createBody();
        $this->createFooter();
    }
    
    function createHeader() {
          $header = date('M', mktime(0,0,0,$this->month,1,$this->year)).' '.$this->year;
          $nextMonth = $this->month+1;
          $prevMonth = $this->month-1;
          // thanks adam taylor for modifying this part
        switch($this->month) {
            case 1:
                   $lYear = $this->year;
                   $pYear = $this->year-1;
                   $nextMonth=2;
                   $prevMonth=12;
                   break;
              case 12:
                   $lYear = $this->year+1;
                   $pYear = $this->year;
                   $nextMonth=1;
                   $prevMonth=11;
                  break;
              default:
                  $lYear = $this->year;
                   $pYear = $this->year;
                  break;
          }
        // --
        
        $this->html = "<table cellspacing='0' border='0' width=\"100%\"  cellpadding='0' class='$this->css' >
        <tr>
        <th class='header'>&nbsp;<a href='#'  onclick=\"show_div(0,'$this->month','".($this->year-1)."');displayQCalendar('$this->month','".($this->year-1)."')\" class='headerNav' title='Prev Year'>&lt;<</a></th>
        <th class='header'>&nbsp;<a href='#'  onclick=\"show_div(0,'$prevMonth','$pYear');displayQCalendar('$prevMonth','$pYear')\" class='headerNav' title='Prev Month'><</a></th>
        <th colspan='3' class='header'>$header</th>
        <th class='header'><a  href='#' onclick=\"show_div(0,'$nextMonth','$lYear');displayQCalendar('$nextMonth','$lYear')\" class='headerNav' title='Next Month'>></a>&nbsp;</th>
        <th class='header'>&nbsp;<a  href='#' onclick=\"show_div(0,'$this->month','".($this->year+1)."');displayQCalendar('$this->month','".($this->year+1)."')\"  class='headerNav' title='Next Year'>>></a></th>
        </tr>";

    }
    
    function createBody(){
        // start rendering table
        $this->html.= "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
        $bg = $bg =='row0' ? 'row1' : 'row0';           
        for($j=0;$j<$this->weeksInMonth;$j++) {
            $this->html.= "<tr class='$bg'>";
            for ($i=0;$i<7;$i++) {
                $cellValue = $this->week[$j][$i];
                // if today
                if (($this->today['day'] == $cellValue) && ($this->today['month'] == $this->month) && ($this->today['year'] == $this->year)) {
                    $cell = "<div class='today'>$cellValue</div>";
                }
                // else normal day
                else {
                    $cell = "$cellValue";
                }
                // if days with link
               /*
                foreach ($this->links as $val) {
                    if (($val['day'] == $cellValue) && (($val['month'] == $this->month) || ($val['month'] == '*')) && (($val['year'] == $this->year) || ($val['year'] == '*'))) {
                        $cell = "<div class='link' title='{$val['eventTitle']}'>$cellValue</div>";
                        break;
                    }
                }
               */ 
               //bgcolor of the <td></td>
                $bg="";
                $fl=1;            
                foreach ($this->links as $val) {
                    //checking for startDate.If startDate mathes the hilight the cell
                    if ($val['startDate'] == dateCompose($this->year,$this->month,$cellValue) and $fl) {
                        $cell = "<div class='link' title='".htmlentities($val['eventTitle'])."'>$cellValue</div>";
                        $fl=0; //this is done so that if will be true for the first event(Most recent event)
                    }
                   //checking for intermediate date.if the date falls in the range change bg
                   if (dateCheck("-",$val['startDate'],$val['endDate'],dateCompose($this->year,$this->month,$cellValue))) {
                      $bg=$val['rcolor'];
                   } 
                }
                if($cellValue!="" ){ //if day is not empty   
                 $this->html.= "<td $bg align=\"center\" class=\"td1\"><span style=\"cursor:pointer\" onclick=\"show_div({$cellValue},{$this->month},{$this->year});\">$cell</span></td>";
                }
               else{                //if day is  empty
                    $this->html.= "<td $bg align=\"center\" class=\"td1\"><span style=\"cursor:pointer\"></span></td>";
               } 
            }
            $this->html.= "</tr>";
        }    
    }
    
    function createFooter() {
        $this->html .= "<tr><td colspan='7' class='footer'><a onclick=\"displayQCalendar('{$this->today['month']}','{$this->today['year']}')\" class='footerNav'>Today is {$this->today['day']} ".date('M', mktime(0,0,0,$this->today['month'],1,$this->today['year']))." {$this->today['year']}</a></td></tr></table>";
    }
    
    function render() {
        echo $this->html;
    }
}
?>

<?php
// render calendar now
$cArray = new CreateQCalendarArray($m, $y);
$cal = new QCalendar($cArray, $today, $links, $css);
if (!isset($_GET['ran'])) {
    echo "<div id='quickCalender'>";
}
$cal->render();
if (!isset($_GET['ran'])) {
    echo "</div>";
}
?>
