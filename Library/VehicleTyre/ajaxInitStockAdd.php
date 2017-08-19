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
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stockTyreNo']) || trim($REQUEST_DATA['stockTyreNo']) == '')) {
        $errorMessage .= SELECT_STOCK_TYRE_NUMBER. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stockVehicleReading']) || trim($REQUEST_DATA['stockVehicleReading']) == '')) {
        $errorMessage .= ENTER_STOCK_VEHICLE_READING. '<br/>';
    }
	
	$stockTyreNo = $REQUEST_DATA['stockTyreNo'];
	/*$stockRegnNo = $REQUEST_DATA['stockRegnNo'];
	$stockVehicleReading = $REQUEST_DATA['stockVehicleReading'];
	$replacementDate = $REQUEST_DATA['replacementDate'];
	$replacementReason = $REQUEST_DATA['replacementReason'];
	$addStockTyreId = $REQUEST_DATA['addStockTyreId'];
	$addStockTyreType = $REQUEST_DATA['addStockTyreType'];*/
	
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
		$vehicleTyreManager = VehicleTyreManager::getInstance();
		
		if(trim($stockTyreNo) != '') {  //DUPLICATE CHECK
			 if(SystemDatabaseManager::getInstance()->startTransaction()) {
			 
				$r1 = $vehicleTyreManager->updateVehicleTyre();

				if($r1===false) {
					echo FAILURE;
					die;
				}

				$r2 = $vehicleTyreManager->updateStockVehicleTyre();

				if($r2===false) {
					echo FAILURE;
					die;
				}

				$r3 = $vehicleTyreManager->updateVehicleTyreHistory();
				if($r3===false){
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

// $History: ajaxInitStockAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/08/10    Time: 4:32p
//Created in $/Leap/Source/Library/VehicleTyre
//new file for stock add tyre
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