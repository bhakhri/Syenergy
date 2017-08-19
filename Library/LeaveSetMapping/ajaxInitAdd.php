<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','LeaveSetMapping');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$leaveSessionId=trim($REQUEST_DATA['leaveSessionId']);  
$leaveSet=trim($REQUEST_DATA['leaveSet']);
$leaveTypeArray=explode(',',trim($REQUEST_DATA['leaveTypeString']));
$leaveTypeValueArray=explode(',',trim($REQUEST_DATA['leaveTypeValueString']));

$leaveTypeCount=count($leaveTypeArray);
$leaveTypeValueCount=count($leaveTypeValueArray);



if($leaveSessionId==''){
    echo SELECT_LEAVE_SESSION;
    die;
}

if($leaveSet==''){
    echo SELECT_LEAVE_SET;
    die;
}

if($leaveTypeCount==0 or $leaveTypeValueCount==0){
    echo 'Required Parameters Missing';
    die;
}

if($leaveTypeCount!=$leaveTypeValueCount){
    echo 'Mismatching leave type and value';
    die;
}

$uniqueCount=count(array_unique($leaveTypeArray));

if($leaveTypeCount!=$uniqueCount){
    echo DUPLICATE_LEAVE_SET_TYPE;
    die;
}

global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');

if($instituteId=='') {
   echo 'Required Parameters Missing';
   die; 
}

// Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    $leaveSessionDate='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
      $leaveSessionDate=$leaveSessionArray[0]['sessionEndDate'];
    }                                                                                                         
    
    if($leaveSessionId=='') {
      echo "Please select atleast one active session";    
      die;  
    }


$leaveSetMappingManager = LeaveSetMappingManager::getInstance(); 
//finding usage of leave types of this leave set.If found they will not be deleted and inserted
$cond = " AND lsm.leaveSessionId = $leaveSessionId";
$usageArray=$leaveSetMappingManager->checkUsageOfLeaveSetMapping($leaveSet,$cond);
$leaveTypeIdArray=array();
if(is_array($usageArray) and count($usageArray)>0){
   $leaveTypeIdArray=explode(',',UtilityManager::makeCSList($usageArray,'leaveTypeId'));
}


//checking leave type and value entries and building insert query
$insertString='';
for($i=0;$i<$leaveTypeCount;$i++){
    if(trim($leaveTypeArray[$i])==''){
        echo SELECT_LEAVE_TYPE;
        die;
    }
    
    if(trim($leaveTypeValueArray[$i])==''){
        echo ENTER_LEAVE_TYPE_VALUE;
        die;
    }
    
    if(!is_numeric(trim($leaveTypeValueArray[$i]))){
        echo ENTER_INTEGER_VALUE;
        die;
    }
    
    if(trim($leaveTypeValueArray[$i])<0){
        echo LEAVE_TYPE_VALUE_GREATER_ZERO;
        die;
    }
    
    if(in_array(trim($leaveTypeArray[$i]),$leaveTypeIdArray)){
        continue;
    }
    
    if($insertString!=''){
        $insertString .=',';
    }
    
    $insertString .="($leaveSessionId, $leaveSet,".trim($leaveTypeArray[$i]).",".trim($leaveTypeValueArray[$i]).",$instituteId )";
}

 if(SystemDatabaseManager::getInstance()->startTransaction()) {
   //first delete previous values corresponding to Leave Set
   $noDeleteCondition='';
   if(count($leaveTypeIdArray)!=0){
       $noDeleteCondition=' AND leaveTypeId NOT IN ('.implode(',',$leaveTypeIdArray).')';
   }
   
   $condition = ' WHERE leaveSessionId ='.$leaveSessionId.' AND leaveSetId='.$leaveSet.$noDeleteCondition;
   $ret=$leaveSetMappingManager->deleteLeaveSetMapping($condition);
   if($ret==false){
       echo FAILURE;
       die;
   }
   
   //then make inset
  if($insertString!=''){ 
   $ret=$leaveSetMappingManager->doLeaveSetMapping($insertString);
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