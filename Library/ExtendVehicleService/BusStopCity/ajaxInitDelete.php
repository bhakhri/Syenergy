<?php
//-------------------------------------------------------
// Purpose: To delete FEE HEAD 
// Author : Nishu Bindal
// Created on : (23.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCityMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['busStopCityId']) || trim($REQUEST_DATA['busStopCityId']) == '') {
        $errorMessage = 'Required Parameter is Missing !!';
    }
    	$busStopCityId = $REQUEST_DATA['busStopCityId'];
    
	require_once(MODEL_PATH . "/BusStopCityManager.inc.php");
	$busStopCityManager = BusStopCityManager::getInstance();
        
        $recordArray = $busStopCityManager->checkInBusStop($busStopCityId);
        if(count($recordArray) > 0) {
        	 $errorMessage .=  DEPENDENCY_CONSTRAINT."\n";
         }
         else{
		$recordArray = $busStopCityManager->checkInBusFees($busStopCityId);
		if(count($recordArray) > 0 ) {
			 $errorMessage .=  DEPENDENCY_CONSTRAINT."\n";
		 }
         }
         
	if(trim($errorMessage) == '') {
		if($busStopCityManager->deleteBusStopCity($busStopCityId) ) {
			echo DELETE;
		}
	}
	else {
		echo $errorMessage;
	}
?>

