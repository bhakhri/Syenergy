<?php
//-------------------------------------------------------
// Purpose: To update hostel room table data
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
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomType']) || trim($REQUEST_DATA['roomType']) == '')) {
        $errorMessage .= ENTER_HOSTEL_ROOM_TYPE_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomAbbr']) || trim($REQUEST_DATA['roomAbbr']) == '')) {
        $errorMessage .= ENTER_HOSTEL_ROOM_ABBR . "\n";
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomTypeManager.inc.php");
        $foundArray = HostelRoomTypeManager::getInstance()->getHostelRoomType('WHERE (LCASE(roomType)="'.add_slashes(strtolower($REQUEST_DATA['roomType'])).'" OR LCASE(roomAbbr)="'.add_slashes(strtolower($REQUEST_DATA['roomAbbr'])).'") AND hostelRoomTypeId!='.$REQUEST_DATA['hostelRoomTypeId']);
			if(trim($foundArray[0]['roomType'])=='') { //DUPLICATE CHECK
				if(trim($foundArray[0]['roomAbbr'])=='') {
				$returnStatus = HostelRoomTypeManager::getInstance()->editHostelRoomType($REQUEST_DATA['hostelRoomTypeId']);
					if($returnStatus === false) {
						echo FAILURE;
					}
					else {
						echo SUCCESS;           
					}
			}
			}
			else if (strtolower($foundArray[0]['roomType']) == strtolower($REQUEST_DATA['roomType'])) {
				echo HOSTELROOM_TYPE_EXIST;
			}
			else if (strtolower($foundArray[0]['roomAbbr']) == strtolower($REQUEST_DATA['roomAbbr'])) {
				echo HOSTELROOM_TYPE_ABBR_EXIST;
			}
    }
    else {
       echo $errorMessage;
    }

// $History: ajaxInitHostelRoomTypeEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/11/09    Time: 6:32p
//Updated in $/LeapCC/Library/HostelRoomType
//fixed issue nos.0000093,0000094,0000096
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/07/09    Time: 5:11p
//Updated in $/LeapCC/Library/HostelRoomType
//bug fixed build no. BuildCC#cc 0001.doc 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:48a
//Created in $/LeapCC/Library/HostelRoomType
//
?>