<?php
//-------------------------------------------------------
// Purpose: To delete Insurance Vehicle detail
//
// Author : Jaineesh
// Created on : (03.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['accidentId']) || trim($REQUEST_DATA['accidentId']) == '') {
        $errorMessage = INVALID_ACCIDENT;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleAccidentManager.inc.php");
		$vehicleAccidentManager = VehicleAccidentManager::getInstance();
		$vehicleAccidentArray = $vehicleAccidentManager->getVehicleAccidentDetail($REQUEST_DATA['accidentId']);
		if ($vehicleAccidentArray[0]['totalRecords'] == 0) {
			echo DISCARD_VEHICLE_NOT_DELETE;
			die;
		}
		if(is_array($vehicleAccidentArray) && $vehicleAccidentArray[0]['totalRecords'] > 0 ) {
			if($vehicleAccidentManager->deleteVehicleAccident($REQUEST_DATA['accidentId'])) {
				echo DELETE;
			}
			else {
				echo DEPENDENCY_CONSTRAINT;
			}
		}
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/VehicleAccident
//fixed bug in fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:03p
//Created in $/Leap/Source/Library/VehicleAccident
//new ajax files for add, edit & delete for vehicle accident
// 
//
?>