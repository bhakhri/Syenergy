<?php
//-------------------------------------------------------
// Purpose: To delete hostel room detail through Id
// Name : id -> hostelRoomTypeId
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomTypeDetail');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");
$hostelRoomTypeDetailManager =  HostelRoomTypeDetailManager::getInstance();

	if(!isset($REQUEST_DATA['roomTypeInfoId']) || trim($REQUEST_DATA['roomTypeInfoId']) == '') {
		$errorMessage = INVALID_HOSTEL_ROOM_DETAIL;
	}
	
	if($errorMessage ==''){
		$dataArray = $hostelRoomTypeDetailManager->checkForRoomTypeDetailMapping($REQUEST_DATA['roomTypeInfoId']);
		if($dataArray[0]['cnt'] > 0){
			$errorMessage = DEPENDENCY_CONSTRAINT;
		}
	}
	
	if(trim($errorMessage) == ''){
		if($hostelRoomTypeDetailManager->deleteHostelRoomTypeDetail($REQUEST_DATA['roomTypeInfoId']) ) {
			echo DELETE;
		}
		else{
			echo DEPENDENCY_CONSTRAINT;
		}
	}
	else{
		echo $errorMessage;
	}
 
// $History: ajaxInitHostelRoomTypeDetailDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//
//
?>
