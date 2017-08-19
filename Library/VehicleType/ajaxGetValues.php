<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE VEHICLE TYPE LIST
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['vehicleTypeId'] ) != '') {
    require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
	$foundVehicleTypeArray = VehicleTypeManager::getInstance()->checkVehicleType('AND vt.vehicleTypeId="'.$REQUEST_DATA['vehicleTypeId'].'"');
	//print_r($foundVehicleTypeArray);
	if ($foundVehicleTypeArray[0]['totalRecords'] > 0) {
		echo DEPENDENCY_CONSTRAINT_EDIT;
		die;
	}
    $foundArray = VehicleTypeManager::getInstance()->getVehicleType(' WHERE vehicleTypeId="'.$REQUEST_DATA['vehicleTypeId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Library/VehicleType
//fixed bug in Fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
?>