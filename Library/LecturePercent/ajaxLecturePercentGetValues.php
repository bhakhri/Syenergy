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
require_once(MODEL_PATH . "/LecturePercentManager.inc.php");    
define('MODULE','LecturePercent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;

$percentManager    = LecturePercentManager::getInstance();    
$attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));

if($attendanceSetId!='') { 
    
    $str = ' AND ams.attendanceSetId='.$attendanceSetId;
    
/*
    if(trim($REQUEST_DATA['subjectTypeId']) != '') { 
        //$timeTableArray = $percentManager->getActiveTimeTableLabelId();
        //$activeTimeTableLabelId  = $timeTableArray[0]['timeTableLabelId'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
	$degreeId = $REQUEST_DATA['degreeId'];
    
    $str = ' AND ams.subjectTypeId='.$subjectTypeId.' AND ams.timeTableLabelId='.$timeTableLabelId.' AND ams.instituteId = '.$instituteId.' AND ams.degreeId = '.$degreeId;
*/ 
    
    $foundArray = $percentManager->getLecturePercent($str);

    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTableArray  = array();
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonTimeTableArray[] = $foundArray[$s];         
        }
    }
    
    $resultArray = array('lecturePercentArr' => $jsonTimeTableArray);
 //   print_r($resultArray);  
    
     echo json_encode($resultArray);
}
// $History: ajaxLecturePercentGetValues.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/29/09   Time: 6:52p
//Updated in $/LeapCC/Library/LecturePercent
//attendance Set Id base code updated
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:53a
//Updated in $/LeapCC/Library/LecturePercent
//modification in code if select different degree to show attendance
//marks slabs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Library/LecturePercent
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/18/09   Time: 3:33p
//Updated in $/LeapCC/Library/LecturePercent
//Add Time Table Label dropdown and change in interface of attendance
//marks slabs. Now user can add the marks between the range for Lecture
//attended. 
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Library/LecturePercent
//changed queries to add instituteId
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 10:21a
//Updated in $/LeapCC/Library/LecturePercent
//modified to check some validations
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/30/09    Time: 1:43p
//Created in $/LeapCC/Library/LecturePercent
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