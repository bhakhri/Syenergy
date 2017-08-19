<?php
//-------------------------------------------------------
// Purpose: To add hostel room detail
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomType');
define('ACCESS','add');
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
        $foundArray = HostelRoomTypeManager::getInstance()->getHostelRoomType(' WHERE LCASE(roomType)="'.add_slashes(strtolower($REQUEST_DATA['roomType'])).'" OR LCASE(roomAbbr)="'.add_slashes(strtolower($REQUEST_DATA['roomAbbr'])).'"');
		//echo '<pre>';
		//print_r($foundArray);
		//die();
		//echo ($foundArray[0]['roomType']);
			if(trim($foundArray[0]['roomType'])=='') {  //DUPLICATE CHECK
				if(trim($foundArray[0]['roomAbbr']) == '' ) {
				$returnStatus = HostelRoomTypeManager::getInstance()->addHostelRoomType();
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
    
// $History: ajaxInitHostelRoomTypeAdd.php $    
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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/19/09    Time: 11:15a
//Created in $/LeapCC/Library/HostelRoom
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 3/19/09    Time: 10:51a
//Updated in $/Leap/Source/Library/HostelRoom
//fixed bug to give room name according to hostel name
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:46p
//Updated in $/Leap/Source/Library/HostelRoom
//add define access in module
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:08p
//Updated in $/Leap/Source/Library/HostelRoom
//modified in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/22/08    Time: 3:33p
//Updated in $/Leap/Source/Library/HostelRoom
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/22/08    Time: 11:05a
//Updated in $/Leap/Source/Library/HostelRoom
//modified in messages
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:34p
//Created in $/Leap/Source/Library/HostelRoom
//ajax functions of add, delete, edit & search
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//changed duplicate message and single quote to double quotes in error
//messages
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:46p
//Updated in $/Leap/Source/Library/States
//To add comments and Refine the code: DONE
?>