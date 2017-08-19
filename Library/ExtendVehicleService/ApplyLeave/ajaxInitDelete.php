<?php
//-------------------------------------------------------
// Purpose: To delete city detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyEmployeeLeave');
define('ACCESS','delete');
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
    if (!isset($REQUEST_DATA['mappingId']) || trim($REQUEST_DATA['mappingId']) == '') {
        $errorMessage = EMPLOYEE_LEAVE_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php"); 
        $applyLeaveManager = ApplyLeaveManager::getInstance();
        //check for status
        $empArray=$applyLeaveManager->getEmployeeLeaveInfo(' WHERE leaveId='.trim($REQUEST_DATA['mappingId']));
        if(count($empArray)==0){
            echo EMPLOYEE_LEAVE_NOT_EXIST;
            die;
        }

        if($empArray[0]['leaveStatus']!=0){
         echo EMPLOYEE_LEAVE_CANCEL_RESTRICTION;
         die;
        } 
        $ret=$applyLeaveManager->cancelAppliedLeave(' WHERE leaveId='.trim($REQUEST_DATA['mappingId']));
        if($ret==false){
            echo FAILURE;
            die;
        }
        else{
            echo SUCCESS;
            die;
        }
        
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
?>