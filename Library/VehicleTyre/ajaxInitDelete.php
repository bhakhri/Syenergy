<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Jaineesh
// Created on : (25.11.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTyreMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['tyreId']) || trim($REQUEST_DATA['tyreId']) == '') {
        $errorMessage = INVALID_VEHICLE_TYRE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
		$vehicleTyreManager = VehicleTyreManager::getInstance();
			
            if ($vehicleTyreManager->deleteVehicleTyre($REQUEST_DATA['tyreId'])) {
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
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/04/09   Time: 6:56p
//Updated in $/Leap/Source/Library/VehicleTyre
//changes for vehicle tyre
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
?>