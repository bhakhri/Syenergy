<?php
//-------------------------------------------------------
//  This File is used for fetching Stop Names
// Author :Nishu Bindal
// Created on : 28-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$cityId = $REQUEST_DATA['cityId'];
	$condition = '';
    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
	if($cityId != '' ){
		$stopArray = $VehicleRouteAllocationManager->fetchStopNames($cityId);
		
		if(count($stopArray) > 0 && is_array($stopArray)) {
			echo json_encode($stopArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
