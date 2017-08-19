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
	$vehicleTypeId = $REQUEST_DATA['vehicleTypeId'];
    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();
	if ($vehicleTypeId != '') {
		$vehicleNoArray = $vehicleTyreManager->getVehicleNos($vehicleTypeId);
		if(count($vehicleNoArray) > 0 && is_array($vehicleNoArray)) {
			echo json_encode($vehicleNoArray);
		}
		else {
			echo 0;
		}
	}

// $History: getVehicleNumbers.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:41p
//Created in $/Leap/Source/Library/VehicleTyre
//new files for vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>