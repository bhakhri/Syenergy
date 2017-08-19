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
	define('MODULE','VehicleTyreMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	
    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();

		$vehicleStockArray = $vehicleTyreManager->getVehicleExtraTyres();
		if(count($vehicleStockArray) > 0 && is_array($vehicleStockArray)) {
			echo json_encode($vehicleStockArray);
		}
		else {
			echo 0;
		}


// $History: getStockTyres.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/08/10    Time: 5:11p
//Created in $/Leap/Source/Library/VehicleTyre
//new ajax file to get stock tyres
//
//
?>