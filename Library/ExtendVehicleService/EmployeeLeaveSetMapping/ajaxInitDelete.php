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
define('MODULE','EmployeeEmployeeLeaveSetMapping');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['mappingId']) || trim($REQUEST_DATA['mappingId']) == '') {
        $errorMessage = EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php"); 
        $empLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance(); 
        
        //delete check
        $empArray=$empLeaveSetMappingManager->getEmployeeLeaveSetMappingData(' AND m.employeeLeaveSetMappingId='.trim($REQUEST_DATA['mappingId']));
        $employeeId=$empArray[0]['employeeId'];
        if($employeeId==''){
            echo EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST;
            die;
        }
        
        //check for with authorizer table also !!!.
        $usageArray=$empLeaveSetMappingManager->checkEmployeeAuthorzerUsage($employeeId);
        if($usageArray[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        
        $usageArray=$empLeaveSetMappingManager->checkEmployeeLeaveSetMappingUsage(' AND l.employeeId='.$employeeId);
        if($usageArray[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        
        if($empLeaveSetMappingManager->deleteEmployeeLeaveSetMapping(' WHERE employeeLeaveSetMappingId='.trim($REQUEST_DATA['mappingId']))) {
               echo DELETE;
         }
         else {
              echo DEPENDENCY_CONSTRAINT;
          }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
?>