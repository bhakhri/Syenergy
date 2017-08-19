<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE VEHICLE TYRE LIST
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleBattery');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['batteryId'] ) != '') {
    require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
	$vehicleBatteryManager = VehicleBatteryManager::getInstance();
    $foundArray = $vehicleBatteryManager->getVehicleBattery(' AND batteryId="'.$REQUEST_DATA['batteryId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			if($foundArray[0]['replacementCost'] == '0.00' && $foundArray[0]['replacementDate'] == '0000-00-00' && $foundArray[0]['meterReading'] == '0') {
				echo BATTERY_NOT_EDIT;
				die;
			}
			else {
				echo json_encode($foundArray[0]);
			}
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Library/VehicleBattery
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Library/VehicleBattery
//new ajax files for vehicle battery
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Library/VehicleTyre
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:31p
//Created in $/Leap/Source/Library/VehicleTyre
//new ajax files for vehicle tyre
//
//
?>