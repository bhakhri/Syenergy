<?php
//-------------------------------------------------------
// Purpose: To delete room detail
// Author : Nishu bindal
// Created on : (28.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
	if($REQUEST_DATA['busRouteStudentMappingId'] == ''){
		$errorMessage = "Required Parameter is Missing !!";
	}
	
if($REQUEST_DATA['employeeId'] == ''){
		$errorMessage = "Required Parameter is Missing !!";
	}
	
	if($errorMessage == ''){
		  require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    		$VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
 //$ret=$VehicleRouteAllocationManager->checkStudentData(' WHERE busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);
 $ret=$VehicleRouteAllocationManager->checkEmployeeData(' WHERE busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);
       
		//$foundArray=$VehicleRouteAllocationManager->getStudentVehicleRouteData(' AND brsm.busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);

$foundArray=$VehicleRouteAllocationManager->getEmployeeVehicleRouteData(' AND brsm.busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId'].' AND brsm.employeeId ='.$REQUEST_DATA['employeeId']);		
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
	}
	else{
		echo $errorMessage;
	}

?>
