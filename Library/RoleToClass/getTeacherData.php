<?php
//-------------------------------------------------------
//  This File is used for fetching Teacher for 
//
//
// Author :Jaineesh
// Created on : 30.09.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoleToClass');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoleToClassManager.inc.php");
    $roletoclassManager = RoleToClassManager::getInstance();
	$roleId = $REQUEST_DATA['roleId'];
	$blockArray = $roletoclassManager->getTeacherData(" WHERE roleId = ".$roleId);
	echo json_encode($blockArray);

// $History: getTeacherData.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/20/09   Time: 6:18p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug if employee  has not managed the user
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:03p
//Created in $/LeapCC/Library/RoleToClass
//new ajax files for add, edit, list
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