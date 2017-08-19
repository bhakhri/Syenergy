<?php
//-------------------------------------------------------
// Purpose: To delete TransportStuff detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleBattery');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['batteryId']) || trim($REQUEST_DATA['batteryId']) == '') {
        $errorMessage = 'Invalid Battery';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
        $vehicleBatteryManager =  VehicleBatteryManager::getInstance();
			echo BATTERY_NOT_DELETE;
        /*if($vehicleBatteryManager->deleteTransportStaff($REQUEST_DATA['staffId']) ) {
              echo DELETE;
        }
        else {
               echo DEPENDENCY_CONSTRAINT;
        }*/
     }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Library/VehicleBattery
//new ajax files for vehicle battery
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Library/TransportStaff
//add new fields and upload image
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>

