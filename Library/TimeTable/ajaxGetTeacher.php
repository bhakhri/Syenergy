<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE teacher in time table label Id
//
//
// Author : Parveen Sharma
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TeacherSubstitutions');  
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance();
     
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']);
    $timeTableType = trim($REQUEST_DATA['timeTableType']);
    $fromDate = trim($REQUEST_DATA['fromDate']);  
     
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }
    
    if($timeTableType=='') {
      $timeTableType = 0;
    } 
    
    if($timeTableType==1) {         // Weekly 
       $teacherArray = $employeeReportsManager->getTimeTableTeacher(" AND ttl.timeTableType=$timeTableType AND ttl.timeTableLabelId=$timeTabelId");
    }
    else if($timeTableType==2) {    // Daily
       $teacherArray = $employeeReportsManager->getTimeTableTeacher(" AND tt.fromDate='$fromDate' AND ttl.timeTableType=$timeTableType AND ttl.timeTableLabelId=$timeTabelId");
    }
       
    if(is_array($teacherArray) && count($teacherArray)>0 ) {  
       echo json_encode($teacherArray);    
    }
    else {
        echo 0;
    }
    

// $History: ajaxGetTeacher.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/12/10    Time: 1:38p
//Created in $/LeapCC/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/11/10    Time: 4:46p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//

?>