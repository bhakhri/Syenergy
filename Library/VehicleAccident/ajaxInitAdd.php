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
define('MODULE','VehicleAccident');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= SELECT_BUS. '<br/>';
    }
    
    if (trim($errorMessage) == '') {
		$busNo = $REQUEST_DATA['busNo'];
		$transportStaff = $REQUEST_DATA['transportStaff'];
		$busRoute = $REQUEST_DATA['busRoute'];
		$accidentDate = $REQUEST_DATA['accidentDate'];

		if ($busNo == '') {
			echo SELECT_BUS;
			die;
		}

		if ($transportStaff == '') {
			echo SELECT_TRANSPORT_STAFF;
			die;
		}

		if ($busRoute == '') {
			echo SELECT_ROUTE;
			die;
		}

		if ($accidentDate == '' || $accidentDate == '0000-00-00') {
			echo SELECT_ACCIDENT_DATE;
			die;
		}
        require_once(MODEL_PATH . "/VehicleAccidentManager.inc.php");
		$vehicleAccidentManager = VehicleAccidentManager::getInstance();

		//$foundArray = $vehicleAccidentManager->getVehicleInsurance(' WHERE LCASE(busNo)= "'.add_slashes(trim(strtolower($REQUEST_DATA['busNo']))).'"');
		
			if($busNo!='') {  //DUPLICATE CHECK
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$returnStatus = $vehicleAccidentManager->addVehicleAccident();
					if($returnStatus === false) {
						echo FAILURE;
						die;
					}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
						echo SUCCESS;
						die;
					 }
					 else {
						echo FAILURE;
					}
				}
				else{
				  echo FAILURE;
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
//User: Jaineesh     Date: 12/04/09   Time: 1:03p
//Created in $/Leap/Source/Library/VehicleAccident
//new ajax files for add, edit & delete for vehicle accident
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
?>