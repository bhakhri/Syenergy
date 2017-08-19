<?php
//-------------------------------------------------------
// Purpose: To delete Room Type detail
//
// Author : Gurkeerat Sidhu
// Created on : (19.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomTypeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['roomTypeId']) || trim($REQUEST_DATA['roomTypeId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoomTypeManager.inc.php");
            $roomTypeManager =  RoomTypeManager::getInstance();
            
            //check in room table
            $foundArray=$roomTypeManager->checkInRoom($REQUEST_DATA['roomTypeId']);
            if($foundArray[0]['cnt']!='0'){
                die(DEPENDENCY_CONSTRAINT);
            }
            
            if($roomTypeManager->deleteRoomType($REQUEST_DATA['roomTypeId'])) {
				echo DELETE;
			}
    }
    else {
        echo $errorMessage;
    }
   
    

?>

