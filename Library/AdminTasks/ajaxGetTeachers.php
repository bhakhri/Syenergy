<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $foundArray = AdminTasksManager::getInstance()->getTeacherList(' AND t.timeTableLabelId='.$REQUEST_DATA['timeTableLabelId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetTeachers.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 20/05/09   Time: 11:54
//Created in $/LeapCC/Library/AdminTasks
//Created "Duty Leave" Module
?>