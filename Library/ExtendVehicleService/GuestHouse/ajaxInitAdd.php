<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseRequest');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     if (!isset($REQUEST_DATA['guestName']) || trim($REQUEST_DATA['guestName']) == '') {
        $errorMessage .=  ENTER_GUEST_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['budgetHead']) || trim($REQUEST_DATA['budgetHead']) == '')) {
        $errorMessage .= SELET_BUDGET_HEAD."\n";  
    }
    
    $startAmPm = $REQUEST_DATA['startAmPm'];
    $endAmPm = $REQUEST_DATA['endAmPm'];
    $startTime = $REQUEST_DATA['startTime'];
    $endTime = $REQUEST_DATA['endTime'];
     
    //time checking happens when dates are same
    if(trim($REQUEST_DATA['arrivalDate'])==trim($REQUEST_DATA['departureDate'])){
      $startHours = intval(substr($startTime,0,2));
      $endHours = intval(substr($endTime,0,2));
      if($startHours > 12 or $startHours < 0) {
        echo 'Hours cannot greater than 12 or less than 0';
        die;
      }
      if($endHours > 12 or $endHours < 0) {
        echo 'Hours cannot greater than 12 or less than 0';
        die;
      }
    
      $startMins = intval(substr($startTime,3));
      $endMins = intval(substr($endTime,3));
    
      if($startAmPm == $endAmPm  and $startAmPm == 'AM') {
        if($startHours > $endHours) {
            echo 'Start Time should not be greater than End Time';
            die;
        }
        elseif($startHours == $endHours and $startMins > $endMins) {
            echo 'Start Time should not be greater than End Time';
            die;
        }
     }
     elseif ($startAmPm == 'AM'  and $endAmPm == 'PM') {
        //everything ok
     }
     elseif ($startAmPm == 'PM' and $endAmPm == 'AM') {
        echo 'Start Time should not be greater than End Time';
        die;
     }
     elseif ($startAmPm == 'PM'  and $endAmPm == 'PM') {
        if($startHours == 12) {
            if($endHours == 12) {
                if($startMins > $endMins) {
                    echo 'Start Time should not be greater than End Time';
                    die;
                }
            }
            else {
                //ok
            }
        }
        else {
            if($startHours > $endHours) {
                echo 'Start Time should not be greater than End Time';
                die;
            }
            elseif($startHours == $endHours and $startMins > $endMins) {
                echo 'Start Time should not be greater than End Time';
                die;
            }
        }
     }
     
     //***********time based check*********************
     list($startHour, $startMin, $startSec) = explode(':', trim($startTime));
     $periodStartAMPM = $startAmPm;
     if ($startHour != 12 and $periodStartAMPM == 'PM') {
        $startHour += 12;
     }
     else if ($startHour == 12 and $periodStartAMPM == 'AM') {
        $startHour = 0;
     }
     list($endHour, $endMin, $endSec) = explode(':', trim($endTime));
     $periodEndAMPM = $endAmPm;
     if ($endHour != 12 and $periodEndAMPM == 'PM') {
        $endHour += 12;
     }
     else if ($endHour == 12 and $periodEndAMPM == 'AM') {
        $endHour = 0;
     }
     
     $oStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
     $oEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);
     if ($oStartDateTime > $oEndDateTime) {
        echo 'Start Time can not be greater than End Time';
        die;
     }
     //***********time based check*********************
     
    }
    
    
    

    

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
        $isAllocated=0;
        $requestedUserId=$sessionHandler->getSessionVariable('UserId');
        $requestedDate=date('Y-m-d');
        //generate booking no
        $bookingNo=GuestHouseManager::getInstance()->getAutoGeneratedBookingNo();
        $returnStatus = GuestHouseManager::getInstance()->addGuestHouseRequest($bookingNo,trim($REQUEST_DATA['guestName']),trim($REQUEST_DATA['arrivalDate']),trim($REQUEST_DATA['departureDate']),$isAllocated,$requestedUserId,$requestedDate,trim($REQUEST_DATA['budgetHead']),$startAmPm,$endAmPm,$startTime,$endTime);
        if($returnStatus === false) {
           echo FAILURE;
        }
        else {
            echo SUCCESS;           
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>