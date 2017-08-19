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
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");  

    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else if($roleId==5){
    	UtilityManager::ifManagementNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();


    $conditionEmployee = '';     
    if($roleId==2) {    
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      $conditionEmployee = " AND tt.employeeId = '$employeeId' ";
    }

if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    
    $condition = ' AND ttl.timeTableLabelId="'.$REQUEST_DATA['timeTableLabelId'].'" '.$conditionEmployee;
    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses($condition," cls.degreeId,cls.branchId,cls.studyPeriodId");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}

// $History: ajaxGetTimeTableClass.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10-04-19   Time: 11:10a
//Updated in $/LeapCC/Library/AdminTasks
//changed the class display order
//"cls.degreeId,cls.branchId,cls.studyPeriodId"
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 12:01p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//
?>
