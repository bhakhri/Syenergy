<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TyreRetreading');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$busId = $REQUEST_DATA['busId'];
	$typeId = $REQUEST_DATA['tyreTypeId'];
	if($typeId == 1) {
		$typeId = 0;
	}
	
    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();
	if ($typeId == 0) {
		$vehicleTypeArray = $vehicleTyreManager->getVehicleSpareTyres($busId,$typeId);
		if(count($vehicleTypeArray) > 0 && is_array($vehicleTypeArray)) {
			echo json_encode($vehicleTypeArray);
		}
		else {
			echo 0;
		}
	}

// $History: getVehicleSpareTyres.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:41p
//Created in $/Leap/Source/Library/VehicleTyre
//new files for vehicle tyre
//
//
?>