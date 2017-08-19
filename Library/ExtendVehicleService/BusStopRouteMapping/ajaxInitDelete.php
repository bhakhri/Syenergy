<?php
//-------------------------------------------------------
// Purpose: To delete busstop detail
//
// Author :Nishu Bindal
// Created on : (28.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleStopRouteMapping');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/BusStopRouteMappingManager.inc.php");
$busStopRouteMapping = BusStopRouteMappingManager::getInstance();
     

    if (!isset($REQUEST_DATA['busRouteStopMappingId']) || trim($REQUEST_DATA['busRouteStopMappingId']) == '') {
        $errorMessage = 'Invalid BusStop';
    }
    else{
    	 $recordArr = $busStopRouteMapping->checkForBusStopRouteMapping($REQUEST_DATA['busRouteStopMappingId']);
    	 if($recordArr[0]['totalRecord'] > 0 ){
    	 	$errorMessage = DEPENDENCY_CONSTRAINT;
    	 }
    }
    
    if (trim($errorMessage) == '') { 
	if($busStopRouteMapping->deleteBusStopRouteMapping($REQUEST_DATA['busRouteStopMappingId']) ) {
		echo DELETE;
	}
    }
    else {
        echo $errorMessage;
    }

?>

