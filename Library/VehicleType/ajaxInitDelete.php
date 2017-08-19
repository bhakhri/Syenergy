<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
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
    if (!isset($REQUEST_DATA['vehicleTypeId']) || trim($REQUEST_DATA['vehicleTypeId']) == '') {
        $errorMessage = INVALID_VEHICLE_TYPE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
		$vehicleTypeManager = VehicleTypeManager::getInstance();
		$foundVehicleTypeArray = $vehicleTypeManager->checkVehicleType('AND vt.vehicleTypeId="'.$REQUEST_DATA['vehicleTypeId'].'"');
	//print_r($foundVehicleTypeArray);
			if ($foundVehicleTypeArray[0]['totalRecords'] > 0) {
				echo DEPENDENCY_CONSTRAINT;
				die;
			}
			
            if($vehicleTypeManager->deleteVehicleType($REQUEST_DATA['vehicleTypeId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
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

