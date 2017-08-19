<?php
//-------------------------------------------------------
//  This File is used for fetching Bus Stop City
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
   require_once(MODEL_PATH . "/BusStopRouteMappingManager.inc.php");
    $busStopRouteMapping = BusStopRouteMappingManager::getInstance();

		
	$cityArray = $busStopRouteMapping->fetchBusStopCity('');
	
	if(count($cityArray) > 0 && is_array($cityArray)) {
		echo json_encode($cityArray);
	}
	else {
		echo 0;
	}
	
?>
