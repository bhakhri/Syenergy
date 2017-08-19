<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//set_time_limit(0); 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once($FE . "/Library/HtmlFunctions.inc.php");    
define('MODULE','DisplayTimeTableReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();

$htmlFunctionsManager = HtmlFunctions::getInstance();    

//Get the time table date according to class selected
    $conditions = '';
    $str = '';
    
    
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    $timeTableType = $REQUEST_DATA['timeTableType']; 
    
    $conditions .=" AND tt.timeTableLabelId IN (".$timeTableLabelId.")";   
    $countArr='';
    
    global $daysArr;
    $valueDays = "";
    
    if($REQUEST_DATA['reportResult']=='weekWise') {
        $countArr = 1; 
        $str = ' AND tt.daysOfWeek IN ('.$REQUEST_DATA['dayWeeks'].')';
        $arr= explode(',',$REQUEST_DATA['dayWeeks']);
        foreach($daysArr as $key=>$value) {   
          if($valueDays=="") {
             $valueDays = $value;
          }
          else {
             $valueDays .= ", ".$value;
          }
       }
    }
    else {
       if($REQUEST_DATA['dayWeeks']!='') {
         $conditions .=" AND tt.daysOfWeek IN  (".$REQUEST_DATA['dayWeeks'].")";   
       }
    }  
    

    if($REQUEST_DATA['reportResult']=='className') {
        $countArr = explode(",",$REQUEST_DATA['classId']);  
        $str = ' AND cl.classId = ';
    }
    else {
        if($REQUEST_DATA['classId']!='') {
           $conditions .=" AND cl.classId IN (".$REQUEST_DATA['classId'].")";   
        }
    }
    
    if($REQUEST_DATA['reportResult']=='employeeName') {
        $countArr = explode(",",$REQUEST_DATA['employeeId']);  
        $str = ' AND emp.employeeId = ';
    }
    else {
        if($REQUEST_DATA['employeeId']!='') {
           $conditions .=" AND emp.employeeId IN (".$REQUEST_DATA['employeeId'].")";   
        }
    }
    
    if($REQUEST_DATA['reportResult']=='roomName') {
        $countArr = explode(",",$REQUEST_DATA['roomId']); 
        $str = ' AND r.roomId = ';
    }
    else {
        if($REQUEST_DATA['roomId']!='') {
           $conditions .=" AND r.roomId IN (".$REQUEST_DATA['roomId'].")";   
        }
    }
    
    if($REQUEST_DATA['reportResult']=='subjectName') {
        $countArr = explode(",",$REQUEST_DATA['subjectId']); 
        $str = ' AND sub.subjectId = ';
    }
    else {
        if($REQUEST_DATA['subjectId']!='') {
           $conditions .=" AND sub.subjectId IN (".$REQUEST_DATA['subjectId'].")";   
        } 
    }
    
    if($REQUEST_DATA['reportResult']=='groupShort') {
        //$countArr = explode(",",$REQUEST_DATA['sectionId']); 
        //$str = ' AND sc.sectionId = ';
        $countArr = 1; 
        $str = ' AND gr.groupId IN ('.$REQUEST_DATA['groupId'].')';
    }
    else {
        if($REQUEST_DATA['groupId']!='') {
           $conditions .=" AND gr.groupId IN (".$REQUEST_DATA['groupId'].")";   
        }
    }  


    // tdTextBreak
    function tdBreak($arrList) {
       $j=count($arrList); 
       $arrText = '';
       $brChk='';
       $i=-1;
       while($j > 0)  {
         $arrText .= $brChk.substr($arrList,($i+1),150);
         $j-=150;  
         $i+=150;
         $brChk ='<br>';
       }
       if($i<count($arrList))
         $arrText .= $brChk.substr($arrList,($i+1),150);
       return $arrText;
    }

    // Search By 
    function seachBy($teacherRecordArray,$listShow,$valueDays) {
           if($listShow!='weekWise') {
               $arr = UtilityManager::makeCSList($teacherRecordArray, $listShow);   
               $arrList = explode(",",$arr); 
               $arrayUnique = array_unique($arrList);                                  
               $arrList1 = implode(", ",$arrayUnique);
           }
           else {
             $arrList1 = $valueDays; 
           }
           
           $result = '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
               <tr>
                   <td width="4%"  class="contenttab_internal_rows" valign="bottom" style="text-align:left"><nobr><b>Search By&nbsp;:&nbsp;</b></nobr></td>
                   <td width="96%" class="contenttab_internal_rows" valign="bottom" style="text-align:left"><nobr>'.tdBreak($arrList1).'</nobr></td>
               </tr>
           </table>';
           return $result;           
    }
    
    
    $condDate="";
    if($timeTableType==1) {
      if($REQUEST_DATA['reportView']=='1') { 
        $orderBy = ' ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber';            
      }
      else 
      if($REQUEST_DATA['reportView']=='2') { 
        $orderBy = ' ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek';       
      }
    }
    else if($timeTableType==2) {
      $fromDate = $REQUEST_DATA['fromDate'];
      $toDate =   $REQUEST_DATA['toDate'];
        
      $orderBy = ' ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber';            
     
      $condDate = " AND (tt.fromDate BETWEEN  '$fromDate' AND '$toDate') ";
    }
    
    
    
    
    // Fetch Period Arrays
    $periodCondition = " tt.timeTableLabelId = ".$REQUEST_DATA['timeTableLabelId'];
    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');      
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
    <tr>
        <td>
            <div id="scroll2" style="overflow:auto;HEIGHT:350px">
<?php    

    $findTimeTable='';   
    
    for($s=0;$s<count($countArr);$s++) {
        if($REQUEST_DATA['reportResult']=='groupShort') {      
           $cond = $conditions.' '.$str;  
        }
        else if($REQUEST_DATA['reportResult']=='weekWise') {      
           $cond = $conditions.' '.$str;   
        }
        else  {      
           $cond = $conditions.' '.$str .'"'.$countArr[$s].'"';  
        }
        for($ps=0; $ps < count($periodSlotArr); $ps++) {
            $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
            $periodCondition = " tt.timeTableLabelId = ".$REQUEST_DATA['timeTableLabelId']." AND p.periodSlotId = ".$periodSlotId;
            $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
            $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);   
            
            // Period Slot Id check added
            $cond1 = $cond." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." AND ttl.timeTableType = ".$timeTableType;
            if($timeTableType==2) {  
              $cond1 .= $condDate;
            }
            
            if($timeTableType==2) { 
               $fieldName = " DISTINCT fromDate";
               $orderFrom = " ORDER BY fromDate";
               $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
            }
            
            $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);
            $recordCount = count($teacherRecordArray);
            
            if($recordCount >0 && is_array($teacherRecordArray)) {     
               echo seachBy($teacherRecordArray, $REQUEST_DATA['reportResult'],$valueDays);
               if($timeTableType==1) {
                   if($REQUEST_DATA['reportView']=='1') {
                       echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                       echo "<br>";
                   }
                   else 
                   if($REQUEST_DATA['reportView']=='2') {
                      echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                      echo "<br>";
                   }
                   $findTimeTable='1';  
               }
               else if($timeTableType==2) {
                   echo $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                   echo "<br>";
                   $findTimeTable='1';    
               }
            }    
        }
    }
    if($findTimeTable=='') {
      echo "<div align='center'>No record found</div></td></tr></table></div>";
    }
        
