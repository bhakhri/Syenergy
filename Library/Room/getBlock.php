<?php
//-------------------------------------------------------
//  This File is used for fetching block for 
//
//
// Author :Jaineesh
// Created on : 12.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoomsMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$buildingId = $REQUEST_DATA['buildingId'];
    require_once(MODEL_PATH . "/RoomManager.inc.php");
    $roomManager = RoomManager::getInstance();
	$blockArray = $roomManager->getBlock($buildingId);
	echo json_encode($blockArray);

// $History: getBlock.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Library/Room
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/12/09    Time: 5:25p
//Created in $/LeapCC/Library/Room
//get the block of specific building
//
//
?>