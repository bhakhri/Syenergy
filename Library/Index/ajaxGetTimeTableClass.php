<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");  
require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");    
$employeeReportsManager = EmployeeReportsManager::getInstance();  

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::headerNoCache();

    
    $condition="";
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTableLabelId']);  
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    $id =$sessionHandler->getSessionVariable('RoleId');
    if($id==1 || $id==5) {      // Admin
       UtilityManager::ifNotLoggedIn(true);
       $condition = " AND ttc.timeTableLabelId='".$timeTableLabelId."'";
       $foundArray = CommonQueryManager::getInstance()->getActiveTimeTableClasses($condition);      
    }
    if($id==2) {          // Teacher
       UtilityManager::ifTeacherNotLoggedIn(true);  
       $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
       $condition = " AND tt.timeTableLabelId='".$timeTableLabelId."' AND tt.employeeId = '".$employeeId."'";
       $orderBy = "className";
       $foundArray = $employeeReportsManager->getTimeTableClass($condition,$orderBy);
    }
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }


// $History: ajaxGetTimeTableClass.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/10    Time: 5:15p
//Created in $/LeapCC/Library/Index
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 12:01p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//



?>