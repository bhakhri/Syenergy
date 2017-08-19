<?php
//-------------------------------------------------------
// Purpose: To delete hostel room detail through Id
// Name : id -> hostelRoomId
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomCourse');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['hostelRoomId']) || trim($REQUEST_DATA['hostelRoomId']) == '') {
        $errorMessage = INVALID_HOSTELROOM;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
        $hostelRoomManager =  HostelRoomManager::getInstance();
            if($hostelRoomManager->deleteHostelRoom($REQUEST_DATA['hostelRoomId']) ) {
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
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HostelRoom
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:46p
//Updated in $/Leap/Source/Library/HostelRoom
//add define access in module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:08p
//Updated in $/Leap/Source/Library/HostelRoom
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/26/08    Time: 4:03p
//Updated in $/Leap/Source/Library/HostelRoom
//modified in message
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:34p
//Created in $/Leap/Source/Library/HostelRoom
//ajax functions of add, delete, edit & search
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>