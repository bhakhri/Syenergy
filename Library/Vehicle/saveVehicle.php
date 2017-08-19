<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

$mode =  $REQUEST_DATA['mode'];


if ($mode === 'add' or $mode === 'edit' or $mode === 'delete') {
	define('MODULE','Vehicle');
	define('ACCESS', $mode);
}

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$busId						=		add_slashes(trim($REQUEST_DATA['busId']));



//echo $busId."nn";die
$saveStr = Array();
if ($mode === 'add' or $mode === 'edit') {
	//echo UtilityManager::includeJS("combinedJS.php",'',1);
	$vehicleTypeId				=		add_slashes(trim($REQUEST_DATA['vehicleType']));
	if ($mode === 'add') {
		$saveStr[] = " vehicleTypeId = '$vehicleTypeId'";
	}
	$busName					=		add_slashes(trim($REQUEST_DATA['busName']));
	$busNo1						=		add_slashes(trim($REQUEST_DATA['busNo1']));
	$busNo2						=		add_slashes(trim($REQUEST_DATA['busNo2']));
	$busNo3						=		add_slashes(trim($REQUEST_DATA['busNo3']));
	$busNo4						=		add_slashes(trim($REQUEST_DATA['busNo4']));
	$busModel					=		add_slashes(trim($REQUEST_DATA['busModel']));
	$purchaseDate				=		add_slashes(trim($REQUEST_DATA['purchaseDate']));
	$seatingCapacity			=		add_slashes(trim($REQUEST_DATA['seatingCapacity']));
	$fuelCapacity				=		add_slashes(trim($REQUEST_DATA['fuelCapacity']));
	$manYear					=		add_slashes(trim($REQUEST_DATA['manYear']));
	$engineNo					=		add_slashes(trim($REQUEST_DATA['engineNo']));
	$chasisNo					=		add_slashes(trim($REQUEST_DATA['chasisNo']));
	$bodyMaker					=		add_slashes(trim($REQUEST_DATA['bodyMaker']));
	$chasisCost					=		add_slashes(trim($REQUEST_DATA['chasisCost']));
	$chasisPurchaseDate			=		add_slashes(trim($REQUEST_DATA['chasisPurchaseDate']));
	$bodyCost					=		add_slashes(trim($REQUEST_DATA['bodyCost']));
	$putOnRoadDate				=		add_slashes(trim($REQUEST_DATA['putOnRoadDate']));
	$insuranceDate				=		add_slashes(trim($REQUEST_DATA['insuranceDate']));
	$insuranceDueDate			=		add_slashes(trim($REQUEST_DATA['insuranceDueDate']));
	$insuringCompanyId			=		add_slashes(trim($REQUEST_DATA['insuringCompany']));
	$policyNo					=		add_slashes(trim($REQUEST_DATA['policyNo']));
	$valueInsured				=		add_slashes(trim($REQUEST_DATA['valueInsured']));
	$insurancePremium			=		add_slashes(trim($REQUEST_DATA['insurancePremium']));
	$ncb						=		add_slashes(trim($REQUEST_DATA['ncb']));
	$paymentMode				=		add_slashes(trim($REQUEST_DATA['paymentMode']));
	$branchName					=		add_slashes(trim($REQUEST_DATA['branchName']));
	$agentName					=		add_slashes(trim($REQUEST_DATA['agentName']));
	$paymentDescription			=		add_slashes(trim($REQUEST_DATA['paymentDescription']));
	$tyreModelNo				=		add_slashes(trim($REQUEST_DATA['tyreModelNo']));
	$tyreManufacturingCompany	=		add_slashes(trim($REQUEST_DATA['tyreManufacturingCompany']));
	$regnNoValidTill			=		add_slashes(trim($REQUEST_DATA['regnNoValidTill']));
	$passengerTaxValidTill		=		add_slashes(trim($REQUEST_DATA['passengerTaxValidTill']));
	$roadTaxValidTill			=		add_slashes(trim($REQUEST_DATA['roadTaxValidTill']));
	$pollutionTaxValidTill		=		add_slashes(trim($REQUEST_DATA['pollutionTaxValidTill']));
	$passingValidTill			=		add_slashes(trim($REQUEST_DATA['passingValidTill']));
	$batteryNo					=		add_slashes(trim($REQUEST_DATA['batteryNo']));
	$batteryMake				=		add_slashes(trim($REQUEST_DATA['batteryMake']));
	$warrantyDate				=		add_slashes(trim($REQUEST_DATA['warrantyDate']));
	$freeService				=		add_slashes(trim($REQUEST_DATA['freeService']));
	$maxFileUpload				=		add_slashes(trim($REQUEST_DATA['maxFileUpload']));
    $vehicleCategory            =        add_slashes(trim($REQUEST_DATA['vehicleCategory']));  
    
	
	if($busNo3 != '') {
		$busNo					=		$busNo1.' '.$busNo2.' '.$busNo3.' '.$busNo4;
	}
	else {
		$busNo					=		$busNo1.' '.$busNo2.' '.$busNo4;
	}
		
	$saveStr[] = " busName = '$busName'";
	$saveStr[] = " busNo = '$busNo'";
	$saveStr[] = " engineNo = '$engineNo'";
	$saveStr[] = " chasisNo = '$chasisNo'";
	$saveStr[] = " yearOfManufacturing = '$manYear'";
	$saveStr[] = " isActive = '1'";
	$saveStr[] = " purchaseDate = '$purchaseDate'";
	$saveStr[] = " modelNumber = '$busModel'";
	$saveStr[] = " seatingCapacity = '$seatingCapacity'";
	$saveStr[] = " fuelCapacity = '$fuelCapacity'";
	$saveStr[] = " bodyMaker = '$bodyMaker'";
	$saveStr[] = " chasisCost = '$chasisCost'";
	$saveStr[] = " chasisPurchaseDate = '$chasisPurchaseDate'";
	$saveStr[] = " bodyCost = '$bodyCost'";
	$saveStr[] = " putOnRoad = '$putOnRoadDate'";
    $saveStr[] = " vechicleCategoryId = '$vehicleCategory'";
}


