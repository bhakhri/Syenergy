<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT VEHICLE TYRE
//
//
// Author : Jaineesh
// Created on : (25.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['tyreNumber']) || trim($REQUEST_DATA['tyreNumber']) == '') {
        $errorMessage .= ENTER_TYRE_NUMBER."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['manufacturer']) || trim($REQUEST_DATA['manufacturer']) == '')) {
        $errorMessage .= ENTER_MANUFACTURER."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['modelNumber']) || trim($REQUEST_DATA['modelNumber']) == '')) {
        $errorMessage .= ENTER_MODEL_NUMBER."\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= ENTER_BUS_NUMBER. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['busReading']) || trim($REQUEST_DATA['busReading']) == '')) {
        $errorMessage .= ENTER_BUS_READING. '<br/>';
    }
	//print_r($REQUEST_DATA);
	$spareTyre = $REQUEST_DATA['usedSpareTyre'];
	$mainTyre = $REQUEST_DATA['usedMainTyre'];
	$usedAsMainTyre = $REQUEST_DATA['usedAsMainTyre'];
	if($usedAsMainTyre == '') {
		echo SELECT_TYRE_TYPE;
		die;
	}

	//die('i m here');
	
    if (trim($errorMessage) == '') {
			require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
			$vehicleTyreManager = VehicleTyreManager::getInstance();
			$foundArray1 = $vehicleTyreManager->getVehicleTyre('AND ( UCASE(tm.tyreNumber)="'.add_slashes(trim(strtoupper($REQUEST_DATA['tyreNumber']))).'") AND tm.tyreId!='.$REQUEST_DATA['tyreId']);
            
			if(trim($foundArray1[0]['tyreNumber'])=='') {  //DUPLICATE CHECK
			if(SystemDatabaseManager::getInstance()->startTransaction()) {
            $r1 = $vehicleTyreManager->editVehicleTyre($REQUEST_DATA['tyreId']);
			if($r1 === false) {
                echo FAILURE;
                die;
            }

			$r2 = $vehicleTyreManager->editBus($REQUEST_DATA['tyreId']);
			if($r2 === false) {
                echo FAILURE;
                die;
            }
			if($usedAsMainTyre == '1') {
				if($mainTyre != '') {
					$r3 = $vehicleTyreManager->editSpareTyre($mainTyre);
						if($r3 === false) {
							echo FAILURE;
							die;
						}
				  }
			}
			if($usedAsMainTyre == '0') {
				if($spareTyre != '') {
					$r3 = $vehicleTyreManager->editMainTyre($spareTyre);
						if($r3 === false) {
							echo FAILURE;
							die;
						}	
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
			if(trim($foundArray1[0]['tyreNumber'])==$REQUEST_DATA['tyreNumber']){
             echo VEHICLE_TYRE_ALREADY_EXIST;
             die;
            }
		}
	}
else {
	echo $errorMessage;
}

// $History: ajaxInitEdit.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug for fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:04p
//Updated in $/Leap/Source/Library/VehicleTyre
//fixed bug on fleet management
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
