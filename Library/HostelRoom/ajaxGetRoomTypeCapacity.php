<?php
//-------------------------------------------------------
// Purpose: To get values of hostel room type capacity & rent
//
// Author : Jaineesh
// Created on : (16.07.09)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['hostelRoomTypeId'] ) != '') {
    require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
	$hostelRoomTypeId = $REQUEST_DATA['hostelRoomTypeId'];
	$hostelId = $REQUEST_DATA['hostelId'];

    $foundArray = HostelRoomManager::getInstance()->getHostelRoomTypeDetail($hostelRoomTypeId,$hostelId);
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxGetRoomTypeCapacity.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/26/09   Time: 4:20p
//Updated in $/LeapCC/Library/HostelRoom
//done changes to save, edit & show hostel type according to hostel name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/20/09    Time: 2:59p
//Created in $/LeapCC/Library/HostelRoom
//ajax file to get hostel capacity & rent 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/16/09    Time: 1:16p
//Created in $/Leap/Source/Library/HostelRoom
//get the capacity & rent from hostel room type detail by selecting
//hostel room type
//
?>