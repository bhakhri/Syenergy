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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','EmployeeLeaveAuthorizerAdv');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$input=trim($REQUEST_DATA['inputValue']);
if($input==''){
    echo 'Required Parameters Missing';
    die;
}
$inputArray=explode(',',$input);
$cnt=count($inputArray);

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

if(SystemDatabaseManager::getInstance()->startTransaction()) {

for($i=0;$i<$cnt;$i++){
    
    $infoArray=explode('~',$inputArray[$i]);
    
    $employeeId=trim($infoArray[0]);
    $firstEmp=trim($infoArray[1]);
    $secondEmp=trim($infoArray[2]);
    $leaveType=trim($infoArray[3]);
    $apprId=trim($infoArray[4]);
    
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
    if($firstEmp==$secondEmp){
        echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION;
        die;
    }
    if($leaveType==''){
        echo SELECT_LEAVE_TYPE;
        die;
    }
    
    
    $empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance();
    //check 1: cyclic check
    $cyclicArray=$empLeaveAuthManager->checkCyclicData($employeeId,$firstEmp,$secondEmp,$leaveSessionCondition);
    if($cyclicArray[0]['cnt']){
        echo CYCLIC_AUTHORIZATION_RESTRICTION;
        die;
    }
    
    if($apprId!=-1){
        $empArray1=$empLeaveAuthManager->getMappingData(' WHERE approvingId='.$apprId.$leaveSessionCondition);
        if(count($empArray1)>0) {
            $empId=$empArray1[0]['employeeId'];
            $lTId=$empArray1[0]['leaveTypeId'];
         /*
            $leaveArray1=$empLeaveAuthManager->checkEmployeeLeaveAuthorizationMappingUsage(' WHERE employeeId='.$empId.' AND leaveTypeId='.$lTId.$leaveSessionCondition);
            if($leaveArray1[0]['cnt']!=0){
               continue;
            }
         */
            $ret=$empLeaveAuthManager->deleteEmployeeLeaveAuthorizerMapping(' WHERE employeeId='.$empId.' AND leaveTypeId='.$lTId.$leaveSessionCondition);
            if($ret==false){
              echo FAILURE;
              die;
            }
        }
    }
    
    /*  //check 2: check for relation in leave table
        $leaveArray=$empLeaveAuthManager->checkEmployeeLeaveAuthorizationMappingUsage(' WHERE employeeId='.$employeeId.' AND leaveTypeId='.$leaveType.$leaveSessionCondition);
        //if related records exixts then skip
        if($leaveArray[0]['cnt']!=0){
            continue;
        }
    */
    $ret=$empLeaveAuthManager->deleteEmployeeLeaveAuthorizerMapping(' WHERE employeeId='.$employeeId.' AND leaveTypeId='.$leaveType.$leaveSessionCondition);
     if($ret==false){
        echo FAILURE;
        die;
    }
    
    $ret=$empLeaveAuthManager->addEmployeeLeaveAuthorizer($employeeId,$firstEmp,$secondEmp,$leaveType,$instituteId,$leaveSessionId);
    if($ret==false){
        echo FAILURE;
        die;
    }
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
 
 
// $History: ajaxInitAdd.php $
?>