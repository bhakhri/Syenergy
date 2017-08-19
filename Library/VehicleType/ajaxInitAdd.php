<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW DESIGNATION
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['vehicleType']) || trim($REQUEST_DATA['vehicleType']) == '')) {
        $errorMessage .= ENTER_VEHICLE_TYPE. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mainTyres']) || trim($REQUEST_DATA['mainTyres']) == '')) {
        $errorMessage .= ENTER_MAIN_TYRE. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['spareTyres']) || trim($REQUEST_DATA['spareTyres']) == '')) {
        $errorMessage .= ENTER_SPARE_TYRE. '<br/>';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
		$vehicleTypeManager = VehicleTypeManager::getInstance();
		$foundArray = $vehicleTypeManager->getVehicleType(' WHERE LCASE(vehicleType)= "'.add_slashes(trim(strtolower($REQUEST_DATA['vehicleType']))).'"');
		
			if(trim($foundArray[0]['vehicleType'])=='') {  //DUPLICATE CHECK
				$returnStatus = $vehicleTypeManager->addVehicleType();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				if(trim(strtoupper($foundArray[0]['vehicleType']))==trim(strtoupper($REQUEST_DATA['vehicleType']))) {
				 echo VEHICLE_TYPE_ALREADY_EXIST;
				 die;
			   }
		  }
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
?>