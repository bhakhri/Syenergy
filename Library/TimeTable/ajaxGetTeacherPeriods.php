<?php
//-------------------------------------------------------
// Purpose: To get sections
// Author : Pushpender Kumar Chauhan
// Created on : (20.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherSubstitutions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/TimeTableManager.inc.php");    

$timeTabelId = trim($REQUEST_DATA['timeTabelId'] );
$employeeId = trim($REQUEST_DATA['employeeId']);
$daysOfWeek = trim($REQUEST_DATA['daysOfWeek']);
$timeTableType = trim($REQUEST_DATA['timeTableType']); 
$fromDate = trim($REQUEST_DATA['fromDate']); 

if($timeTabelId=='') {
  $timeTabelId =0; 
}

if($employeeId=='') {
  $employeeId =0; 
}

if($daysOfWeek=='') {
  $daysOfWeek = "-1";  
}
   
if($timeTableType=='') {
  $timeTableType=0;  
}   
    
    $filter= "DISTINCT  tt.periodId, CONCAT(p.periodNumber,' ~ ',ps.slotAbbr,' ~ ',cc.abbreviation,'-',bb.abbreviation,'-',r.roomAbbreviation) AS periodNumber "; 
    $condition = " AND lbltt.timeTableType=$timeTableType AND tt.employeeId='".$employeeId."' AND tt.timeTableLabelId = '".$timeTabelId."'"; 
    if($timeTableType==1) {         // Daily 
      $condition .= " AND tt.daysOfWeek='".$daysOfWeek."'";
    } 
    else if($timeTableType==2) {    // Weekly 
      $condition .= " AND tt.fromDate='".$fromDate."'";
    } 
    $foundArray = TimeTableManager::getInstance()->getDaysPeriodsTimeTable($filter,$condition);   
    
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonSectionsArray  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSectionsArray[] = $foundArray[$s];         
        }
    }
    echo json_encode($jsonSectionsArray);
    
    
// $History: ajaxGetTeacherPeriods.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/22/10    Time: 5:30p
//Updated in $/LeapCC/Library/TimeTable
//period slot format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/19/10    Time: 5:36p
//Updated in $/LeapCC/Library/TimeTable
//timetableLabelId base check updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:01p
//Created in $/LeapCC/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:32p
//Created in $/SnS/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:44a
//Created in $/Leap/Source/Library/ScTimeTable
//initial checkin
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:48p
//Updated in $/Leap/Source/Library/ScTimeTable
//applied role level access
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 9/20/08    Time: 9:32p
//Created in $/Leap/Source/Library/ScTimeTable

?>