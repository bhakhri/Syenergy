<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

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
    $timeTableLabelId =  trim($REQUEST_DATA['timeTableLabelId']);
    
    if(trim($timeTableLabelId)=='') {
      $timeTableLabelId = 0;  
    }    
    
    if(trim($employeeId)=='') {
      $employeeId = 0;  
    }
    
    $condition = " AND tt.timeTableLabelId='".$timeTableLabelId."' AND tt.employeeId = '".$employeeId."'";
    $orderBy = "className";
    
    $foundArray = $employeeReportsManager->getTimeTableClass($condition,$orderBy);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetTimeTableClass.php $
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
//User: Parveen      Date: 2/16/10    Time: 12:01p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//



?>