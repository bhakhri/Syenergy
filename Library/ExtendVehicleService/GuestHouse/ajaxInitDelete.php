<?php
//-------------------------------------------------------
// Purpose: To delete guest house request
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseRequest');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['allocationId']) || trim($REQUEST_DATA['allocationId']) == '') {
        $errorMessage = 'Invalid Booking';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
        $guestHouseManager =  GuestHouseManager::getInstance();
        
        //check for status of guest house request.if it is in wainting state then it can edited,otherwise not
        $statusArray=$guestHouseManager->checkGuestHouseRequestStatus(trim($REQUEST_DATA['allocationId']));
        if($statusArray[0]['isAllocated']!=0){
            echo BOOKING_DELETE_RESTRICTON;
            die;
        }
        if($guestHouseManager->deleteGuestHouseRequest(trim($REQUEST_DATA['allocationId'])) ) {
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