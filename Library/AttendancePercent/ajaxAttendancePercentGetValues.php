<?php
//-------------------------------------------------------
// Purpose: To get values of coursewise timetable from the database
//
// Author : Parveen Sharma
// Created on : 28.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AttendancePercentManager.inc.php");    
define('MODULE','AttendancePercent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;

 
$attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));

if($attendanceSetId!='') { 
    
    $str = ' AND a.attendanceSetId='.$attendanceSetId;
    $foundArray = AttendancePercentManager::getInstance()->getAttendancePercent($str);
    
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTableArray  = array();
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonTimeTableArray[] = $foundArray[$s];         
        }
    }
    
    $resultArray = array('attendancePercentArr' => $jsonTimeTableArray);
    // print_r($resultArray);
    echo json_encode($resultArray);
} 

/*if(trim($REQUEST_DATA['subjectTypeId']) != '' && trim($REQUEST_DATA['timeTableLabelId']) != '' && trim($REQUEST_DATA['degreeId']) != '') { 
    
   //$timeTableArray = AttendancePercentManager::getInstance()->getActiveTimeTableLabelId();
   // $activeTimeTableLabelId  = $timeTableArray[0]['timeTableLabelId'];
   // $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    
    $str = ' AND a.subjectTypeId='.$subjectTypeId.' AND a.timeTableLabelId='.$timeTableLabelId.' AND a.instituteId = '.$instituteId.' AND a.degreeId = '.$degreeId;
    
    $foundArray = AttendancePercentManager::getInstance()->getAttendancePercent($str);

    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTableArray  = array();
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonTimeTableArray[] = $foundArray[$s];         
        }
    }
    
    $resultArray = array('attendancePercentArr' => $jsonTimeTableArray);
    // print_r($resultArray);
    echo json_encode($resultArray);
} */

// $History: ajaxAttendancePercentGetValues.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/29/09   Time: 2:05p
//Updated in $/LeapCC/Library/AttendancePercent
//new enhancement attendance Set Id base checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/20/09   Time: 12:26p
//Updated in $/LeapCC/Library/AttendancePercent
//degreeId, timeTableLabelId added 
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/AttendancePercent
//changed queries to add instituteId
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 12:28p
//Created in $/LeapCC/Library/AttendancePercent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 11:27a
//Created in $/Leap/Source/Library/ScTimeTable
//coursewise time table added
//

?>