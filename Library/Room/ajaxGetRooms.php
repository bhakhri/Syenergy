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
define('MODULE','RoomAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
$roomManager =  RoomAllocationManager::getInstance();
        
    if (!isset($REQUEST_DATA['hostelId']) || trim($REQUEST_DATA['hostelId']) == '') {
        $errorMessage = 'Invalid Hostel';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelRoomTypeId']) || trim($REQUEST_DATA['hostelRoomTypeId']) == '')) {
        $errorMessage .= 'Invalid Room Type'."\n";  
    }
    $classId = $REQUEST_DATA['classId'];
    
    if($classId=='') {
      $classId='0';  
    }
    
    if (trim($errorMessage) == '') {
        $condition = " AND hr.hostelId='".$REQUEST_DATA['hostelId']."' 
                       AND hr.hostelRoomTypeId='".$REQUEST_DATA['hostelRoomTypeId']."'
                       AND hf.classId = '$classId' ";
        $foundArray = $roomManager->getRoomRentData($condition);
        if(count($foundArray) == 0 ) {
           echo "Hostel Fee not defined";
           die;  
        }
        $foundArray2 = $roomManager->getRoomTypeDetailData(' WHERE hrtd.hostelRoomTypeId="'.$REQUEST_DATA['hostelRoomTypeId'].'"');
        if(is_array($foundArray) && count($foundArray)>0 ) {  
          echo json_encode($foundArray).'!~!~!'.json_encode($foundArray2[0]);
        }
        else {
           echo 0;
        }
    }
    else {
        echo $errorMessage;
    }
?>