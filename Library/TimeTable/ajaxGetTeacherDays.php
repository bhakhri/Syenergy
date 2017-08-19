<?php
//-------------------------------------------------------
// Purpose: To get sections
// Author : Pushpender Kumar Chauhan
// Created on : (20.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
$timeTableType = trim($REQUEST_DATA['timeTableType']);  

if($timeTabelId=='') {
  $timeTabelId =0; 
}

if($employeeId=='') {
  $employeeId =0; 
}


if($timeTableType=='') {
  $timeTableType=0; 
}

    
if(trim($employeeId) != '') {
 
    $filter= " DISTINCT  tt.daysOfWeek AS daysOfWeek ";
    $condition = " AND lbltt.timeTableType = $timeTableType AND tt.employeeId = ".$employeeId." AND lbltt.timeTableLabelId = ".$timeTabelId;
    
    $foundArray = TimeTableManager::getInstance()->getDaysPeriodsTimeTable($filter,$condition);
    
    global $daysArr;
     
    $resultsCount = count($foundArray);
    $jsonSectionsArray  = '';   
    
    if(is_array($foundArray) && $resultsCount>0) {
        for($s = 0; $s<$resultsCount; $s++) {
            $foundArray[$s]['daysOfWeekC']=$daysArr[$foundArray[$s]['daysOfWeek']];
            $jsonSectionsArray[] = $foundArray[$s];         
        }
    }
	echo json_encode($jsonSectionsArray);
}
// $History: ajaxGetTeacherDays.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/19/10    Time: 5:36p
//Updated in $/LeapCC/Library/TimeTable
//timetableLabelId base check updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/27/10    Time: 11:22a
//Updated in $/LeapCC/Library/TimeTable
//format updated free period view
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
//User: Parveen      Date: 2/20/09    Time: 11:43a
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