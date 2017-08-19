<?php
//-------------------------------------------------------
//  This File is used for fetching Bus Stop City
// Author :Nishu Bindal
// Created on : 6-Feb-2012
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
  require_once(MODEL_PATH . "/Fee/BusFeeManager.inc.php");
    $BusFeeManager = BusFeeManager::getInstance();
	$routeId = trim($REQUEST_DATA['selectedRouteId']);
	if($routeId == ''){
		echo  "Required Parameter is Missing !!";
	}
	else{	
		$cityArray = $BusFeeManager->fetchBusStopCity($routeId);
		if(count($cityArray) > 0 && is_array($cityArray)) {
			echo json_encode($cityArray);
		}
		else {
			echo 0;
		}
	}
	
?>
