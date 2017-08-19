<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE classes in time table label Id
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
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance();
                                           
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']);
    $employeeId = trim($REQUEST_DATA['employeeId']);   
     
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }
    
    if($employeeId=='') {
      $employeeId = 0;
    }
     
    $condition = " AND tt.timeTableLabelId =  $timeTabelId  AND tt.employeeId='".$employeeId."'";
    
    $foundArray = $employeeReportsManager->getTimeTableClass($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo json_encode($foundArray);    
    }
    else {
        echo 0;
    }

// $History: ajaxGetClasses.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/15/10    Time: 11:12a
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//

?>