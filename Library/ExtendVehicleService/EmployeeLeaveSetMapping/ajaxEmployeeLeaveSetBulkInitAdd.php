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

require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
$empLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();   

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    $leaveSetIds = $REQUEST_DATA['leaveSet'];
    $employeeIds = $REQUEST_DATA['employeeIds']; 
    
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
    
    if($employeeIds=='') {
      $employeeIds=0;  
    }
    
    $notUpdateEmployeeId='';
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        for($i=0;$i<count($employeeIds);$i++) {      
            $employeeId =  $employeeIds[$i];  
            $leaveSet   =  $leaveSetIds[$i];     
        
            //insert check
            $leaveCondition = " AND l.leaveSessionId ='$leaveSessionId' AND l.employeeId='$employeeId'";
            $usageArray=$empLeaveSetMappingManager->checkEmployeeLeaveSetMappingUsage($leaveCondition);
            $leaveCount = $usageArray[0]['cnt'];
            if($leaveCount=='') {
              $leaveCount=0;  
            }
            if($leaveCount!=0) {
              if($leaveSet=='') {
                 if($notUpdateEmployeeId=='') {
                   $notUpdateEmployeeId = $employeeIds[$i]; 
                 }
                 else {
                   $notUpdateEmployeeId .=",".$employeeIds[$i];  
                 }
              }
            }
            else {
                //first delete previous values corresponding to Employee
                $ret=$empLeaveSetMappingManager->deleteEmployeeLeaveSetMapping(' WHERE leaveSessionId ='.$leaveSessionId.' AND employeeId='.$employeeId);
                if($ret==false){
                  echo FAILURE;
                  die;
                }
                //Insert values corresponding to leaveSessionId, EmployeeId, leaveSetId 
                if($leaveSet!='') {  
                    //checking leave type and value entries and building insert query
                    $insertString='';
                    $insertString .="( $leaveSessionId, $employeeId,$leaveSet )";
                       
                    //then make inset
                    $ret=$empLeaveSetMappingManager->doEmployeeLeaveSetMapping($insertString);
                    if($ret==false){
                       echo FAILURE;
                       die;
                    }
                }
            }
        }
        if($notUpdateEmployeeId!='') {
           echo "Data could not be edited due to records existing in linked tables!~~!$notUpdateEmployeeId";
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
      
?>