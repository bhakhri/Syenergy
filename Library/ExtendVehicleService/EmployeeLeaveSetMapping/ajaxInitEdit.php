<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
define('MODULE','EmployeeEmployeeLeaveSetMapping');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$mappingId=trim($REQUEST_DATA['mappingId']);
$leaveSet=trim($REQUEST_DATA['leaveSet']);

if($mappingId==''){
    echo EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST;
    die;
}
if($leaveSet==''){
    echo SELECT_LEAVE_SET;
    die;
}

$empLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance(); 

//finding empId of this record
$empArray=$empLeaveSetMappingManager->getEmployeeLeaveSetMapping(' WHERE  employeeLeaveSetMappingId='.$mappingId);
if(trim($empArray[0]['employeeId'])==''){
    echo EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST;
    die;
}
$employeeId = trim($empArray[0]['employeeId']);
$leaveSessionId = trim($empArray[0]['leaveSessionId']);       

//edit check
$usageArray=$empLeaveSetMappingManager->checkEmployeeLeaveSetMappingUsage(' AND l.leaveSessionId = '.$leaveSessionId.' AND l.employeeId='.$employeeId);
if($usageArray[0]['cnt']!=0){
    echo DEPENDENCY_CONSTRAINT_EDIT;
    die;
}

//check for duplicate mappinig
$dupArray=$empLeaveSetMappingManager->getEmployeeLeaveSetMapping(' WHERE  leaveSessionId = '.$leaveSessionId.' AND leaveSetId='.$leaveSet.' AND employeeId='.$employeeId.' AND employeeLeaveSetMappingId!='.$mappingId);
if($dupArray[0]['employeeLeaveSetMappingId']!=''){
    echo DUPLICATE_LEAVE_SET;
    die;
}

//edit leave set mapping
$ret=$empLeaveSetMappingManager->editEmployeeLeaveSetMapping($mappingId,$leaveSet);
if($ret==false){
    echo FAILURE;
    die;
}
else{
    echo SUCCESS;
    die;
}


// $History: ajaxInitEdit.php $
?>