<?php
//-------------------------------------------------------
// Purpose: To get room type detail
// Author : Dipanjan Bhattacharjee
// Created on : (23.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseAuthorization');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$allocationId=trim($REQUEST_DATA['allocationId']);
$roomId=trim($REQUEST_DATA['roomId']);
$isAllocated=trim($REQUEST_DATA['allocated']);
$reason=trim($REQUEST_DATA['reason']);
$fromDate=trim($REQUEST_DATA['date1']);
$toDate=trim($REQUEST_DATA['date2']);

if($allocationId==''){
   echo BOOKING_NOT_EXIST; 
   die;
}
if($isAllocated==1){
 if($roomId==''){
   echo "Room Information Missing"; 
   die;
 }
 if($fromDate=='' or $toDate==''){
     echo "Date Information Missing";
     die;
 }
}
else if($isAllocated==2){
   if($reason==''){
       echo ENTER_GUEST_HOUSE_REJECTION_REASON;
       die;
   }
   if(strlen($reason)<5){
       echo GUEST_HOUSE_REJECTION_REASON_LENGTH;
       die;
   }
}
else{
    echo "Invalid Allocation Value";
    die;
}

require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
$guestHouseManager = GuestHouseManager::getInstance();

$userId=$sessionHandler->getSessionVariable('UserId');
$date=date('Y-m-d');


if($isAllocated==2){
    $ret=$guestHouseManager->makeGuestHouseRequestReject($allocationId,$reason,$userId,$date,$isAllocated);
    if($ret==false){
        echo FAILURE;
        die;
    }
    else{
        echo SUCCESS;
        die;
    }
}
else if($isAllocated==1){
    //check for vacant position
    $date1=explode('~',trim($REQUEST_DATA['date1']));
    $date2=explode('~',trim($REQUEST_DATA['date2']));
    
    $arrDate=$date1[0];
    $arrDatePart=explode('-',$date1[0]);
    $arrTimeArray=explode('*',$date1[1]);
    $arrTime=$arrTimeArray[0];
    $arrAmPm=$arrTimeArray[1];
    
    $deptDate=$date2[0];
    $deptDatePart=explode('-',$date2[0]);
    $deptTimeArray=explode('*',$date2[1]);
    $deptTime=$deptTimeArray[0];
    $deptAmPm=$deptTimeArray[1];
    
    $startHours = intval(substr($arrTime,0,2));
    $endHours = intval(substr($deptTime,0,2));
    $startMins = intval(substr($arrTime,3));
    $endMins = intval(substr($deptTime,3));
    $startSec=0;
    $endSec=0;
    
    if ($startHours != 12 and $arrAmPm == 'PM') {
        $startHours += 12;
    }
    else if ($startHours == 12 and $arrAmPm == 'AM') {
        $startHours = 0;
    }
    if ($endHours != 12 and $deptAmPm == 'PM') {
        $endHours += 12;
    }
    else if ($endHours == 12 and $deptAmPm == 'AM') {
        $endHours = 0;
    }
    
    $startDateTime = mktime($startHours, $startMins, $startSec, $arrDatePart[1], $arrDatePart[2], $arrDatePart[0]);
    $endDateTime = mktime($endHours, $endMins, $endSec, $deptDatePart[1], $deptDatePart[2], $deptDatePart[0]);
    
    $guestHouseRecordArray = $guestHouseManager->getPossibleVacantRoomList2($filter,' WHERE hr.hostelRoomId='.$roomId);
    $roomRecordArray = array();
    $conflictsFound = array();
    foreach ($guestHouseRecordArray as $record) {
        $hostelRoomId = $record['hostelRoomId'];
        $roomCapacity = $record['roomCapacity'];
        $roomRecordArray[$hostelRoomId]['hostelRoomId'] = $hostelRoomId;
        $roomRecordArray[$hostelRoomId]['capacity'] = $roomCapacity;
        $roomRecordArray[$hostelRoomId]['roomName'] = $record['roomName'];
        $roomRecordArray[$hostelRoomId]['hostelName'] = $record['hostelName'];
        $roomRecordArray[$hostelRoomId]['hostelId'] = $record['hostelId'];
        
        if (!isset($conflictsFound[$hostelRoomId])) {
            $conflictsFound[$hostelRoomId] = 0;
        }
        
        if($record['arrivalDate']==-1){
            continue;
        }

        $tempArrivalDayArray = explode('-',$record['arrivalDate']);
        $tempStartHours = intval(substr($record['arrivalTime'],0,2));
        $tempStartMins = intval(substr($record['arrivalTime'],3));
        $tempArrivalTimeAmPm = $record['arrivalAmPm'];

        $tempDepartureDayArray = explode('-',$record['departureDate']);
        $tempEndHours = intval(substr($record['departureTime'],0,2));
        $tempEndMins = intval(substr($record['departureTime'],3));
        $tempDepartureTimeAmPm = $record['departureAmPm'];
        $tempStartSec=0;
        $tempEndSec=0;

        if($tempStartHours != 12 and $tempArrivalTimeAmPm == 'PM') {
            $tempStartHours += 12;
        }
        else if ($startHours == 12 and $tempArrivalTimeAmPm == 'AM') {
            $tempStartHours = 0;
        }
        if($tempEndHours != 12 and $tempDepartureTimeAmPm == 'PM') {
            $tempEndHours += 12;
        }
        else if ($tempEndHours == 12 and $tempDepartureTimeAmPm == 'AM') {
            $tempEndHours = 0;
        }

        $tempStartDateTime = mktime($tempStartHours, $tempStartMins, $tempStartSec, $tempArrivalDayArray[1], $tempArrivalDayArray[2], $tempArrivalDayArray[0]);
        $tempEndDateTime = mktime($tempEndHours, $tempEndMins, $tempEndSec, $tempDepartureDayArray[1], $tempDepartureDayArray[2], $tempDepartureDayArray[0]);


        if ($startDateTime == $tempStartDateTime) {
            $conflictsFound[$hostelRoomId]++;
        }
        elseif ($startDateTime > $tempStartDateTime and $startDateTime < $tempEndDateTime) {
            $conflictsFound[$hostelRoomId]++;
        }
        elseif ($startDateTime < $tempStartDateTime and $endDateTime > $tempStartDateTime) {
            $conflictsFound[$hostelRoomId]++;
        }
    }
    
    $vacantCount=0;
    foreach($roomRecordArray as $hostelRoomId => $detailsArray){
        $roomCapacity = $detailsArray['capacity'];
        $conflicts = $conflictsFound[$hostelRoomId];
        if ($roomCapacity > $conflicts) {
            $vacantCount=1;
            break;
        }
    }
    
    if($vacantCount==1){
        $ret=$guestHouseManager->makeGuestHouseRequestAccept($allocationId,$roomId,$userId,$date,$isAllocated);
        if($ret==false){
            echo FAILURE;
            die;
        }
        else{
            echo SUCCESS;
            die;
        }
    }
    else{
        echo GUEST_HOUSE_ROOM_FULL;
        die;
    }
}
        
    
// $History: ajaxGetRooms.php $    
?>