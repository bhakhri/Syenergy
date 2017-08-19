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
define('MODULE','HostelRoomCourse');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomName']) || trim($REQUEST_DATA['roomName']) == '')) {
        $errorMessage .= ENTER_HOSTELROOM_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomCapacity']) || trim($REQUEST_DATA['roomCapacity']) == '')) {
        $errorMessage .= ENTER_HOSTELROOM_CAPACITY. "\n";
    }
  

	$hostelId = $REQUEST_DATA['hostelId'];
	$roomCapacity = $REQUEST_DATA['roomCapacity'];
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
		$foundTotalHostelArray = HostelRoomManager::getInstance()->getHostelCapacity($hostelId);
		$foundHostelCapacityArray = HostelRoomManager::getInstance()->getTotalHostel($hostelId);
		$roomCapacity = $roomCapacity + $foundHostelCapacityArray[0]['roomCapacity'];
		if($foundHostelCapacityArray[0]['cnt'] >= $foundTotalHostelArray[0]['roomTotal']) {
			echo ROOM_NOT_GREATER;
			exit(0);
		}
		if($roomCapacity > $foundTotalHostelArray[0]['totalCapacity']) {
			echo CAPACITY_NOT_GREATER;
			exit(0);
		}
		
        $foundArray = HostelRoomManager::getInstance()->getHostelRoom(' AND UCASE(roomName)="'.add_slashes(strtoupper($REQUEST_DATA['roomName'])).'" AND hostelId='.$REQUEST_DATA['hostelId'].'');
			if(trim($foundArray[0]['roomName'])=='') {  //DUPLICATE CHECK
				$returnStatus = HostelRoomManager::getInstance()->addHostelRoom();
				if($returnStatus === false) {
					echo FAILURE;
					die;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				echo HOSTEL_ROOM_NAME_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitAdd.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/17/09    Time: 7:34p
//Updated in $/LeapCC/Library/HostelRoom
//fixed bug nos.0001093, 0001086, 0000672, 0001087
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:48p
//Created in $/LeapCC/Library/HostelRoom
//put conditions on hostel room & capacity
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/05/09    Time: 1:07p
//Updated in $/Leap/Source/Library/HostelRoom
//add new field hostel room type
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
