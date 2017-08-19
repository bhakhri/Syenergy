<?php
//-------------------------------------------------------
// Purpose: To update hostel room table data
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomCourse');
define('ACCESS','edit');
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
	$hostelRoomId = $REQUEST_DATA['hostelRoomId'];
	//echo ($roomCapacity);
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
		$foundTotalHostelArray = HostelRoomManager::getInstance()->getHostelCapacity($hostelId);
		//print_r($foundTotalHostelArray);

		$foundHostelCapacityArray = HostelRoomManager::getInstance()->getTotalHostel($hostelId);
		//print_r($foundHostelCapacityArray);

		if($foundHostelCapacityArray[0]['cnt'] > $foundTotalHostelArray[0]['roomTotal']) {
			echo ROOM_NOT_GREATER;
			exit(0);
		}
		//echo($foundHostelCapacityArray[0]['roomCapacity']);
		//echo($foundTotalHostelArray[0]['totalCapacity']);

		if($roomCapacity > $foundTotalHostelArray[0]['totalCapacity']) {
			echo CAPACITY_NOT_GREATER;
			exit(0);
		}

		$foundNewArray = HostelRoomManager::getInstance()->getEditHostelCapacity($hostelRoomId,$hostelId);
		$hostelRoomCapacity = $foundTotalHostelArray[0]['totalCapacity'] - $foundNewArray[0]['roomCapacity'];
		//echo($hostelRoomCapacity);
			if($roomCapacity > $hostelRoomCapacity) {
				echo CAPACITY_NOT_GREATER;
				exit(0);
			}
        $foundArray = HostelRoomManager::getInstance()->getHostelRoom(' AND UCASE(roomName)="'.add_slashes(strtoupper($REQUEST_DATA['roomName'])).'" AND hostelId='.$REQUEST_DATA['hostelId'].' AND hostelRoomId!='.$REQUEST_DATA['hostelRoomId']);
			if(trim($foundArray[0]['roomName'])=='') {  //DUPLICATE CHECK
				$returnStatus = HostelRoomManager::getInstance()->editHostelRoom($REQUEST_DATA['hostelRoomId']);
					if($returnStatus === false) {
						echo FAILURE;
					}
					else {
						echo SUCCESS;           
					}
			}
			else {
				echo HOSTELROOM_NAME_EXIST;
			}
    }
    else {
       echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
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
//User: Jaineesh     Date: 8/28/08    Time: 4:09p
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
//User: Pushpender   Date: 6/18/08    Time: 7:57p
//Updated in $/Leap/Source/Library/States
//just added duplicate message and changed single quote to double quotes
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:42p
//Updated in $/Leap/Source/Library/States
//added Comments and refined the code
?>
