<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE classes in time table label Id
//
//
// Author : Parveen Sharma
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance();
                                           
    $classId = trim($REQUEST_DATA['classId']);
    $employeeId = trim($REQUEST_DATA['employeeId']);   
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']); 
    
    if($classId=='') {
      $classId = 0;
    }
    
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }
    
    if($employeeId=='') {
      $employeeId = 0;
    }
     
    $condition = " AND g.classId = $classId AND tt.timeTableLabelId = $timeTabelId  AND tt.employeeId='".$employeeId."'";
    
    $foundArray = $employeeReportsManager->getTimeTableSubject($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo json_encode($foundArray);    
    }
    else {
        echo 0;
    }

// $History: ajaxGetSubject.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/11/10    Time: 6:26p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//

?>