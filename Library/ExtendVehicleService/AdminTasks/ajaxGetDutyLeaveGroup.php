<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO all options(class,subject,group) corresponding to a particular date for a teacher 
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");                                                                
define('MODULE','DutyLeavesAdvanced');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    
    if(trim($REQUEST_DATA['employeeId'])!='' and trim($REQUEST_DATA['classId'])!='' and trim($REQUEST_DATA['subjectId'])!='' and trim($REQUEST_DATA['timeTableLabelId'])!=''){ 
    $startDate=date('Y-m-d');
    $endDate=date('Y-m-d');
    //*************Get adjusted data for class*************
     $foundArray = AdminTasksManager::getInstance()->getDutyLeaveGroups(trim($REQUEST_DATA['employeeId']),trim($REQUEST_DATA['classId']),trim($REQUEST_DATA['subjectId']),trim($REQUEST_DATA['timeTableLabelId']),$startDate,$endDate);
    }
    else{
        echo 0;
        die;
    }

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetDutyLeaveGroup.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:14
//Created in $/LeapCC/Library/AdminTasks
//Modified Duty Leaves module in admin section
?>