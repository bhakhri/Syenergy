<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	
    require_once(MODEL_PATH . "/ExtendVehicleServiceManager.inc.php");
    $extendVehicleServiceManager = ExtendVehicleServiceManager::getInstance();
	$busId = $REQUEST_DATA['busId'];
	if ($busId != '') {
		$vehicleServiceArray = $extendVehicleServiceManager->getVehicleFreeService($busId);
		if(count($vehicleServiceArray) > 0 && is_array($vehicleServiceArray)) {
			echo json_encode($vehicleServiceArray);
		}
		else {
			echo 0;
		}
	}

// $History: getVehicleFreeService.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:52p
//Created in $/Leap/Source/Library/ExtendVehicleService
//new files to add extend vehicle service
//
//
?>