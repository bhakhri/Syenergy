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
define('MODULE','LeaveSetMapping');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['leaveSetMappingId']) || trim($REQUEST_DATA['leaveSetMappingId']) == '') {
        $errorMessage = LEAVE_SET_MAPPING_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");
        $leaveSetMappingManager = LeaveSetMappingManager::getInstance(); 
        
        //check for leave set usage
        $usageArray=$leaveSetMappingManager->checkLeaveSetUsage2(trim($REQUEST_DATA['leaveSetMappingId']));
        if($usageArray[0]['cnt']!=0){
          echo DEPENDENCY_CONSTRAINT;
          die;
        }
        
        if($leaveSetMappingManager->deleteLeaveSetMapping(' WHERE leaveSetMappingId='.trim($REQUEST_DATA['leaveSetMappingId']))) {
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