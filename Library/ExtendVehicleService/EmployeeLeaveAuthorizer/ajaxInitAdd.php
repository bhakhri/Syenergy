<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php");

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
    

define('MODULE','EmployeeLeaveAuthorizer');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$employeeId=trim($REQUEST_DATA['employeeId']);
$firstEmp=trim($REQUEST_DATA['firstEmployee']);
$secondEmp=trim($REQUEST_DATA['secondEmployee']);
$leaveType=trim($REQUEST_DATA['leaveType']);


  global $sessionHandler;
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    
 
    global $sessionHandler;
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');     
    if($leaveAuthorizersId=='') {
      $leaveAuthorizersId=1;  
    }

if($employeeId==''){
    echo ENTER_VALID_EMPLOYEE_INFO;
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


$empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance();

$leaveSessionCondition = " AND leaveSessionId = $leaveSessionId";


//check 1: cyclic check
$cyclicArray=$empLeaveAuthManager->checkCyclicData($employeeId,$firstEmp,$secondEmp,$leaveSessionCondition);
if($cyclicArray[0]['cnt']){
    echo CYCLIC_AUTHORIZATION_RESTRICTION;
    die;
}
//check 2: unique check
$uniqueArray=$empLeaveAuthManager->getMappingData(' WHERE employeeId='.$employeeId.' AND leaveTypeId='.$leaveType.$leaveSessionCondition);
if(count($uniqueArray)!=0){
    echo DUPLICATE_AUTHORIZATION_RESTRICTION;
    die;
}

 $ret=$empLeaveAuthManager->addEmployeeLeaveAuthorizer($employeeId,$firstEmp,$secondEmp,$leaveType,$instituteId,$leaveSessionId);
 if($ret==false){
    echo FAILURE;
    die;
 }
 else{
    echo SUCCESS;
    die;
 }
 
 
// $History: ajaxInitAdd.php $
?>