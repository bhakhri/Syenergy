<?php
//-------------------------------------------------------
//  This File is used for fetching Stop Names
// Author :Nishu Bindal
// Created on : 29-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelRoomCourse');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$hostelId = $REQUEST_DATA['hostelId'];
	
    	require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
	if($hostelId != '' ){
		$roomTypeArray = HostelRoomManager::getInstance()->fetchRoomTypes($hostelId);
	
		if(count($roomTypeArray) > 0 && is_array($roomTypeArray)) {
			echo json_encode($roomTypeArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
