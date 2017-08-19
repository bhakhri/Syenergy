<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Vehicle');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();
	$vehicleId = add_slashes(trim($REQUEST_DATA['busId']));
	$vehicleCountArray = $vehicleManager->countVehicleId($vehicleId);
	$vehicleCount = $vehicleCountArray[0]['cnt'];
	if ($vehicleCount == 0) {
		echo INVALID_VEHICLE_SELECTED;
		die;
	}

	$mainArray = array();
	$vehicleDetailsArray									=	$vehicleManager->getVehicleDetails($vehicleId);
	$mainArray['vehicleDetails'][0]['vehicleTypeId']		=	strip_slashes($vehicleDetailsArray[0]['vehicleTypeId']);
    $mainArray['vehicleDetails'][0]['busId']				=    strip_slashes($vehicleDetailsArray[0]['busId']);
	$mainArray['vehicleDetails'][0]['busName']				=	strip_slashes($vehicleDetailsArray[0]['busName']);
	$vehicleDetail = explode(' ',$vehicleDetailsArray[0]['busNo']);
	if($vehicleDetail[3] == '') {
		$mainArray['vehicleDetails'][0]['busNo1'] = $vehicleDetail[0];
		$mainArray['vehicleDetails'][0]['busNo2'] = $vehicleDetail[1];
		$mainArray['vehicleDetails'][0]['busNo3'] = '';
		$mainArray['vehicleDetails'][0]['busNo4'] = $vehicleDetail[2];
	}
	else {
		$mainArray['vehicleDetails'][0]['busNo1'] = $vehicleDetail[0];
		$mainArray['vehicleDetails'][0]['busNo2'] = $vehicleDetail[1];
		$mainArray['vehicleDetails'][0]['busNo3'] = $vehicleDetail[2];
		$mainArray['vehicleDetails'][0]['busNo4'] = $vehicleDetail[3];
	}

	//$mainArray['vehicleDetails'][0]['busNo']				=	strip_slashes($vehicleDetailsArray[0]['busNo']);
	$mainArray['vehicleDetails'][0]['engineNo']				=	strip_slashes($vehicleDetailsArray[0]['engineNo']);
	$mainArray['vehicleDetails'][0]['chasisNo']				=	strip_slashes($vehicleDetailsArray[0]['chasisNo']);
	$mainArray['vehicleDetails'][0]['yearOfManufacturing']	=	strip_slashes($vehicleDetailsArray[0]['yearOfManufacturing']);
	$mainArray['vehicleDetails'][0]['purchaseDate']			=	strip_slashes($vehicleDetailsArray[0]['purchaseDate']);
	$mainArray['vehicleDetails'][0]['modelNumber']			=	strip_slashes($vehicleDetailsArray[0]['modelNumber']);
	$mainArray['vehicleDetails'][0]['busImage']				=	strip_slashes($vehicleDetailsArray[0]['busImage']);
	$mainArray['vehicleDetails'][0]['seatingCapacity']		=	strip_slashes($vehicleDetailsArray[0]['seatingCapacity']);
	$mainArray['vehicleDetails'][0]['fuelCapacity']			=	strip_slashes($vehicleDetailsArray[0]['fuelCapacity']);
	$mainArray['vehicleDetails'][0]['bodyMaker']			=	strip_slashes($vehicleDetailsArray[0]['bodyMaker']);
	$mainArray['vehicleDetails'][0]['chasisCost']			=	strip_slashes($vehicleDetailsArray[0]['chasisCost']);
	$mainArray['vehicleDetails'][0]['chasisPurchaseDate']	=	strip_slashes($vehicleDetailsArray[0]['chasisPurchaseDate']);
	$mainArray['vehicleDetails'][0]['bodyCost']				=	strip_slashes($vehicleDetailsArray[0]['bodyCost']);
	$mainArray['vehicleDetails'][0]['putOnRoad']			=	strip_slashes($vehicleDetailsArray[0]['putOnRoad']);
    $mainArray['vehicleDetails'][0]['vechicleCategoryId']    =   strip_slashes($vehicleDetailsArray[0]['vechicleCategoryId']); 
    
   
	$vehicleInsuranceArray									=	$vehicleManager->getVehicleLastInsuranceDetails($vehicleId);
	$mainArray['insuranceDetails'][0]['lastInsuranceDate']	=	UtilityManager::formatDate(strip_slashes($vehicleInsuranceArray[0]['lastInsuranceDate']));
	$mainArray['insuranceDetails'][0]['insuringCompanyId']	=	strip_slashes($vehicleInsuranceArray[0]['insuringCompanyId']);
	$mainArray['insuranceDetails'][0]['insuranceDueDate']	=	UtilityManager::formatDate(strip_slashes($vehicleInsuranceArray[0]['insuranceDueDate']));
	$mainArray['insuranceDetails'][0]['policyNo']			=	strip_slashes($vehicleInsuranceArray[0]['policyNo']);
	$mainArray['insuranceDetails'][0]['valueInsured']		=	strip_slashes($vehicleInsuranceArray[0]['valueInsured']);
	$mainArray['insuranceDetails'][0]['insurancePremium']	=	strip_slashes($vehicleInsuranceArray[0]['insurancePremium']);
	$mainArray['insuranceDetails'][0]['paymentMode']		=	strip_slashes($vehicleInsuranceArray[0]['paymentMode']);
	$mainArray['insuranceDetails'][0]['paymentDescription']	=	strip_slashes($vehicleInsuranceArray[0]['paymentDescription']);
	$mainArray['insuranceDetails'][0]['ncb']				=	strip_slashes($vehicleInsuranceArray[0]['ncb']);
	$mainArray['insuranceDetails'][0]['branchName']			=	strip_slashes($vehicleInsuranceArray[0]['branchName']);
	$mainArray['insuranceDetails'][0]['agentName']			=	strip_slashes($vehicleInsuranceArray[0]['agentName']);


	$vehicleTaxArray										=	$vehicleManager->getVehicleTaxDetail($vehicleId);
	$mainArray['taxDetails'][0]['busNoValidTill']			=	UtilityManager::formatDate(strip_slashes($vehicleTaxArray[0]['busNoValidTill']));
	$mainArray['taxDetails'][0]['passengerTaxValidTill']	=	UtilityManager::formatDate(strip_slashes($vehicleTaxArray[0]['passengerTaxValidTill']));
	$mainArray['taxDetails'][0]['roadTaxValidTill']			=	UtilityManager::formatDate(strip_slashes($vehicleTaxArray[0]['roadTaxValidTill']));
	$mainArray['taxDetails'][0]['pollutionCheckValidTill']	=	UtilityManager::formatDate(strip_slashes($vehicleTaxArray[0]['pollutionCheckValidTill']));
	$mainArray['taxDetails'][0]['passingValidTill']			=	UtilityManager::formatDate(strip_slashes($vehicleTaxArray[0]['passingValidTill']));


	$vehicleTyreArray										=	$vehicleManager->getVehicleTyreDetail($vehicleId);
	
	$cnt = count($vehicleTyreArray);
	
	$x = 1;
	if($cnt > 0 && is_array($vehicleTyreArray)) {
		$tyreDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
		for($i=0;$i<$cnt;$i++) {
			if($vehicleTyreArray[$i]['usedAsMainTyre'] == 1) {
				$tyreDiv .= '<tr><td width="20%">&nbsp;Model No.</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" readOnly="readOnly" name = "tyreModelNo_'.$y.'" value="'.$vehicleTyreArray[$i]['modelNumber'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td width="20%">&nbsp;Manufacturer</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" readOnly="readOnly" name = "tyreManufacturerNo_'.$y.'" value="'.$vehicleTyreArray[$i]['manufacturer'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td width="20%">&nbsp;Main Tyre '.$x.'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" name="mainTyre_'.$x.'" readOnly="readOnly" value="'.$vehicleTyreArray[$i]['tyreNumber'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td height="2%"><hr></td></tr>';
				
			}
			if($vehicleTyreArray[$i]['usedAsMainTyre'] == 0) {
				$tyreDiv .= '<tr><td width="20%">&nbsp;Model No.</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" readOnly="readOnly" name = "tyreModelNo_'.$y.'" value="'.$vehicleTyreArray[$i]['modelNumber'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td width="20%">&nbsp;Manufacturer</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" readOnly="readOnly" name = "tyreManufacturerNo_'.$y.'" value="'.$vehicleTyreArray[$i]['manufacturer'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td width="20%">&nbsp;Spare Tyre '.$x.'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" style="width:300px" name="spareTyre_'.$x.'" readOnly="readOnly" value="'.$vehicleTyreArray[$i]['tyreNumber'].'" maxlength="30"/></td></tr>';
				$tyreDiv .= '<tr><td height="2%"><hr></td></tr>';
			}
			$x++;
		}
		$tyreDiv .= '</table>';
	}
	//print_r($vehicleTyreArray);
	//echo($tyreDiv);
	//die;
	/*$y = 1;
	if($cnt > 0 && is_array($vehicleTyreArray)) {
		$modelDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
		for($j=0;$j<$cnt;$j++) {
			//echo($y);
			
			$y++;
			//echo($vehicleTyreArray[$j]['modelNumber']);
		}
		$modelDiv .= '</table>';
	}*/
	//echo ($modelDiv);
	//die;
	
	
	//$mainArray['tyreDetails'][0]['modelNumber']				=	strip_slashes($vehicleTyreArray[0]['modelNumber']);
	$mainArray['modelDiv']	= $modelDiv;
	$mainArray['tyreDetails'][0]['manufacturer']			=	strip_slashes($vehicleTyreArray[0]['manufacturer']);
	$mainArray['tyreDiv']									=	$tyreDiv;


	$vehicleTotalServiceArray								=	$vehicleManager->getTotalVehicleService($vehicleId);
	//echo($vehicleTotalServiceArray[0]['countRecords']);
	if($vehicleTotalServiceArray[0]['countRecords']	> 0 ) {
		$vehicleServiceArray							=	$vehicleManager->getVehicleServiceDetail($vehicleId);
		//echo('<pre>');
		//print_r($vehicleServiceArray);
		$x=1;
		if(count($vehicleServiceArray)	> 0 && is_array($vehicleServiceArray)) {
			$serviceDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			$serviceDiv .= '<tr><td width="10%"></td><td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Due Date</b></td><td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;<b>KM Run</b></td></tr>';
			for($j=0;$j<$vehicleTotalServiceArray[0]['countRecords'];$j++) {
				$vehicleServiceArray[$j]['serviceDueDate'] = UtilityManager::formatDate($vehicleServiceArray[$j]['serviceDueDate']);
				$serviceDiv .= '<tr><td width="10%">Free service no. '.$x.'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox" type="text" name="serviceDate_'.$x.'" readOnly="readOnly" value="'.$vehicleServiceArray[$j]['serviceDueDate'].'"/></td>';
				$serviceDiv .= '<td width="10%">&nbsp;<input class="inputbox" type="text" maxlength = "9" name="kmRun_'.$x.'" readOnly="readOnly" value="'.$vehicleServiceArray[$j]['serviceDueKM'].'"/></td></tr>';
				$x++;
			}
		}
			$serviceDiv .= '</table>';
	}
	
	if($vehicleTotalServiceArray[0]['countRecords']	> 0 ) {
		$mainArray['serviceDetails'][0]['countRecords']				=	strip_slashes($vehicleTotalServiceArray[0]['countRecords']);
		$mainArray['serviceDiv']									=	$serviceDiv;
	}
	else {
		$mainArray['serviceDetails'][0]['countRecords']				=	0;
	}
	
	//die;
		
	
	$mainArray['tyreDetails'][0]['modelNumber']				=	strip_slashes($vehicleTyreArray[0]['modelNumber']);
	$mainArray['tyreDetails'][0]['manufacturer']			=	strip_slashes($vehicleTyreArray[0]['manufacturer']);
	$mainArray['tyreDiv']									=	$tyreDiv;

	$vehicleBatteryArray									=	$vehicleManager->getVehicleBatteryDetail($vehicleId);
	$mainArray['batteryDetails'][0]['batteryNo']			=	strip_slashes($vehicleBatteryArray[0]['batteryNo']);
	$mainArray['batteryDetails'][0]['batteryMake']			=	strip_slashes($vehicleBatteryArray[0]['batteryMake']);
	$mainArray['batteryDetails'][0]['warrantyDate']			=	UtilityManager::formatDate(strip_slashes($vehicleBatteryArray[0]['warrantyDate']));
	

	echo json_encode($mainArray);



?>