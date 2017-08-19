<?php
//--------------------------------------------------------------------------------------------
//Purpose: This file stores the list of vehicle whose 'insuranceDueDate' is less than 10 days.
//Author: Kavish Manjkhola
//Created On: 11/04/2011
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceVehicleAutopay');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
$insuranceVehicleManager = InsuranceVehicleManager::getInstance();

if (isset($REQUEST_DATA['vehicleId']) || trim($REQUEST_DATA['vehicleId']) != '') {
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$vehicleId = $REQUEST_DATA['vehicleId'];
		$paidOn = explode('-',$REQUEST_DATA['insurancePaidOn']);
		$insurancePaidOn = $paidOn[2].'-'.$paidOn[1].'-'.$paidOn[0];
		$dueDate = explode('-',$REQUEST_DATA['insuranceDueDate']);
		$insuranceDueDate = $dueDate[2].'-'.$dueDate[1].'-'.$dueDate[0];
		$ctr = 0;

		//Array to store the vehicle details based on vehicleId
		$getVehicleInsuranceDueDate = $insuranceVehicleManager->getVehicleInsuranceDueDate($vehicleId);

		//To update the vehicle Insurance details in 'bus_insurance' table
		$returnStatus = $insuranceVehicleManager->updateVehicleInsuranceDetails($vehicleId, $insuranceDueDate, $insurancePaidOn);
		if($returnStatus = false) {
			echo FAILURE;
			die;
		}

		//To check no of insurance pending.
		$insurancePendingList = $insuranceVehicleManager->getInsurancePendingList();
		$totalRecords = $insurancePendingList[0]['totalRecords'];
		if($totalRecords == 1) {
			//To update the 'viewStatus' in 'Notifications' table.
			$return = $insuranceVehicleManager->updateViewStatus();
			if($return == false) {
				echo VIEWSTATUS_CANNOT_BE_UPDATED;
			}
		}

		//To Update the status of Vehicle Insurance.
		$returnValue = $insuranceVehicleManager->updateVehicleInsuranceStatus($vehicleId, $insuranceDueDate, $insurancePaidOn);
		if($returnValue = false) {
			echo FAILURE;
			die;
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
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
else{
	echo FAILURE;
	die;
}
?>