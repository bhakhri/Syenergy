<?php
//-------------------------------------------------------
// Purpose: To delete hostel room detail through Id
// Name : id -> hostelRoomTypeId
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CleaningMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['cleanId']) || trim($REQUEST_DATA['cleanId']) == '') {
        $errorMessage = INVALID_CLEANING_ROOM_DETAIL;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
        $cleaningRoomManager =  CleaningRoomManager::getInstance();
            if($cleaningRoomManager->deleteHostelRoomTypeDetail($REQUEST_DATA['cleanId']) ) {
                echo DELETE;
            }
			else {
				echo DEPENDENCY_CONSTRAINT;
			}
	}
    else {
        echo $errorMessage;
    }
 
// $History: ajaxInitCleaningRoomDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Library/CleaningRoom
//fixed bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:27p
//Created in $/LeapCC/Library/CleaningRoom
//all ajax files for cleaning room
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//
//
?>