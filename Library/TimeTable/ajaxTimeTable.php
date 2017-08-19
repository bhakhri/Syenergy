<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------  
require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();

require_once($FE . "/Library/HtmlFunctions.inc.php"); 
$htmlFunctionsManager = HtmlFunctions::getInstance();   

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();   

global $sessionHandler;
$timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";   
 
    $timeTableLabelId = trim($REQUEST_DATA['labelId']);
    $teacherId = trim($REQUEST_DATA['teacherId']);
    $timeTableType = trim($REQUEST_DATA['timeTableType']);
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($teacherId=='') {
      $teacherId=0;  
    }
    
    if($timeTableType=='') {
      $timeTableType=0;  
    }
    
    
    $condDate ='';
    if($timeTableType==2) {
       $fromDate = $REQUEST_DATA['fromDate'];
       $toDate = $REQUEST_DATA['toDate'];
       $condDate = " AND ttl.timeTableType=$timeTableType  AND (tt.fromDate BETWEEN '$fromDate' AND '$toDate') "; 
    }
    
    
    
    // Search Start 
    $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);    
    $foundArray = $studentManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = ".$timeTableLabelId);
    $labelName = $foundArray[0]['labelName'];
    
    $foundArray = $studentManager->getSingleField('employee', "DISTINCT employeeId, CONCAT(employeeName,' (',employeeCode,')') AS employeeName", "WHERE employeeId  = ".$teacherId);
    $className = $foundArray[0]['employeeName'];
    $search = $labelName."<br>".$className;
    if($timeTableType==2) {
      $search .= "<br>From&nbsp;".UtilityManager::formatDate($fromDate)."&nbsp;To&nbsp;".UtilityManager::formatDate($toDate);      
    }
    else {
       //$search .= "<br>As On $formattedDate ";                  
    }
    
 
    // Fetch Period Arrays
    $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
  
    $findTimeTable=''; 
     
    //Get the time table date according to class selected
    for($ps=0; $ps < count($periodSlotArr); $ps++) {
        $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
        
        $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
        $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  

        $conditions= " AND tt.employeeId=".$teacherId." AND tt.timeTableLabelId=".$timeTableLabelId;
        $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." ".$condDate;
     
        $fieldName="DISTINCT timeTableType";
        $orderFrom = " ORDER BY timeTableType";
        $studentRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
        $timeTableType=1;
        if(count($studentRecordArray)>0) {
           $timeTableType = $studentRecordArray[0]['timeTableType'];
        }
        
        if($timeTableType==1) {
           $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
        }
        else 
        if($timeTableType==2) {
           $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber";   
        }             
        
        if($timeTableType==2) {
            // Date Format 
            $fieldName = " DISTINCT tt.fromDate";
            $orderFrom = " ORDER BY fromDate";
            $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
        }  
     
        $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);
        $recordCount = count($teacherRecordArray);
        if($recordCount >0 && is_array($teacherRecordArray)) {     
           if($timeTableType==1) {  
               if($timetableFormat=='1') {
                   echo "<br><table width='100%' class='reportTableBorder'>
                            <tr><td width='100%' class='dataFont'  align='left'>$search</td></tr>    
                          </table>";
                   echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                   echo "<br>";
               }
               else{ 
                  if($timetableFormat=='2') {
                    echo "<br><table width='100%' class='reportTableBorder'>
                            <tr><td width='100%' class='dataFont'  align='left'>$search</td></tr>   
                          </table>";
                    echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                    echo "<br>";
                  }
               }
           }
           else 
           if($timeTableType==2) {  
              echo "<br><table width='100%' class='reportTableBorder'>
                            <tr><td width='100%' class='dataFont'  align='left'>$search</td></tr>    
                          </table>"; 
              echo $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
              echo "<br>"; 
           }
           $findTimeTable='1';
        }
    }
     
  if($findTimeTable=='') {
    echo "<br><table width='100%' class='reportTableBorder'>
                <tr><td width='100%' class='dataFont'  align='left'>$search</td></tr>     
                <tr><td width='100%' class='dataFont'  align='center'><br><b>No Data Found</b></td></tr>
              </table>";
  }
  else {
   echo '<div id = "saveDiv" align="right">
            <input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport()"/>&nbsp;
         </div>';
  }
  
//$History: ajaxTimeTable.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Library/TimeTable
//validation & condition format updated 
//
//*****************  Version 9  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Library/TimeTable
//periodslotwise report function updated
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:09p
//Updated in $/LeapCC/Library/TimeTable
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Library/TimeTable
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/04/09    Time: 11:31a
//Updated in $/LeapCC/Library/TimeTable
//Order by Clause Update (LENGTH(p.periodNumber)+0,p.periodNumber)
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/30/09    Time: 7:09p
//Updated in $/LeapCC/Library/TimeTable
//Updated with "No Data Found" message
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/05/09    Time: 11:22a
//Updated in $/LeapCC/Library/TimeTable
//Changed Time table format so that admin can decide the display of time
//table i.e in periods in rows or periods in column
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/03/09    Time: 12:31p
//Updated in $/LeapCC/Library/TimeTable
//Updated time table with groupShort field
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Library/TimeTable
//updated functionality as per CC
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/21/08    Time: 5:31p
//Updated in $/Leap/Source/Library/TimeTable
//added print report option
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 6:32p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
 
?>