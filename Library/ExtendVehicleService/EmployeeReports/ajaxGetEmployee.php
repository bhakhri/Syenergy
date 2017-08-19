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
    define('MODULE','TeacherWiseTopicTaught');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance();
     
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']);
     
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }
     
    $foundArray = $employeeReportsManager->getTimeTableTeacher(' AND tt.timeTableLabelId="'.$timeTabelId.'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo json_encode($foundArray);    
    }
    else {
        echo 0;
    }
    

// $History: ajaxGetEmployee.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/11/10    Time: 4:46p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//

?>