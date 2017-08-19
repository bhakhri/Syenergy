<?php
//-------------------------------------------------------
// Purpose: To add in lecture
// Author : Jaineesh
// Created on : (30.03.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','VehicleServiceRepair');
	define('ACCESS','edit');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	$errorMessage ='';
	$serviceRepairId = $REQUEST_DATA['serviceRepairId'];
	$busService = $REQUEST_DATA['busService'];
	$busId = $REQUEST_DATA['busNo'];
	$serviceId = $REQUEST_DATA['serviceNo'];
	$serviceDate = $REQUEST_DATA['serviceDate1'];

	$vehicleRepairCount = count($REQUEST_DATA['repair']);
	$vehicleItemsCount = count($REQUEST_DATA['items']);
	$vehicleChargesCount = count($REQUEST_DATA['charges']);
	
	
    require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
	$vehicleServiceRepairManager = VehicleServiceRepairManager::getInstance();
	
	
	$busRepairTypeArr;
	$repairCount = count($busRepairTypeArr);

	if($serviceRepairId != '') {

		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			/*if($busService == 1) {
				$updateVehicleService =	$vehicleServiceRepairManager->updateVehicleService($busId,$serviceId);
				if($updateVehicleService === false) {
					$errorMessage = FAILURE;
				}
			}*/
			$updateVehicleServiceRepair = $vehicleServiceRepairManager->updateVehicleServiceRepair();
			if($updateVehicleServiceRepair === false) {
				$errorMessage = FAILURE;
			}

			$x=1;

			$deleteVehicleServiceRepair = $vehicleServiceRepairManager->deleteVehicleServiceDetail();
			if($deleteVehicleServiceRepair === false) {
				$errorMessage = FAILURE;
			}
	
			while ($x <= $repairCount) {
				$serviceAmount = 'amount_'.$x;
				$amount = $REQUEST_DATA[$serviceAmount];
				if($amount != '') {
					if(!is_numeric($amount) or ($amount < 1) or ($amount == 0)) {
						echo INVALID_AMOUNT. ' at Sr.No. '. $x . ' in Service Details';
						die;
					}
				}
				$serviceAmountArray = explode('_',$serviceAmount);
				$actionId = $serviceAmountArray[1];
				$vehicleKMRun = 'kmRun_'.$x;
				$kmRun = $REQUEST_DATA[$vehicleKMRun];
				if($kmRun != '') {
					if(!is_numeric($kmRun) or ($kmRun <1)) {
						echo INVALID_KM_RUN. ' at Sr.No. '.$x. ' in Service Details';
						die;
					}
				}
				$vehicleKMChangeRun = 'kmChangeRun_'.$x;
				$kmChangeRun = $REQUEST_DATA[$vehicleKMChangeRun];

				if($kmChangeRun != '') {
					if(!is_numeric($kmChangeRun) or ($kmChangeRun <1)) {
						echo INVALID_NEXT_CHANGE_KM. ' at Sr.No. '.$x. ' in Service Details';
						die;
					}
				}
	
				if($amount != '' OR $kmRun != '' OR $kmChangeRun != '') {
					$insertVehicleServiceRepair = $vehicleServiceRepairManager->insertVehicleServiceOil($serviceRepairId,$actionId,$amount,$kmRun,$kmChangeRun);
					if($insertVehicleServiceRepair === false) {
						$errorMessage = FAILURE;
					}
				}
				$x++;
			}

			for($j=0;$j<$vehicleItemsCount;$j++) {
				if(trim($REQUEST_DATA['items'][$j]) == '' && trim($REQUEST_DATA['charges'][$j]) == '') {
					echo "Please fill the value of empty box"; 
					die;
				}

				if($vehicleItemsCount > 0 ) {
					if(is_numeric($REQUEST_DATA['items'][$j])) {
						echo INVALID_ITEMS.' in Repair Details';
						die;
					}
				}

				if($vehicleChargesCount > 0 ) {
					if(!is_numeric($REQUEST_DATA['charges'][$j]) or ($REQUEST_DATA['charges'][$j] < 1) or ($REQUEST_DATA['charges'][$j] == 0)) {
						echo INVALID_CHARGES.' in Repair Details';
						die;
					}
				}
			}


//	die('line'.__LINE__);

			$deleteVehicleRepair = $vehicleServiceRepairManager->deleteVehicleRepairDetail();
			if($deleteVehicleRepair === false) {
				$errorMessage = FAILURE;
			}

			if($vehicleItemsCount > 0 ) {
				for($j = 0; $j < $vehicleItemsCount; $j++) {
					$vehicleRepair = $REQUEST_DATA['repair'][$j];
					$vehicleItems = addslashes($REQUEST_DATA['items'][$j]);
					$vehicleAmount = addslashes($REQUEST_DATA['charges'][$j]);
					if(!empty($str)) {
						$str .= ',';
					}
					$str .= "($serviceRepairId, '$vehicleRepair', '$vehicleItems', '$vehicleAmount')";
				}
				$insertVehicleServiceRepairDetail = $vehicleServiceRepairManager->insertVehicleServiceRepairDetail($str);
				if($insertVehicleServiceRepairDetail === false) {
					$errorMessage = FAILURE;
				}
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
		echo INVALID_REPAIR_ID;
		die;
	}

// $History: $
//
//
?>