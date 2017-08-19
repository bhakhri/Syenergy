<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSSTOP
//
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
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	$errorMessage ='';
    if (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '') {
        $errorMessage .= SELECT_BUS."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['batteryNo']) || trim($REQUEST_DATA['batteryNo']) == '')) {
        $errorMessage .= ENTER_ENTER_BATTERY_NO."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['batteryMake']) || trim($REQUEST_DATA['batteryMake']) == '')) {
        $errorMessage .= ENTER_BATTERY_MAKE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['warrantyDate']) || trim($REQUEST_DATA['warrantyDate']) == '')) {
        $errorMessage .= ENTER_WARRANTY_DATE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['meterReading']) || trim($REQUEST_DATA['meterReading']) == '')) {
        $errorMessage .= ENTER_METER_READING."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['replacementCost']) || trim($REQUEST_DATA['replacementCost']) == '')) {
        $errorMessage .= ENTER_REPLACEMENT_COST."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['replacementDate']) || trim($REQUEST_DATA['replacementDate']) == '')) {
        $errorMessage .= ENTER_REPLACEMENT_DATE."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
        //$foundArray = TransportStaffManager::getInstance()->getTransportStaff(' WHERE UCASE(staffCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['staffCode']))).'" AND staffId!='.$REQUEST_DATA['staffId']);
		$foundArray = VehicleBatteryManager::getInstance()->getVehicleBattery(' AND UCASE(batteryNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['batteryNo']))).'" AND batteryId !='.$REQUEST_DATA['batteryId']);
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if(trim($foundArray[0]['batteryNo'])=='') {  //DUPLICATE CHECK
            $returnStatus = VehicleBatteryManager::getInstance()->editVehicleBattery($REQUEST_DATA['batteryId']);
            if($returnStatus === false) {
                echo FAILURE;
            }
        }
        else {
            echo VEHICLE_BATTERY_ALREADY_EXIST;
			//$sessionHandler->setSessionVariable('DUPLICATE_USER',STAFF_CODE_ALREADY_EXIST);
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
// $History: ajaxInitEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Library/VehicleBattery
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/VehicleBattery
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Library/VehicleBattery
//new ajax files for vehicle battery
//
//
?>