<?php
//-------------------------------------------------------
// Purpose: To delete city detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','EmployeeLeaveAuthorizerAdv');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '') {
        $errorMessage = EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
    }
    if (!isset($REQUEST_DATA['leaveTypeId']) || trim($REQUEST_DATA['leaveTypeId']) == '') {
        $errorMessage = EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php"); 
        $empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance(); 
        
        $employeeId = trim($REQUEST_DATA['employeeId']);
        $leaveTypeId  = trim($REQUEST_DATA['leaveTypeId']);
        
        // Set session
        $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
        $leaveSessionId='-1';
        if($leaveSessionArray[0]['leaveSessionId']!='') {
           $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
        }    
        
        
        //check for usage in leave table        
        $usageArray=$empLeaveAuthManager->checkEmployeeLeaveAuthorizationMappingUsage(' WHERE leaveSessionId = '.$leaveSessionId.' AND employeeId='.$employeeId.' AND leaveTypeId='.$leaveTypeId);
        if($usageArray[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        
        if($empLeaveAuthManager->deleteEmployeeLeaveAuthorizerMapping(' WHERE leaveSessionId = '.$leaveSessionId.' AND employeeId='.$employeeId.' AND leaveTypeId='.$leaveTypeId)){
               echo DELETE;
               die;
         }
         else {
              echo DEPENDENCY_CONSTRAINT;
              die;
          }
    }
    else {
        echo $errorMessage;
    }
   
?>    
  
