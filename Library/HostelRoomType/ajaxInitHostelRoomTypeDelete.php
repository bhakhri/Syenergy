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
define('MODULE','HostelRoomType');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['hostelRoomTypeId']) || trim($REQUEST_DATA['hostelRoomTypeId']) == '') {
        $errorMessage = INVALID_HOSTEL_ROOM;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomTypeManager.inc.php");
        $hostelRoomTypeManager =  HostelRoomTypeManager::getInstance();
		$foundArray = $hostelRoomTypeManager -> checkExistanceHostelRoomType('WHERE hostelRoomTypeId='.$REQUEST_DATA['hostelRoomTypeId']);
		$foundArray1 = $hostelRoomTypeManager -> checkExistanceInHostelRoom('WHERE hostelRoomTypeId='.$REQUEST_DATA['hostelRoomTypeId']);
		if ($foundArray1[0]['totalRecords'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

		if ($foundArray[0]['totalRecords'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT; 
		}
		else {
            if($hostelRoomTypeManager->deleteHostelRoomType($REQUEST_DATA['hostelRoomTypeId']) ) {
                echo DELETE;
            }
		}
	}
    else {
        echo $errorMessage;
    }
 
// $History: ajaxInitHostelRoomTypeDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Library/HostelRoomType
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Library/HostelRoomType
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:48a
//Created in $/LeapCC/Library/HostelRoomType
//
//
?>