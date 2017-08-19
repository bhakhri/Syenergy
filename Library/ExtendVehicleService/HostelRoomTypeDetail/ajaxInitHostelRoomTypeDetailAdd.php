<?php
//-------------------------------------------------------
// Purpose: To add hostel room detail
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomTypeDetail');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelName']) || trim($REQUEST_DATA['hostelName']) == '')) {
        $errorMessage .= ENTER_HOSTEL. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomType']) || trim($REQUEST_DATA['roomType']) == '')) {
        $errorMessage .= CHOOSE_ROOM_TYPE . "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['Capacity']) || trim($REQUEST_DATA['Capacity']) == '')) {
        $errorMessage .= ENTER_CAPACITY . "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['noOfBeds']) || trim($REQUEST_DATA['noOfBeds']) == '')) {
        $errorMessage .= ENTER_BEDS . "\n";
    }
    
	$hostelId = $REQUEST_DATA['hostelName'];
	$capacity = $REQUEST_DATA['Capacity'];

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");

		$foundTotalHostelArray = HostelRoomTypeDetailManager::getInstance()->getHostelCapacity($hostelId);
		if($capacity > $foundTotalHostelArray[0]['totalCapacity']) {
			echo CAPACITY_NOT_GREATER;
			die;
		}

        $foundArray = HostelRoomTypeDetailManager::getInstance()->getHostelRoomTypeDetail('AND hrtd.hostelId="'.add_slashes(trim($REQUEST_DATA['hostelName'])).'" AND hrtd.hostelRoomTypeId="'.add_slashes(trim($REQUEST_DATA['roomType'])).'"');
		
			if(trim($foundArray[0]['roomType'])=='') {  //DUPLICATE CHECK
				$returnStatus = HostelRoomTypeDetailManager::getInstance()->addHostelRoomTypeDetail();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else if ($foundArray[0]['hostelRoomTypeId'] == $REQUEST_DATA['roomType']) { 
				echo HOSTELROOM_TYPE_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitHostelRoomTypeDetailAdd.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//
?>
