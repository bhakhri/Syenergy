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
require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");

require_once(MODEL_PATH . "/AuthorizeLeaveManager.inc.php");
$authorizeLeaveManager = AuthorizeLeaveManager::getInstance();

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','AuthorizeEmployeeLeave');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
 UtilityManager::ifNotLoggedIn(true);
}
else if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==5){
  UtilityManager::ifManagementNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);  
}
UtilityManager::headerNoCache();

$leaveId=trim($REQUEST_DATA['mappingId']);
if($leaveId==''){
    echo EMPLOYEE_LEAVE_NOT_EXIST;
    die;
}

$leaveStatus=trim($REQUEST_DATA['leaveStatus']);
$reason=trim($REQUEST_DATA['reason']);

if($leaveStatus=='' or $reason==''){
    echo 'Required Parameters Missing';
    die;
}

$applyLeaveManager = ApplyLeaveManager::getInstance();

$leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS'); 

//fetch employeeId and leave status
$empArray=$applyLeaveManager->getEmployeeLeaveInfo(' WHERE leaveId='.$leaveId);
if(count($empArray)==0){
    echo EMPLOYEE_LEAVE_NOT_EXIST;
    die;
}
$sourceEmployeeId=$empArray[0]['employeeId'];
$leaveType=$empArray[0]['leaveTypeId'];


// Set session
$leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
$leaveSessionId='';
if($leaveSessionArray[0]['leaveSessionId']!='') {
  $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
}                                                                                                         

if($leaveSessionId=='') {
  echo "Please select atleast one active session";    
  die;  
}



 //fetch employeeId of logged in user
 $empArray=$authorizeLeaveManager->getEmployeeInformation($sessionHandler->getSessionVariable('UserId'));
 $employeeId=$empArray[0]['employeeId'];
 if($employeeId==''){
  echo EMPLOYEE_LEAVE_NOT_EXIST; 
  die; 
 }

  
 
 //check for first/second authorization
  $authorizeArray= $authorizeLeaveManager->checkAuthorizationData($sourceEmployeeId,$leaveType,$leaveSessionId);
  $firstAuthorizer=$authorizeArray[0]['firstApprovingEmployeeId'];
  if($authorizeArray[0]['firstApprovingEmployeeId']==$employeeId){
      $employeeApprovingField = ", firstApprovingEmployeeId = $employeeId";
      $authorizer=1; 
  }
  else if($authorizeArray[0]['secondApprovingEmployeeId']==$employeeId){
      $employeeApprovingField = ", secondApprovingEmployeeId = $employeeId";  
      $authorizer=2; 
  }
  else{
      echo 'Invalid mapping of authorization with employees';
      die;
  }
  
  if($authorizer==1){
     if($leaveStatus!=1 and $leaveStatus!=3){
        echo 'Incorrect leave status for first authorizer';
        die;
     }
  }
  
  if($authorizer==2 && $leaveAuthorizersId==2 && $leaveAuthorizersId==2){
     if($leaveStatus!=2 and $leaveStatus!=3) {
        echo 'Incorrect leave status for second authorizer';
        die;
     }
  }


//if all goes well,edit and update records in leave table and comments table respectively
if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
   //update leave table
   $ret=$authorizeLeaveManager->editEmployeeLeave($leaveId,$leaveStatus,$employeeApprovingField,$leaveSessionId);
   if($ret==false){
      echo FAILURE;
      die; 
   }
   //add record in commnets table
   $ret=$authorizeLeaveManager->addEmployeeLeaveComments($leaveId,$reason,date('Y-m-d'),$employeeId,$leaveSessionId);
   if($ret==false){
      echo FAILURE;
      die; 
   }
   
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo SUCCESS;
   die;
  }
  else {
   echo FAILURE;
   die;
  }
  }
 else {
  echo FAILURE;
  die;
 }


// $History: ajaxInitEdit.php $
?>