<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaveEvents');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['eventTitle']) || trim($REQUEST_DATA['eventTitle']) == '') {
        $errorMessage .=  ENTER_DUTY_LEAVE_EVENT_NAME."\n"; 
    }
    
    if(trim($REQUEST_DATA['eventId'])==''){
        echo DUTY_LEAVE_EVENT_NOT_EXIST;
        die;
    }
    
    //check for its usage in duty_leave table
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DutyLeaveEventsManager.inc.php");
        
        //check for its usage in duty_leave table
        $foundArray=DutyLeaveEventsManager::getInstance()->checkDutyLeave(trim($REQUEST_DATA['eventId']));
        if($foundArray[0]['found']!=0){
           die(DEPENDENCY_CONSTRAINT_EDIT);
        }
       
        $startDate=trim($REQUEST_DATA['startDate']);
        $endDate=trim($REQUEST_DATA['endDate']);
        /*
        $dateConditions="WHERE ( (startDate BETWEEN '.$startDate' AND '$endDate') 
                                   OR
                               (endDate BETWEEN '$startDate' AND '$endDate') 
                                OR
                               (startDate <= '$startDate' AND endDate >= '$endDate') )";
         */                         
        $foundArray = DutyLeaveEventsManager::getInstance()->getEvent($dateConditions.' WHERE ( UCASE(eventTitle)="'.add_slashes(strtoupper(trim($REQUEST_DATA['eventTitle']))).'") AND eventId!='.trim($REQUEST_DATA['eventId']));
        if(trim($foundArray[0]['eventTitle'])=='') {  //DUPLICATE CHECK
            $returnStatus = DutyLeaveEventsManager::getInstance()->editEvent(trim($REQUEST_DATA['eventId']));
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo DUTY_ELAVE_EVENT_ALREADY_EXIST;
            die;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
?>