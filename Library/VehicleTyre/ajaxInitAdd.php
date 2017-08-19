<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW VEHICLE TYRE
// Author : Jaineesh
// Created on : (25.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTyreMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['tyreNumber']) || trim($REQUEST_DATA['tyreNumber']) == '')) {
        $errorMessage .= ENTER_TYRE_NUMBER. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['manufacturer']) || trim($REQUEST_DATA['manufacturer']) == '')) {
        $errorMessage .= ENTER_MANUFACTURER. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['modelNumber']) || trim($REQUEST_DATA['modelNumber']) == '')) {
        $errorMessage .= ENTER_MODEL_NUMBER. '<br/>';
    }
	/*if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= ENTER_BUS_NUMBER. '<br/>';
    }*/
	if ($errorMessage == '' && (!isset($REQUEST_DATA['busReading']) || trim($REQUEST_DATA['busReading']) == '')) {
        $errorMessage .= ENTER_BUS_READING. '<br/>';
    }
    
	$usedAsMainTyre = $REQUEST_DATA['usedAsMainTyre'];
	$addTyreId = $REQUEST_DATA['addTyreId'];
	//print_r($REQUEST_DATA);
	//die;
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
		$vehicleTyreManager = VehicleTyreManager::getInstance();
		
		/*$foundTyreHistoryArray = $vehicleTyreManager->getVehicleTyreHistory(' AND b.busId = "'.add_slashes(trim(strtolower($REQUEST_DATA['busNo']))).'"');
		
		$foundTyreTypeArray = $vehicleTyreManager->getVehicleTyreType(' AND b.busId = "'.add_slashes(trim(strtolower($REQUEST_DATA['busNo']))).'"');
		
		if($foundTyreHistoryArray[0]['totalTyres'] == $foundTyreTypeArray[0]['totalVehicleTypeTyre']) {
			echo DELETE_TYRE;
			die;
		}*/

		$foundArray = $vehicleTyreManager->getCheckVehicleTyre(' AND LCASE(tyreNumber)= "'.add_slashes(trim(strtolower($REQUEST_DATA['tyreNumber']))).'"');
			if(trim($foundArray[0]['tyreNumber'])=='') {  //DUPLICATE CHECK
			 if(SystemDatabaseManager::getInstance()->startTransaction()) {
			 
				$r1 = $vehicleTyreManager->addVehicleTyre();
				if($r1===false){
					echo FAILURE;
					die;
				}
				$tyreId=SystemDatabaseManager::getInstance()->lastInsertId();

				$r2 = $vehicleTyreManager->addTyreHistory($tyreId);

				if($r2===false) {
					echo FAILURE;
					die;
				}
				if($usedAsMainTyre == 1) {
					$r3 = $vehicleTyreManager->updateDamageVehicleTyre($addTyreId);
					if($r3===false) {
						echo FAILURE;
						die;
					}
				}

				if($usedAsMainTyre == 0) {
					$r4 = $vehicleTyreManager->updateExtraVehicleTyre($addTyreId);
					if($r4===false) {
						echo FAILURE;
						die;
					}
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
			else {
				if(trim(strtoupper($foundArray[0]['vehicleTyre']))==trim(strtoupper($REQUEST_DATA['vehicleTyre']))) {
				 echo VEHICLE_TYRE_ALREADY_EXIST;
				 die;
			   }
		  }
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug for fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug during self testing
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
//
?>