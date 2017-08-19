<?php
//--------------------------------------------------------------------------------------------------------------
//This file returns the array of groups, based on classId  
// Author : Parveen Sharma
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
$employeeReportsManager = EmployeeReportsManager::getInstance(); 

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

    
    global $sessionHandler;      
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');      
    $timeTableLabelId = trim(add_slashes($REQUEST_DATA['timeTableLabelId'])); 
    $classId = trim(add_slashes($REQUEST_DATA['classId']));
    $subjectId = trim(add_slashes($REQUEST_DATA['subjectId'])); 
    
    if($employeeId=='') {
      $employeeId=0;
    }
    
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
    
    if($classId=='') {
      $classId = 0;  
    }
    
    if($subjectId=='') {
      $subjectId = 0;  
    }
    
    $condition = " AND c.classId = $classId AND tt.timeTableLabelId = $timeTableLabelId  AND s.subjectId = $subjectId AND tt.employeeId = $employeeId  ";   
    $foundArray = $employeeReportsManager->getTimeTableGroup($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetTimeTableGroups.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/17/10    Time: 12:23p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//teacher login code updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/17/10    Time: 10:30a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/10    Time: 11:35a
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>