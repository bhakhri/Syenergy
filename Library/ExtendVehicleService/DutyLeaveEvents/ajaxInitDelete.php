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
define('MODULE','DutyLeaveEvents');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['eventId']) || trim($REQUEST_DATA['eventId']) == '') {
        $errorMessage = DUTY_LEAVE_EVENT_NOT_EXIST;
    }
    
    if(trim($errorMessage) == '') {
       require_once(MODEL_PATH . "/DutyLeaveEventsManager.inc.php");
       $leaveManager =  DutyLeaveEventsManager::getInstance();
       
       //check for its usage in duty_leave table
       $foundArray=$leaveManager->checkDutyLeave(trim($REQUEST_DATA['eventId']));
       if($foundArray[0]['found']!=0){
           die(DEPENDENCY_CONSTRAINT);
       }
       
       if($leaveManager->deleteEvent(trim($REQUEST_DATA['eventId']))) {
            echo DELETE;
            die;
        }
       else {
            echo DEPENDENCY_CONSTRAINT;
            die;
       }
       /*
       $recordArray = $leaveManager->checkInGuestHouse(trim($REQUEST_DATA['budgetHeadId']));
       if($recordArray[0]['found']==0) {
            if($leaveManager->deleteBudgetHeads(trim($REQUEST_DATA['budgetHeadId']))) {
                echo DELETE;
                die;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
       }
       else {
            echo DEPENDENCY_CONSTRAINT;
            die;
       }
       */
    }
    else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $    
?>