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
require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php");


require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
    
define('MODULE','EmployeeLeaveAuthorizer');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$mappingId=trim($REQUEST_DATA['mappingId']);
$firstEmp=trim($REQUEST_DATA['firstEmployee']);
$secondEmp=trim($REQUEST_DATA['secondEmployee']);
$leaveType=trim($REQUEST_DATA['leaveType']);


global $sessionHandler;
$instituteId=$sessionHandler->getSessionVariable('InstituteId');

  // get Active session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      echo "Please select atleast one active session";    
      die;  
    }

    $leaveSessionCondition = " AND leaveSessionId = $leaveSessionId";   


global $sessionHandler;
$leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');     
if($leaveAuthorizersId=='') {
  $leaveAuthorizersId=1;  
}

if($mappingId==''){
    echo EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
    die;
}

if($firstEmp==''){
    echo SELECT_FIRST_AUTHORIZER;
    die;
}
if($secondEmp==''){
    echo SELECT_SECOND_AUTHORIZER;
    die;
}

/*
if($firstEmp==$secondEmp){
    echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION;
    die;
}
*/

if($leaveType==''){
    echo SELECT_LEAVE_TYPE;
    die;
}

$empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance(); 

//finding empId of this record
$empArray=$empLeaveAuthManager->getMappingData(' WHERE  approvingId='.$mappingId);
if(trim($empArray[0]['employeeId'])==''){
    echo EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
    die;
}
$employeeId = trim($empArray[0]['employeeId']);
$prevLeaveType=trim($empArray[0]['leaveTypeId']);


//check 1: cyclic check
$cyclicArray=$empLeaveAuthManager->checkCyclicData($employeeId,$firstEmp,$secondEmp,$leaveSessionCondition); ;
if($cyclicArray[0]['cnt']){
    echo CYCLIC_AUTHORIZATION_RESTRICTION;
    die;
}

    
//check 2: unique check
$uniqueArray=$empLeaveAuthManager->getMappingData(' WHERE employeeId='.$employeeId.' AND leaveTypeId='.$leaveType.' AND approvingId!='.$mappingId.$leaveSessionCondition); 
if(count($uniqueArray)!=0){
    echo DUPLICATE_AUTHORIZATION_RESTRICTION;
    die;
}

/*    
//check 3 : usage check
$usageArray=$empLeaveAuthManager->checkEmployeeLeaveAuthorizationMappingUsage(' WHERE employeeId='.$employeeId.' AND leaveTypeId='.$prevLeaveType.$leaveSessionCondition); 
if($usageArray[0]['cnt']!=0){
    echo DEPENDENCY_CONSTRAINT_EDIT;
    die;
}
*/

//update records
$ret=$empLeaveAuthManager->editEmployeeLeaveAuthorizer($mappingId,$firstEmp,$secondEmp,$leaveType,$instituteId,$leaveSessionId);   
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