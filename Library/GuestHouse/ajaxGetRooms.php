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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['hostelId']) || trim($REQUEST_DATA['hostelId']) == '') {
        $errorMessage = 'Invalid Hostel';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
        $roomManager =  RoomAllocationManager::getInstance();
        $foundArray = $roomManager->getRoomData(' WHERE hr.hostelId="'.trim($REQUEST_DATA['hostelId']).'"');
        if(is_array($foundArray) && count($foundArray)>0 ) {  
            echo json_encode($foundArray);
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