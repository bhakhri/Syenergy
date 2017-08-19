<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A DEGREE
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceVehicle');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= SELECT_BUS. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['insuringCompanyId']) || trim($REQUEST_DATA['insuringCompanyId']) == '')) {
        $errorMessage .= SELECT_INSURANCE_COMPANY. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['valueInsured']) || trim($REQUEST_DATA['valueInsured']) == '')) {
        $errorMessage .= ENTER_VALUE_INSURED. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['insurancePremium']) || trim($REQUEST_DATA['insurancePremium']) == '')) {
        $errorMessage .= ENTER_INSURANCE_PREMIUM. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['paymentMode']) || trim($REQUEST_DATA['paymentMode']) == '')) {
        $errorMessage .= SELECT_PAYMENT_MODE. '<br/>';
    }

    if (trim($errorMessage) == '') {
			require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
			$insuranceVehicleManager = InsuranceVehicleManager::getInstance();

			//
			//$foundArray1 = $insuranceVehicleManager->getVehicleInsurance('WHERE ( UCASE(busNo)="'.add_slashes(trim(strtoupper($REQUEST_DATA['busNo']))).'" ) AND  insuranceId!='.$REQUEST_DATA['insuranceId']);
        $busNo = $REQUEST_DATA['busNo'];
		$insuranceDate = $REQUEST_DATA['insuranceDate'];
		$insuranceDueDate = $REQUEST_DATA['insuranceDueDate'];
		$insuringCompanyId = $REQUEST_DATA['insuringCompanyId'];
		$valueInsured = $REQUEST_DATA['valueInsured'];
		$insurancePremium = $REQUEST_DATA['insurancePremium'];
		$paymentMode = $REQUEST_DATA['paymentMode'];

		if($busNo == '') {
			echo SELECT_BUS;
			die;
		}

		if($insuranceDate == '' || $insuranceDate == '0000-00-00') {
			echo SELECT_INSURANCE_DATE ;
			die;
		}

		if($insuringCompanyId == '') {
			echo SELECT_INSURANCE_COMPANY;
			die;
		}

		if($valueInsured == '') {
			echo ENTER_VALUE_INSURED;
			die;
		}

		if($insurancePremium == '') {
			echo ENTER_INSURANCE_PREMIUM;
			die;
		}

		if($paymentMode == '') {
			echo SELECT_PAYMENT_MODE;
			die;
		}

		$vehicleInsuranceDate = explode('-',$insuranceDate);
		$vehicle_insurance_date  =gregoriantojd($vehicleInsuranceDate[1], $vehicleInsuranceDate[2], $vehicleInsuranceDate[0]);

		$vehcileInsuranceDueDate = explode('-',$insuranceDueDate);
		$vehicle_insurance_due_date  =gregoriantojd($vehcileInsuranceDueDate[1], $vehcileInsuranceDueDate[2], $vehcileInsuranceDueDate[0]);
		
		$diff = $vehicle_insurance_due_date - $vehicle_insurance_date;   //chassis date cannot be small than to date

		if($diff < 0 ) {
			echo VEHICLE_INSURANCE_DATE_NOT_GREATER;
			die;
		}

			if($busNo!='') {  //DUPLICATE CHECK
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
				$returnStatus = $insuranceVehicleManager->editInsuranceVehicle($REQUEST_DATA['insuranceId']);
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

// $History: ajaxInitEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:16p
//Created in $/Leap/Source/Library/InsuranceVehicle
//new ajax files for vehicle insurance
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
//
?>