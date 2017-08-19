<?php
//-------------------------------------------------------
//  This File is used for fetching Root Names
// Author :Nishu Bindal
// Created on : 28-Feb-2012
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
	
    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
    
    $stopId = $REQUEST_DATA['stopId'];
    $classId = $REQUEST_DATA['classId'];
   
    if($stopId=='') {
      $stopId ='0'; 
    }
   
    if($classId=='') {
      $classId ='0'; 
    }
    
    $condition = '';
    $routeArray = $VehicleRouteAllocationManager->fetchRootNamesWithCharges($stopId,$classId);
	if(count($routeArray) > 0 && is_array($routeArray)) {
	  echo json_encode($routeArray);
	}
	else {
	  echo 0;
	}
	
?>
