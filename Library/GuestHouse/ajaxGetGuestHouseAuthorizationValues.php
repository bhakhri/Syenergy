<?php
//-------------------------------------------------------
// Purpose: To get room type detail
// Author : Dipanjan Bhattacharjee
// Created on : (23.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseAuthorization');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['allocationId']) || trim($REQUEST_DATA['allocationId']) == '') {
        $errorMessage = BOOKING_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
        $guestHouseManager = GuestHouseManager::getInstance();
        $foundArray = $guestHouseManager->getGuestHouseRequestDetails(' WHERE gh.allocationId="'.trim($REQUEST_DATA['allocationId']).'"');
        $startTime=explode(':',$foundArray[0]['arrivalTime']);
        $endTime=explode(':',$foundArray[0]['departureTime']);
        $foundArray[0]['arrival']=$foundArray[0]['arrivalDate'].'~'.$startTime[0].':'.$startTime[1].'*'.$foundArray[0]['arrivalAmPm'];
        $foundArray[0]['departure']=$foundArray[0]['departureDate'].'~'.$endTime[0].':'.$endTime[1].'*'.$foundArray[0]['departureAmPm'];
        $foundArray[0]['arrivalDate'] =UtilityManager::formatDate($foundArray[0]['arrivalDate']).' '.$startTime[0].':'.$startTime[1].' '.$foundArray[0]['arrivalAmPm'];
        $foundArray[0]['departureDate'] =UtilityManager::formatDate($foundArray[0]['departureDate']).' '.$endTime[0].':'.$endTime[1].' '.$foundArray[0]['departureAmPm'];
        if($foundArray[0]['hostelId']==''){
            $foundArray[0]['hostelId']='';
        }
        if($foundArray[0]['hostelRoomId']==''){
            $foundArray[0]['hostelRoomId']='';
        }
        if($foundArray[0]['alternativeArrangement']==''){
            $foundArray[0]['alternativeArrangement']='';
        }
        
        if(is_array($foundArray) && count($foundArray)>0 ) {  
            echo json_encode($foundArray[0]);
        }
        else {
         echo 0;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxGetRooms.php $    
?>