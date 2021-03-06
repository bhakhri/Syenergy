<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit A BUSSTOP Route Mapping
// Author : Nishu Bindal
// Created on : (29.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleStopRouteMapping');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/BusStopRouteMappingManager.inc.php");
$busStopRouteMapping = BusStopRouteMappingManager::getInstance();
     
    $errorMessage ='';
    if($errorMessage == '' && (!isset($REQUEST_DATA['cityId']) || trim($REQUEST_DATA['cityId']) == '')) {
        $errorMessage .= "Select Vehicle Stop City. \n";    
    }
    if (!isset($REQUEST_DATA['stopId']) || trim($REQUEST_DATA['stopId']) == '') {
        $errorMessage .= "Select Vehicle Stop Name. \n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['routeCode']) || trim($REQUEST_DATA['routeCode']) == '')) {
        $errorMessage .= "Select Vehicle Route.\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['scheduleTime']) || trim($REQUEST_DATA['scheduleTime']) == '')) {
        $errorMessage .= "Enter Schedule Time\n";    
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['busRouteStopMappingId']) || trim($REQUEST_DATA['busRouteStopMappingId']) == '')) {
        $errorMessage .= "Required Parameter is Missing.\n";    
    }    
   
   	if($errorMessage == ''){
   		$condition = "WHERE busStopId = '".$REQUEST_DATA['stopId']."' AND busRouteId = '".$REQUEST_DATA['routeCode']."' AND busRouteStopMappingId Not In ('".$REQUEST_DATA['busRouteStopMappingId']."')";
   		
        	$foundArray =$busStopRouteMapping->checkForAlreadyMapped($condition);
        	if($foundArray[0]['totalRecord'] > 0) {  //DUPLICATE CHECK
        		$errorMessage = "Vehicle Stop Name is Already Mapped With  Vehicle Route.\n";
        	}
        	
        	$condition1 = "WHERE  busRouteId = '".$REQUEST_DATA['routeCode']."' AND scheduledTime = '".$REQUEST_DATA['scheduleTime']."' AND busRouteStopMappingId Not In ('".$REQUEST_DATA['busRouteStopMappingId']."')";
        	$foundArray =$busStopRouteMapping->checkForAlreadyMapped($condition1);
        	if($foundArray[0]['totalRecord'] > 0) {  //DUPLICATE CHECK
        		$errorMessage .= "Scheduled Arival Time for can't be same for same Vehicle Route.\n";
        	}
        }
      
    
    if (trim($errorMessage) == '') {
    	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$returnStatus = $busStopRouteMapping->editBusStopRouteMapping($REQUEST_DATA['busRouteStopMappingId'],$REQUEST_DATA['stopId'],$REQUEST_DATA['routeCode'],$REQUEST_DATA['scheduleTime']);
		if($returnStatus === false){
			echo FAILURE;
			die;
		}
		
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
			die;
		}
		else {
			echo FAILURE;
			die;
		}
		
	}
	else {
		echo FAILURE;
		die;
	}
    }
    else {
        echo $errorMessage;
    }

?>