require_once(MODEL_PATH . "/VehicleManager.inc.php");
$vehicleManager = VehicleManager::getInstance();
//die('i m here');
if ($mode === 'add' or $mode === 'edit' ) {
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {

		$conditions = '';
		if ($mode === 'add') {
			if (!empty($busId)) {
				sendMessage(INVALID_DETAILS_FOUND);
			}
			if (empty($vehicleTypeId)) {
				sendMessage(SELECT_VEHICLE_TYPE,"-1");
			}
			$vehicleTypeArray = $vehicleManager->coutVehicleType($vehicleTypeId);
			$vehicleTypeCount = $vehicleTypeArray[0]['cnt'];
			if (empty($vehicleTypeCount)) {
				sendMessage(INVALID_VEHICLE_TYPE);
			}
		}
		else if ($mode === 'edit') {
			if (empty($busId)) {
				sendMessage(INVALID_DETAILS_FOUND);
			}
			$vehicleIdArray = $vehicleManager->countVehicleId($busId);
			$vehicleIdCount = $vehicleIdArray[0]['cnt'];
			if ($vehicleIdCount == 0) {
				sendMessage(VEHICLE_DOES_NOT_EXISTS,2,'busNo');
			}
			$conditions = " AND busId != $busId";
		}
		
		if (empty($busName)) {
			sendMessage(ENTER_VEHICLE_NAME,2,'busName','',0);
		}
		if (empty($busNo1)) {
			sendMessage(ENTER_VEHICLE_NO,2,'busNo1','',0);
		}
		if (empty($busNo2)) {
			sendMessage(ENTER_VEHICLE_NO,2,'busNo2','',0);
		}
		/*if (empty($busNo3)) {
			sendMessage(ENTER_VEHICLE_NO,2,'busNo3');
		}*/
		if (empty($busNo4)) {
			sendMessage(ENTER_VEHICLE_NO,2,'busNo4','',0);
		}
		
		if (empty($busModel)) {
		    sendMessage(ENTER_VEHICLE_MODEL,2,'busModel','',0);
		}
        
		$curDate = explode('-',date('Y-m-d'));
		$cur_date  =gregoriantojd($curDate[1], $curDate[2], $curDate[0]);

		$vehiclePurchaseDate = explode('-',$purchaseDate);
		$purchase_date  =gregoriantojd($vehiclePurchaseDate[1], $vehiclePurchaseDate[2], $vehiclePurchaseDate[0]);

		$diff = $cur_date - $purchase_date;   //purchase date cannot be greater than current date

		if($diff < 0 ) {
			sendMessage(PURCHASE_DATE_NOT_GREATER,2,'purchaseDate','',0);
		}

		list($purchaseY, $purchaseM, $purchaseD) = explode('-',$purchaseDate);
		if (false === checkdate($purchaseM, $purchaseD, $purchaseY)) {
			sendMessage(INVALID_PURCHASE_DATE,2,'purchaseDate','',0);
		}

		if ($seatingCapacity == '') {
			sendMessage(ENTER_SEATING_CAPACITY,2,'seatingCapacity','',0);
		}

		if (!is_numeric($seatingCapacity) or (intval($seatingCapacity) != floatval($seatingCapacity)) or ($seatingCapacity < 1) or ($seatingCapacity > 9999) or ($seatingCapacity == 0 )) {
			sendMessage(INVALID_SEATING_CAPACITY,2,'seatingCapacity','',0);
		}

		if ($fuelCapacity == '') {
			sendMessage(ENTER_FUEL_CAPACITY,2,'fuelCapacity','',0);
		}
		
		if (!is_numeric($fuelCapacity) or ($fuelCapacity < 1) or ($fuelCapacity > 999999.99) or ($fuelCapacity == 0)) {
		    sendMessage(INVALID_FUEL_CAPACITY,2,'fuelCapacity','',0);
		}
        
        if (empty($vehicleCategory)) {
           sendMessage(SELECT_VEHICLE_CATEGORY,2,'vehicleCategory','',0);
        } 

		if (empty($engineNo)) {
			sendMessage(ENTER_ENGINE_NO,2,'engineNo','',0);
		}

		if ($engineNo != '') {
			$engineNoArray = $vehicleManager->countEngineNo($engineNo, $conditions);
			$engineNoCount = $engineNoArray[0]['cnt'];
			if ($engineNoCount > 0) {
				sendMessage(ENGINE_NO_ALREADY_EXISTS,2,'engineNo','',0);
			}
		}

		if (empty($chasisNo)) {
			sendMessage(ENTER_CHASSIS_NO,2,'chasisNo','',0);
		}

		if ($chasisNo != '') {
			$chasisNoArray = $vehicleManager->countChasisNo($chasisNo, $conditions);
			$chasisNoCount = $chasisNoArray[0]['cnt'];
			if ($chasisNoCount > 0) {
				sendMessage(CHASIS_NO_ALREADY_EXISTS,2,'chasisNo','',0);
			}
		}

		if ($chasisCost != '') {
			if (!is_numeric($chasisCost)) {
				sendMessage(INVALID_CHASIS_COST,2,'chasisCost','',0);
			}
			if ($chasisCost < 0 or $chasisCost > 99999999.99) {
				sendMessage(CHASIS_COST_MUST_BE_BETWEEN_.'1 AND 99999999.99',2,'chasisCost','',0);
			}
		}
		
		//$curDate = explode('-',date('Y-m-d'));
		//$cur_date  =gregoriantojd($curDate[1], $curDate[2], $curDate[0]);

		$vehicleChassisPurchaseDate = explode('-',$chasisPurchaseDate);
		$chassis_purchase_date  =gregoriantojd($vehicleChassisPurchaseDate[1], $vehicleChassisPurchaseDate[2], $vehicleChassisPurchaseDate[0]);

		$diff = $cur_date - $chassis_purchase_date;   //chassis date cannot be small than to date

		if($diff < 0 ) {
			sendMessage(CHASSIS_PURCHASE_DATE_NOT_GREATER,2,'purchaseDate','',0);
		}

		list($chasisPurchaseDateY, $chasisPurchaseDateM, $chasisPurchaseDateD) = explode('-',$chasisPurchaseDate);
		if (false === checkdate($chasisPurchaseDateM, $chasisPurchaseDateD, $chasisPurchaseDateY)) {
			sendMessage(INVALID_CHASIS_PURCHASE_DATE,2,'chasisPurchaseDate','',0);
		}

		if ($bodyCost != '') {
			if (!is_numeric($bodyCost)) {
				sendMessage(INVALID_BODY_COST,2,'bodyCost','',0);
			}
			if ($bodyCost < 0 or $bodyCost > 99999999.99) {
				sendMessage(BODY_COST_MUST_BE_BETWEEN_.'1 AND 99999999.99',2,'bodyCost','',0);
			}
		}

		$vehicleInsuranceDate = explode('-',$insuranceDate);
		$vehicle_insurance_date  =gregoriantojd($vehicleInsuranceDate[1], $vehicleInsuranceDate[2], $vehicleInsuranceDate[0]);

		$vehcileInsuranceDueDate = explode('-',$insuranceDueDate);
		$vehicle_insurance_due_date  =gregoriantojd($vehcileInsuranceDueDate[1], $vehcileInsuranceDueDate[2], $vehcileInsuranceDueDate[0]);
		
		$diff = $vehicle_insurance_due_date - $vehicle_insurance_date;   //chassis date cannot be small than to date

		if($diff < 0 ) {
			sendMessage(VEHICLE_INSURANCE_DATE_NOT_GREATER,2,'insuranceDate','',1);
		}

		list($insuranceDateY, $insuranceDateM, $insuranceDateD) = explode('-',$insuranceDate);
		if (false === checkdate($insuranceDateM, $insuranceDateD, $insuranceDateY)) {
			sendMessage(INVALID_INSURANCE_DATE,2,'insuranceDate','',0);
		}

		if (empty($insuranceDueDate)) {
			sendMessage(ENTER_INSURANCE_DUE_DATE,2,'insuranceDueDate','',1);
		}

		if (empty($policyNo)) {
			sendMessage(ENTER_POLICY_NO,2,'policyNo','',1);
			//showTab('dhtmlgoodies_tabView1',1);
		}

		if ($policyNo != '') {
			$policyNoArray = $vehicleManager->countPolicyNo($policyNo, $conditions);
			$policyNoCount = $policyNoArray[0]['cnt'];
			if ($policyNoCount > 0) {
				sendMessage(POLICY_NO_ALREADY_EXISTS,2,'policyNo','',1);
			}
		}

		if ($valueInsured != '') {
			if (!is_numeric($valueInsured)) {
				sendMessage(INVALID_VALUE_INSURED,2,'valueInsured','',1);
			}
			if ($valueInsured < 0 or $valueInsured > 99999999.99) {
				sendMessage(VALUE_INSURED_MUST_BE_BETWEEN_.'1 AND 99999999.99',2,'valueInsured','',1);
			}
		}

		if ($insurancePremium != '') {
			if (!is_numeric($insurancePremium)) {
				sendMessage(INVALID_INSURANCE_PREMIUM,2,'insurancePremium','',1);
			}
			if ($insurancePremium < 0 or $insurancePremium > 99999999.99) {
				sendMessage(INSURANCE_PREMIUM_MUST_BE_BETWEEN_.'1 AND 99999999.99',2,'insurancePremium','',1);
			}
		}
		if ($ncb != '') {
			if (!is_numeric($ncb)) {
				sendMessage(INVALID_NCB,2,'ncb','',1);
			}
			if ($ncb < 0 or $ncb > 99999999.99) {
				sendMessage(NCB_MUST_BE_BETWEEN_.'1 AND 99999999.99',2,'ncb','',1);
			}
		}

		if (empty($branchName)) {
			sendMessage(ENTER_INSURANCE_BRANCH_NAME,2,'branchName','',1);
		}

		if (empty($agentName)) {
			sendMessage(ENTER_AGENT_NAME,2,'agentName','',1);
		}
		if ($mode === 'add') {
			if (empty($tyreModelNo)) {
				sendMessage(ENTER_TYRE_MODEL_NO,2,'tyreModelNo','',2);
			}
			if (empty($tyreManufacturingCompany)) {
				sendMessage(ENTER_TYRE_MANUFACTURING_COMPANY,2,'tyreManufacturingCompany','',2);
			}
		}

		if (empty($warrantyDate)) {
			sendMessage(ENTER_WARRANTY_DATE,2,'warrantyDate');
		}
		
		$vehicleNoArray = $vehicleManager->countVehicleNo($busNo, $conditions);
		$vehicleNoCount = $vehicleNoArray[0]['cnt'];
		if ($vehicleNoCount > 0) {
			sendMessage(VEHICLE_REGISTRATION_NO_ALREADY_EXISTS,2,'busNo1','',0);
		}
		/*if (empty($busModel)) {
			sendMessage(ENTER_VEHICLE_MODEL,2,'busModel');
		}*/
		//$curDate = date('Y-m-d');
		/*$curDate = explode('-',date('Y-m-d'));
		$cur_date  =gregoriantojd($curDate[1], $curDate[2], $curDate[0]);

		$purchaseDate = explode('-',$purchaseDate);
		$purchase_date  =gregoriantojd($purchaseDate[1], $purchaseDate[2], $purchaseDate[0]);

		$diff = $cur_date - $purchase_date;   //from date cannot be small than to date

		if($diff < 0 ) {
			sendMessage(PURCHASE_DATE_NOT_GREATER,2,'purchaseDate','',0);
		}*/

	
		

		if (!is_numeric($manYear)) {
			sendMessage(INVALID_MANUFACTURING_YEAR);
		}
		$manYearArray = range(date('Y')-8, date('Y'));
		if (!in_array($manYear, $manYearArray)) {
			sendMessage(INVALID_MANUFACTURING_YEAR,2,'manYear');
		}
		
	
		list($putOnRoadDateY, $putOnRoadDateM, $putOnRoadDateD) = explode('-',$putOnRoadDate);
		if (false === checkdate($putOnRoadDateM, $putOnRoadDateD, $putOnRoadDateY)) {
			sendMessage(INVALID_PUT_ON_ROAD_DATE,2,'putOnRoadDate','',0);
		}

        if ($mode === 'add') {  
		    if($insuringCompanyId == '') {
              $insuringCompanyId=0;  
            }
            if($insuringCompanyId == 0 ) {
              sendMessage(INSURANCE_COMPANY_DOES_NOT_EXISTS,2,'insuringCompany','',1);
            }
        
		    $insuringCompanyArray = $vehicleManager->countInsuringCompany($insuringCompanyId);
		    $insuringCompanyCount = $insuringCompanyArray[0]['cnt'];
            if($insuringCompanyCount == 0 ) {
		      sendMessage(INSURANCE_COMPANY_DOES_NOT_EXISTS,2,'insuringCompany','',1);
		    }
        }

		require_once(BL_PATH . "/FileUploadManager.inc.php");
		$tempExtenision = $allowedExtensionsArray;
		$allowedExtensionsArray = array('gif','jpg','jpeg','png','bmp');
		$fileObj = FileUploadManager::getInstance('busPhoto');
		$fileName = $fileObj->name;
		
		if($fileObj->fileExtension != '') {
			if(!in_array($fileObj->fileExtension,$allowedExtensionsArray)) {
				sendMessage(INVALID_EXTENSION.implode(',',$allowedExtensionsArray).EXTENSION,2,'busPhoto','',0);
			}
		}

		$insertedBusId = 0;
		$completeFileName = IMG_PATH.'/Bus/'.$fileName;
				
		if ($fileName != '') {
			
			if(!$fileObj->upload(IMG_PATH.'/Bus/', $fileName)) {
				sendMessage($fileObj->message);
			}
			else {
				//add image.
				$saveStr[] = " busImage = '$fileName'";
			}
		}

		if($fileName == '' && $maxFileUpload == 1) {
			sendMessage(FILE_NOT_UPLOAD.ceil(MAXIMUM_FILE_SIZE/1024).KB,2,'busPhoto','',0);
		}
		/*else {
			sendMessage(FILE_NOT_UPLOAD.ceil(MAXIMUM_FILE_SIZE/1024).KB,2,'busPhoto','',0);
		}*/
		$saveString = implode(',', $saveStr);
		
		if ($mode === 'add') {
			$return = $vehicleManager->addVehicleInTransaction($saveString);
		}
		else {
			$return = $vehicleManager->updateVehicleInTransaction($saveString, $busId);
		}

		
		if ($return == false) {
			sendMessage(ERROR_WHILE_SAVING_VEHICLE,1,$completeFileName);
		}
		else {
			if ($mode === 'add') {
				$insertedBusIdArray = $vehicleManager->getLastVehicleId();
				$insertedBusId = $insertedBusIdArray[0]['busId'];
				if ($insertedBusId != 0) {
					if (!array_key_exists($paymentMode, $modeArr)) {
						sendMessage(INVALID_PAYMENT_MODE,2,'paymentMode','',1);
					}
                   if($insuringCompanyId!=0 and $insuringCompanyId!=''){ 
					   
					    $return = $vehicleManager->addVehicleInsuranceInTransaction($insertedBusId, $insuranceDate, $insuranceDueDate, $policyNo, $insuringCompanyId, $valueInsured, $insurancePremium, $paymentMode, $branchName, $agentName, $paymentDescription, $ncb);
						echo "return";
					    if ($return == false) {
						    sendMessage(ERROR_WHILE_SAVING_INSURANCE,1,$completeFileName);
					    }
						$returnValue = $vehicleManager->addInsuranceVehicleHistory($insertedBusId, $insuranceDate, $insuranceDueDate);
						if($returnValue === false) {
							echo FAILURE;
							die;
						}
                    }
					if ($regnNoValidTill != '' and $passengerTaxValidTill != '' and $roadTaxValidTill != '' and $pollutionTaxValidTill != '' and $passingValidTill != '') {
						$return = $vehicleManager->addVehicleTaxInTransaction($insertedBusId, $regnNoValidTill, $passengerTaxValidTill, $roadTaxValidTill, $pollutionTaxValidTill, $passingValidTill);
					    if ($return == false) {
						    sendMessage(ERROR_WHILE_SAVING_TAX,1,$completeFileName);
					    }
					}

					$vehicleTyreArray = $vehicleManager->getVehicleTyres($vehicleTypeId);
					$mainTyres = $vehicleTyreArray[0]['mainTyres'];
					$spareTyres = $vehicleTyreArray[0]['spareTyres'];
					$x = 1;
					$tyrePlacementReason = 'New Vehicle Purchased';
					$tyresArray = array();
					if($mainTyres > 0 ) {
					while ($x <= $mainTyres) {
						$tyreName = 'mainTyre_'.$x;
						$tyreNo = $REQUEST_DATA[$tyreName];
						//echo ($tyreNo);
						if($tyreNo == '') {
							sendMessage(ENTER_TYRE_NO.' at '.$x,3,$completeFileName,$tyreName,2);
						}
						if($tyreNo != '') {
							if($tyreNo == '0') {
								sendMessage(INVALID_TYRE_NO.' at '.$x,3,$completeFileName,$tyreName,2);
							}
							if (in_array($tyreNo, $tyresArray)) {
								sendMessage(TYRE_NO_.$tyreNo._ALREADY_EXISTS,3,$completeFileName,$tyreName,2);
							}
							else {
								$tyresArray[] = $tyreNo;
							}
							$tyreNoArray = $vehicleManager->countTyreNo($tyreNo);
							$tyreNoCount = $tyreNoArray[0]['cnt'];
							if ($tyreNoCount > 0) {
								sendMessage(TYRE_NO_.$tyreNo._ALREADY_EXISTS,3,$completeFileName,$tyreName,2);
							}
							else {
								$return = $vehicleManager->addTyreInTransaction($tyreNo, $tyreManufacturingCompany, $tyreModelNo, $purchaseDate, 1);
								if ($return == false) {
									sendMessage(ERROR_WHILE_SAVING_TYRE,1,$completeFileName);
								}
								else {
									$insertedTyreIdArray = $vehicleManager->getLastTyreId();
									$insertedTyreId = $insertedTyreIdArray[0]['tyreId'];
									$return = $vehicleManager->addTyreHistoryInTransaction($insertedTyreId, $insertedBusId, 0, 0, $purchaseDate, 1, $tyrePlacementReason, 0);
									if ($return == false) {
										sendMessage(ERROR_WHILE_SAVING_TYRE_HISTORY,1,$completeFileName);
									}
								}
							}
						}
						$x++;
					}
					}

					$x = 1;
					if($spareTyres > 0 ) {
					while ($x <= $spareTyres) {
						$tyreName = 'spareTyre_'.$x;
						$tyreNo = $REQUEST_DATA[$tyreName];
						if($tyreNo == '') {
							sendMessage(ENTER_TYRE_NO.' at Spare Tyre '.$x,3,$completeFileName,$tyreName,2);
						}
						if ($tyreNo != '') {
							if ($tyreNo == '0') {
								sendMessage(INVALID_TYRE_NO.' at Spare Tyre '.$x,3,$completeFileName,$tyreName,2);
							}
							if (in_array($tyreNo, $tyresArray)) {
								sendMessage(TYRE_NO_.$tyreNo._ALREADY_EXISTS,3,$completeFileName,$tyreName,2);
							}
							else {
								$tyresArray[] = $tyreNo;
							}
							$tyreNoArray = $vehicleManager->countTyreNo($tyreNo);
							$tyreNoCount = $tyreNoArray[0]['cnt'];
							if ($tyreNoCount > 0) {
								sendMessage(TYRE_NO_.$tyreNo._ALREADY_EXISTS,3,$completeFileName,$tyreName,2);
							}
							else {
								$return = $vehicleManager->addTyreInTransaction($tyreNo, $tyreManufacturingCompany, $tyreModelNo, $purchaseDate, 1);
								if ($return === false) {
									@unlink($completeFileName);
									sendMessage(ERROR_WHILE_SAVING_TYRE,1,$completeFileName);
								}
								else {
									$insertedTyreIdArray = $vehicleManager->getLastTyreId();
									$insertedTyreId = $insertedTyreIdArray[0]['tyreId'];
									$return = $vehicleManager->addTyreHistoryInTransaction($insertedTyreId, $insertedBusId, 0, 0, $purchaseDate, 0, $tyrePlacementReason, 0);
									if ($return == false) {
										sendMessage(ERROR_WHILE_SAVING_TYRE_HISTORY,1,$completeFileName);
									}
								}
							}
						}
						$x++;
					}
					}

					if (empty($batteryNo)) {
						sendMessage(ENTER_BATTERY_NO,2,'batteryNo','',3);
					}

					if (empty($batteryMake)) {
						sendMessage(ENTER_BATTERY_MAKE,2,'batteryMake','',3);
					}

					if ($batteryNo != '' ) {
						$tyreBatteryNoArray = $vehicleManager->countBatteryNo($batteryNo);
						$batteryNoCount = $tyreBatteryNoArray[0]['cnt'];
						if ($batteryNoCount > 0) {
							sendMessage(BATTERY_NO_ALREADY_EXISTS,2,'batteryNo','',3);
						}
						else {
							$return = $vehicleManager->addVehicleBatteryTransaction($insertedBusId, $batteryNo, $batteryMake, $warrantyDate);
							if ($return == false) {
								sendMessage(ERROR_WHILE_SAVING_BATTERY,1,$completeFileName);
							}
						}
					}

					if($freeService == '') {
						sendMessage(ENTER_FREE_SERVICE_NO,2,'freeService','',4);
					}

					if($freeService == 0 || $freeService < 1) {
						sendMessage(INVALID_FREE_SERVICE_NO,2,'freeService','',4);
					}



					$x=1;
					$oldServiceKm = 0;
					$oldServiceDate = '';
					if ($freeService > 0 ) {
						while ($x <= $freeService) {
							$serviceNo = 'Free service no.'.$x;
							$serviceDueDate = 'serviceDate_'.$x;
							$serviceDate = $REQUEST_DATA[$serviceDueDate];
							$serviceFormatDate = UtilityManager::formatDate($serviceDate);
							$serviceDueKM = 'kmRun_'.$x;
							$serviceKM = $REQUEST_DATA[$serviceDueKM];
							if ($oldServiceDate != '') {
								if ($serviceDate <= $oldServiceDate) {
									sendMessage(_NOT_SMALLER. ' at Free service no.'.$x,3,$completeFileName,$serviceDueDate,4);
								}
							}
							if ($oldServiceKm != 0) {
								if ($serviceKM < $oldServiceKm) {
									sendMessage(INVALID_KM.$serviceKM._NOT_SMALLER_KM. ' at Free service no.'.$x,3,$completeFileName,$serviceDueKM,4);
								}
							}
							if ($serviceKM == '') {
								sendMessage(ENTER_SERVICE_KM.' at Free service no.'.$x,3,$completeFileName,$serviceDueKM,4);
							}
							if ($serviceKM != '') {
								if ($serviceKM == '0' || $serviceKM < 1) {
								sendMessage(INVALID_SERVICE_KM.' at Free service no.'.$x,3,$completeFileName,$serviceDueKM,4);
								}
								if (!is_numeric($serviceKM) or (intval($serviceKM) != floatval($serviceKM)) or ($serviceKM < 1)) {
									sendMessage(INVALID_SERVICE_KM.' at Free service no.'.$x,3,$completeFileName,$serviceDueKM,4);
								}
								if (in_array($serviceKM, $serviceKMArray)) {
									sendMessage(SERVICE_KM_.$serviceKM._SERVICE_ALREADY_EXISTS. ' at Free service no.'.$x,3,$completeFileName,$serviceDueKM,4);
								}
								else {
									$serviceKMArray[] = $serviceKM;
								}
							}
							$return = $vehicleManager->addServiceInTransaction($insertedBusId, $serviceNo, $serviceDate, $serviceKM);
							if ($return == false) {
								sendMessage(ERROR_WHILE_SAVING_SERVICE,1,$completeFileName);
							}
							$oldServiceDate = $serviceDate;
							$oldServiceKm = $serviceKM;
							$x++;
						}
					}
				}
			}
			/*else {
				$return = $vehicleManager->updateVehicleInsuranceInTransaction($busId, $insuranceDate, $insuranceDueDate, $policyNo, $insuringCompanyId, $valueInsured, $insurancePremium, $paymentMode, $branchName, $agentName, $paymentDescription, $ncb);
				if ($return == false) {
					sendMessage(ERROR_WHILE_UPDATING_INSURANCE_DETAILS,1,$completeFileName);
				}
			}*/
		}
				
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		?>
		<script language="JavaScript">
         var mode="<?php echo $mode; ?>";
         if(mode=='add'){
		     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
			     parent.document.vehicleForm.reset();
				 parent.document.getElementById('serviceDetailDiv').innerHTML = '';
				 parent.sendReq(parent.listURL,parent.divResultName,parent.searchFormName,'page='+parent.page+'&sortOrderBy='+parent.sortOrderBy+'&sortField='+parent.sortField);
				 parent.document.vehicleForm.uniqueId.value = '<?php echo $_SESSION["xId"];?>';
				 parent.setMode('add');
				 parent.showTab("dhtmlgoodies_tabView1",<?php echo 0;?>);
				 parent.document.vehicleForm.vehicleType.focus();
				 parent.getVehicleTyres();
		     }
		     else {
				 parent.document.vehicleForm.reset();
			     parent.hiddenFloatingDiv('VehicleDiv');
			     parent.sendReq(parent.listURL,parent.divResultName,parent.searchFormName,'page='+parent.page+'&sortOrderBy='+parent.sortOrderBy+'&sortField='+parent.sortField);
				 parent.document.vehicleForm.uniqueId.value = '<?php echo $_SESSION["xId"];?>';
		    }
         }
         else {
                 parent.hiddenFloatingDiv('VehicleDiv');
                 parent.sendReq(parent.listURL,parent.divResultName,parent.searchFormName,'page='+parent.page+'&sortOrderBy='+parent.sortOrderBy+'&sortField='+parent.sortField);
				 parent.document.vehicleForm.uniqueId.value = '<?php echo $_SESSION["xId"];?>';
            }   
		</script>
		<?php
		}
		else {
			sendMessage(FAILURE);
		}
	}
	else {
		sendMessage(FAILURE);
	}
}
elseif ($mode == 'delete') {
	if (empty($busId)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	$vehicleIdArray = $vehicleManager->countVehicleId($busId);
	$vehicleIdCount = $vehicleIdArray[0]['cnt'];
	if ($vehicleIdCount == 0) {
		echo VEHICLE_DOES_NOT_EXISTS;
		die;
	}
	else {
		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$return = $vehicleManager->discardBusInTransaction($busId);
			if ($return == false) {
				echo ERROR_WHILE_DISCARDING_VEHICLE;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DELETE;
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
}

function sendMessage($str, $type = 2, $thirdParam = '', $fourthParam = '', $tabIndex = '') {
	if ($type == 1 or $type == 3) {
		@unlink($thirdParam);
	}
?>
	<script language="JavaScript">
		alert("<?php echo $str;?>");
	</script>
<?php
    if ($type == "-1") {
?>      <script language="JavaScript">
           eval('parent.document.vehicleForm.vehicleType.focus()');  
        </script>
<?php
    }    
	if ($type == 2 and $thirdParam != '') {
?>
		<script language="JavaScript">                
			ele = "<?php echo $thirdParam;?>";
			parent.showTab("dhtmlgoodies_tabView1",<?php echo $tabIndex;?>);
			eval('parent.document.vehicleForm.'+ele+'.focus()');
		</script>
<?php
	}
	if ($type == 3 and $fourthParam != '') {
?>
		<script language="JavaScript">
			ele = "<?php echo $fourthParam;?>";
			parent.showTab("dhtmlgoodies_tabView1",<?php echo $tabIndex;?>);
			eval('parent.document.vehicleForm.'+ele+'.focus()');
		</script>
<?php
	}
	die;
}



?>