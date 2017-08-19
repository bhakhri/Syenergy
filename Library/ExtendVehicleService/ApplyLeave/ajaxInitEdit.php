<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','ApplyEmployeeLeave');
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

$leaveType=trim($REQUEST_DATA['leaveType']);

$fromDate=trim($REQUEST_DATA['fromDate']);
$fromDateArr=explode('-',$fromDate);

$toDate=trim($REQUEST_DATA['toDate']);
$toDateArr=explode('-',$toDate);

$applyDate=trim($REQUEST_DATA['applyDate']);
$applyDateArr=explode('-',$applyDate);

$leaveReason=htmlentities(add_slashes(trim($REQUEST_DATA['leaveReason'])));
$leaveFormat=trim($REQUEST_DATA['leaveFormat']);
if($fromDate=='' or $toDate=='' or $applyDate=='' or $leaveReason==''){
    echo 'Required Parameters Missing';
    die;
}

$from_date=gregoriantojd($fromDateArr[1],$fromDateArr[2],$fromDateArr[0]);
$to_date=gregoriantojd($toDateArr[1],$toDateArr[2],$toDateArr[0]);
$apply_date=gregoriantojd($applyDateArr[1],$applyDateArr[2],$applyDateArr[0]);

//date check
if(($from_date-$to_date)>0){
    echo LEAVE_DATE_RESTRICTION;
    die;
}
/*
if(($apply_date-$from_date)>0){
    echo APPLY_LEAVE_DATE_RESTRICTION1;
    die;
}
if(($apply_date-$to_date)>0){
    echo APPLY_LEAVE_DATE_RESTRICTION2;
    die;
}
*/

$applyLeaveManager = ApplyLeaveManager::getInstance();

//fetch employeeId and leave status
$empArray=$applyLeaveManager->getEmployeeLeaveInfo(' WHERE leaveId='.$leaveId);
if(count($empArray)==0){
    echo EMPLOYEE_LEAVE_NOT_EXIST;
    die;
}

if($empArray[0]['leaveStatus']!=0){
    echo EMPLOYEE_LEAVE_EDIT_RESTRICTION;
    die;
}
$employeeId=$empArray[0]['employeeId'];


$substituteEmployee = strtoupper(trim($REQUEST_DATA['substituteEmployee']));     

$substituteEmployee1='';
$duplicateArray =array();
if($substituteEmployee!='') {
   $substituteEmployeeArray = explode(',',$substituteEmployee);
   
   $substituteEmployee='';  
   for($i=0;$i<count($substituteEmployeeArray);$i++) {
      if(trim($substituteEmployeeArray[$i])!='') {
        $find='0';  
        for($j=0;$j<count($duplicateArray);$j++) {
          if($duplicateArray[$j] == strtoupper(trim($substituteEmployeeArray[$i])))  {
           $find='1';   
            break;  
          }
        }
        if($find=='0') {  
          if($substituteEmployee!='') {
            $substituteEmployee .=", ";  
            $substituteEmployee1 .=", ";
          } 
          $substituteEmployee .= "'".htmlentities(add_slashes(trim($substituteEmployeeArray[$i])))."'";   
          $substituteEmployee1 .= htmlentities(add_slashes(trim($substituteEmployeeArray[$i])));
          $duplicateArray[] = strtoupper(trim($substituteEmployeeArray[$i]));
        }
      }
   } 
   $searchEmployeeCodeArray = $applyLeaveManager->getSearchList("DISTINCT employeeId, employeeCode","employee","WHERE employeeCode IN (".$substituteEmployee.")" );
   $substituteEmployeeArray = explode(',',$substituteEmployee);  
   if(count($searchEmployeeCodeArray) != count($substituteEmployeeArray)) {
      echo "Substitute Employee Code not exist";
      die;  
   }
   $substitute = '';
   for($i=0;$i<count($searchEmployeeCodeArray);$i++) {
     if($searchEmployeeCodeArray[$i]['employeeId'] == $employeeId) {
       echo "You can not enter your own employee code as a substitue employee code";
       die;  
     }
     if($substitute == '') {
        $substitute .=',';
     }
     $substitute .= $searchEmployeeCodeArray[$i]['employeeId']; 
   }
}


//leave type check
$leaveTypeArray=$applyLeaveManager->getEmployeeLeaveSetMapping(' AND lsm.leaveSessionId='.$leaveSessionId.' AND lse.employeeId='.$employeeId.' AND lsm.leaveTypeId='.$leaveType);
if(count($leaveTypeArray)==0){
    echo INCORRECT_LEAVE_TYPE_FOR_EMPLOYEE;
    die;
}

//date range check
$dateCondition="AND
                   ( 
                     (l.leaveFromDate BETWEEN '$fromDate' AND '$toDate' )
                     OR
                     (l.leaveToDate BETWEEN '$fromDate' AND '$toDate' )
                     OR
                     (l.leaveFromDate <='$fromDate' AND l.leaveToDate >='$toDate')
                   )";
$dateArray=$applyLeaveManager->getEmployeeLeavesList(' AND l.leaveSessionId='.$leaveSessionId.' AND emp.employeeId='.$employeeId.' AND l.leaveStatus NOT IN (3,4) '.$dateCondition.' AND l.leaveId!='.$leaveId);
if(count($dateArray)!=0){
    echo 'Leaves can not be applied from '.UtilityManager::formatDate($fromDate).' to '.UtilityManager::formatDate($toDate);
    die;
}

//if all goes well,edit records in leave table and comments table

 if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
   //add in leave table
   $ret=$applyLeaveManager->editEmployeeLeave($leaveId,$leaveType,$applyDate,$fromDate,$toDate,$substituteEmployee1,$leaveFormat);
   if($ret==false){
      echo FAILURE;
      die; 
   }
   //add record in commnets table
   $ret=$applyLeaveManager->editEmployeeLeaveComments($leaveId,$employeeId,$leaveReason,date('Y-m-d'),$leaveSessionId);
   if($ret==false){
      echo FAILURE;
      die; 
   }
    $sessionHandler->setSessionVariable('IdLeaveToFileUpload',$leaveId);
    $sessionHandler->setSessionVariable('OperationMode',2);
    //Stores file upload info
   $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
   echo SUCCESS;
   
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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
