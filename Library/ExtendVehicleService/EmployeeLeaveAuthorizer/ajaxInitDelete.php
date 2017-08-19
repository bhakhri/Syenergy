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
define('MODULE','EmployeeLeaveAuthorizer');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
  /* if (!isset($REQUEST_DATA['mappingId']) || trim($REQUEST_DATA['mappingId']) == '') {
        $errorMessage = EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
    }
    if (trim($errorMessage) == '') { */
      require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php"); 
        $empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance(); 
		require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
		$commonQueryManager = CommonQueryManager::getInstance();
                 
        //finding empId of this record
      /*   $empArray=$empLeaveAuthManager->getMappingData(' WHERE leaveSessionId = '.$leaveSessionId.' AND approvingId='.trim($REQUEST_DATA['mappingId']));
        if(trim($empArray[0]['employeeId'])==''){
            echo EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST;
            die;
        }
        $employeeId = trim($empArray[0]['employeeId']);
        $leaveType=trim($empArray[0]['leaveTypeId']);
        
        $usageArray=$empLeaveAuthManager->checkEmployeeLeaveAuthorizationMappingUsage(' WHERE leaveSessionId = '.$leaveSessionId.' AND  employeeId='.$employeeId.' AND leaveTypeId='.$leaveType);
        if($usageArray[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
        } */
		 $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
        $leaveSessionId='-1';
        if($leaveSessionArray[0]['leaveSessionId']!='') {
           $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
        }    
        if($empLeaveAuthManager->deleteEmployeeLeaveAuthorizerMapping(' WHERE leaveSessionId = '.$leaveSessionId.' AND  approvingId='.trim($REQUEST_DATA['mappingId']))) {
               echo DELETE;
         }
         else {
              echo  DEPENDENCY_CONSTRAINT;
          }
     /* }
    else {
        echo $errorMessage;
    }
   */
    
// $History: ajaxInitDelete.php $    
?>