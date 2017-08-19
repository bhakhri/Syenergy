<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
$empLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();     
define('MODULE','EmployeeEmployeeLeaveSetMapping');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$leaveSessionId=trim($REQUEST_DATA['leaveSessionId']);

$employeeId=trim($REQUEST_DATA['employeeId']);
//$leaveSetArray=explode(',',trim($REQUEST_DATA['leaveSetString']));
$leaveSet=trim($REQUEST_DATA['leaveSet']);


//$leaveSetCount=count($leaveSetArray);

if($leaveSessionId=='') {
   echo "Please select atleast one active session";
   die; 
}

if($employeeId==''){
    echo ENTER_VALID_EMPLOYEE_INFO;
    die;
}
if($leaveSet==''){
    echo SELECT_LEAVE_SET;
    die;
}
/*
if($leaveSetCount==0 ){
    echo 'Required Parameters Missing';
    die;
}

$uniqueCount=count(array_unique($leaveSetArray));

if($leaveSetCount!=$uniqueCount){
    echo DUPLICATE_LEAVE_SET;
    die;
}
*/

//checking leave type and value entries and building insert query
$insertString='';
/*
for($i=0;$i<$leaveSetCount;$i++){
    if(trim($leaveSetArray[$i])==''){
        echo SELECT_LEAVE_SET;
        die;
    }
    
    if($insertString!=''){
        $insertString .=',';
    }
    
    $insertString .="( $employeeId,".trim($leaveSetArray[$i])." )";
}
*/

$condition = ' AND lse.leaveSessionId ='.$leaveSessionId.' AND lse.employeeId='.$employeeId;
$foundArray = $empLeaveSetMappingManager->getEmployeeLeaveSetMappingList($condition); 
if(count($foundArray)>0) { 
  echo "Already assign to Leave Set ";  
  die;  
}

$insertString ="( $leaveSessionId, $employeeId,$leaveSet )";
//insert check
$usageArray=$empLeaveSetMappingManager->checkEmployeeLeaveSetMappingUsage(' AND l.leaveSessionId ='.$leaveSessionId.' AND l.employeeId='.$employeeId);
if($usageArray[0]['cnt']!=0){
    echo 'You can not add/edit leave set for this employee ';
    die;
}

 if(SystemDatabaseManager::getInstance()->startTransaction()) {
   //first delete previous values corresponding to Employee
   $ret=$empLeaveSetMappingManager->deleteEmployeeLeaveSetMapping(' WHERE leaveSessionId ='.$leaveSessionId.' AND employeeId='.$employeeId);
   if($ret==false){
       echo FAILURE;
       die;
   }
   

   //then make inset
   $ret=$empLeaveSetMappingManager->doEmployeeLeaveSetMapping($insertString);
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
// $History: ajaxInitAdd.php $
?>