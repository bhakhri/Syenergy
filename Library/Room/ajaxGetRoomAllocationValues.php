<?php
//-------------------------------------------------------
// Purpose: To delete room detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (23.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomAllocation');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
$roomManager =  RoomAllocationManager::getInstance();
        
        
    if (!isset($REQUEST_DATA['hostelStudentId']) || trim($REQUEST_DATA['hostelStudentId']) == '') {
        $errorMessage = INVALID_ROOM_ALLOCATION;
    }
    if (trim($errorMessage) == '') {
        //check for checkOut Date
        /*
        $ret=$roomManager->checkStudentData(' WHERE hostelStudentId='.$REQUEST_DATA['hostelStudentId']);
        if($ret[0]['dateOfCheckOut']!='0000-00-00' and $ret[0]['modifyOnDate']!=date('Y-m-d')){
            echo ROOM_ALLOCATION_EDIT_RESTRICTION;
            die;
        }
        */
        
        /*
            $condition = " AND hs.hostelStudentId='".$REQUEST_DATA['hostelStudentId']."'";
            $ret=$roomManager->checkStudentPayData($condition); 
            if(count($ret)>0) {
              if($ret[0]['isHostelPaid'] == '1') {
                echo ROOM_ALLOCATION_EDIT_RESTRICTION;
                die; 
              } 
            }
        */
        $condition = " AND hostelStudentId='".$REQUEST_DATA['hostelStudentId']."'";  
        $foundArray=$roomManager->getRoomAllocationData($condition);
        if(is_array($foundArray) && count($foundArray)>0 ) {
            echo json_encode($foundArray[0]);
        }
        else{
            echo  INVALID_ROOM_ALLOCATION;
        } 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxGetRoomAllocationValues.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:34
//Created in $/LeapCC/Library/Room
//Added files for "Room Allocation Master"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/04/09   Time: 17:57
//Created in $/Leap/Source/Library/Room
//Created "Room Allocation Master"
?>
