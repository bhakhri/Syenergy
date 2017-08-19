<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Employee Leave Set Add (Bulk)
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

require_once(MODEL_PATH . "/EmployeeLeaveCarryForwardManager.inc.php");
$employeeLeaveCarryForwardManager = EmployeeLeaveCarryForwardManager::getInstance();   

define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1); 
UtilityManager::ifNotLoggedIn(true);

 $resultId = $REQUEST_DATA['chb'];
 
 
 $insertString='';  
 $condition='';
 for($i=0;$i<count($resultId);$i++) {
    if($resultId[$i]!='') {
        $resultIdArray = explode('~',$resultId[$i]);    
       
        $leaveSessionId = $resultIdArray[0];
        $employeeId = $resultIdArray[1];
        $leaveTypeId = $resultIdArray[2];
        $leavesAdded = $resultIdArray[3];
        
        //checking leave type and value entries and building insert query
        if($insertString=='') {
          $insertString  .= "($leaveSessionId, $employeeId,$leaveTypeId,$leavesAdded)";
          $condition    .= "'$leaveSessionId#$employeeId'"; 
        }
        else {
          $insertString .= ",($leaveSessionId, $employeeId,$leaveTypeId,$leavesAdded)";
          $condition   .= ",'$leaveSessionId#$employeeId'";   
        }
    }
 }
 

 if($insertString!='') {
    $deleteCondition = " CONCAT(leaveSessionId,'#',employeeId) IN ($condition) ";  
    if(SystemDatabaseManager::getInstance()->startTransaction()) {   
       $ret=$employeeLeaveCarryForwardManager->deleteEmployeeCarryForwad($deleteCondition); 
       if($ret==false){
         echo FAILURE;
         die;
       }
       $ret=$employeeLeaveCarryForwardManager->addEmployeeCarryForwad($insertString);  
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
 }
 else {
    echo "Not selected Carry Forwd.";
    die;
 }
?>