/*  else {
       echo '</td></tr></table></div><table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">'; 
       echo '<tr><td height="20"></td></tr><tr>
             <td colspan="'.(count($periodArray)+1).'" align="right"><div id = "saveDiv">';
                //<input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport()"/>&nbsp;&nbsp;
       echo '<input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/download1.gif" onClick="printReportDoc()"/>&nbsp;</div>
            </td></tr></table>'; 
    }
*/    
?>

<?php
//$History: ajaxTimeTableDetailsReport.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/20/10    Time: 5:03p
//Updated in $/LeapCC/Library/TimeTable
//showTimeTable function (daily & weekly base format udpated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/20/10    Time: 11:24a
//Updated in $/LeapCC/Library/TimeTable
//format updated 
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/19/10    Time: 4:47p
//Updated in $/LeapCC/Library/TimeTable
//reportType base code updated
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10-04-10   Time: 4:47p
//Updated in $/LeapCC/Library/TimeTable
//added multiple utility time table in management login 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/19/10    Time: 11:24a
//Updated in $/LeapCC/Library/TimeTable
//set_time_limit added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Library/TimeTable
//periodslotwise report function updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Library/TimeTable
//class base format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/19/09    Time: 4:14p
//Updated in $/LeapCC/Library/TimeTable
//dayswise, classwise  time table show & Print & CSV file checks
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/18/09    Time: 5:35p
//Updated in $/LeapCC/Library/TimeTable
//classwise time table show
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/26/09    Time: 6:01p
//Updated in $/LeapCC/Library/TimeTable
//Order by clause update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/16/09    Time: 10:29a
//Updated in $/LeapCC/Library/TimeTable
//show time table function update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/09    Time: 3:53p
//Created in $/LeapCC/Library/TimeTable
//file added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/13/09    Time: 4:40p
//Updated in $/Leap/Source/Library/ScTimeTable
//report view code update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/13/09    Time: 4:34p
//Updated in $/Leap/Source/Library/ScTimeTable
//function base query update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/08/09    Time: 6:46p
//Updated in $/Leap/Source/Library/ScTimeTable
//report format change 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/06/09    Time: 3:09p
//Updated in $/Leap/Source/Library/ScTimeTable
//conditions check update 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/06/09    Time: 11:49a
//Updated in $/Leap/Source/Library/ScTimeTable
//alignment settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/04/09    Time: 7:09p
//Created in $/Leap/Source/Library/ScTimeTable
//file added
//

?>