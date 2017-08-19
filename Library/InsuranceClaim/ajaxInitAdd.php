<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW DESIGNATION
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceClaim');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= SELECT_BUS. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['claimDate']) || trim($REQUEST_DATA['claimDate']) == '')) {
        $errorMessage .= SELECT_CLAIM_DATE. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['claimAmount']) || trim($REQUEST_DATA['claimAmount']) == '')) {
        $errorMessage .= ENTER_CLAIM_AMOUNT. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['totalExpenses']) || trim($REQUEST_DATA['totalExpenses']) == '')) {
        $errorMessage .= ENTER_TOTAL_EXPENSES. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['selfExpenses']) || trim($REQUEST_DATA['selfExpenses']) == '')) {
        $errorMessage .= ENTER_SELF_EXPENSES. '<br/>';
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['loggingClaim']) || trim($REQUEST_DATA['loggingClaim']) == '')) {
        $errorMessage .= ENTER_LOGGING_CLAIM. '<br/>';
    }
	 if ($errorMessage == '' && (!isset($REQUEST_DATA['settlementDate']) || trim($REQUEST_DATA['settlementDate']) == '')) {
        $errorMessage .= SELECT_SETTLEMENT_DATE. '<br/>';
    }
    

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/InsuranceClaimManager.inc.php");
		$insuranceClaimManager = InsuranceClaimManager::getInstance();
		//$foundArray = $insuranceVehicleManager->getVehicleInsurance(' WHERE LCASE(busNo)= "'.add_slashes(trim(strtolower($REQUEST_DATA['busNo']))).'"');

		$busNo = $REQUEST_DATA['busNo'];
		$claimDate = $REQUEST_DATA['claimDate'];
		$dateOfSettlement = $REQUEST_DATA['settlementDate'];
		$claimAmount = $REQUEST_DATA['claimAmount'];
		$totalExpenses = $REQUEST_DATA['totalExpenses'];
		$selfExpenses = $REQUEST_DATA['selfExpenses'];
		$ncbClaim = $REQUEST_DATA['ncbClaim'];
		$loggingClaim = $REQUEST_DATA['loggingClaim'];

		if($busNo == '') {
			echo SELECT_BUS;
			die;
		}
		
		if($claimDate == '' || $claimDate == '0000-00-00') {
			echo SELECT_CLAIM_DATE ;
			die;
		}

		if($dateOfSettlement == '' || $dateOfSettlement == '0000-00-00') {
			echo SELECT_SETTLEMENT_DATE ;
			die;
		}

		if ($claimAmount < 0 or $claimAmount > 9999999999.99) {
			echo INVALID_CLAIM_AMOUNT;
			die;
		}
		if ($totalExpenses < 0 or $totalExpenses > 9999999999.99) {
			echo INVALID_TOTAL_EXPENSES;
			die;
		}

		if($totalExpenses < $claimAmount) {
			echo TOTAL_EXPENSES_NOT_LESS;
			die;
		}

		if ($selfExpenses < 0 or $selfExpenses > 9999999999.99) {
			echo INVALID_SELF_EXPENSES;
			die;
		}

		if ($ncbClaim < 0 or $ncbClaim > 9999999999.99) {
			echo INVALID_NO_CLAIM_BONUS;
			die;
		}
		if(!is_numeric($ncbClaim)){
			echo INVALID_NO_CLAIM_BONUS;
			die;
		}

		if ($loggingClaim < 0 or $loggingClaim > 9999999999.99) {
			echo INVALID_LOGGING_CLAIM;
			die;
		}

		if($totalExpenses != '' && $claimAmount != '') {
			$selfExpenses = $totalExpenses - $claimAmount;
		}

		

		/*
		$vehicleInsuranceDate = explode('-',$insuranceDate);
		$vehicle_insurance_date  =gregoriantojd($vehicleInsuranceDate[1], $vehicleInsuranceDate[2], $vehicleInsuranceDate[0]);

		$vehcileInsuranceDueDate = explode('-',$insuranceDueDate);
		$vehicle_insurance_due_date  =gregoriantojd($vehcileInsuranceDueDate[1], $vehcileInsuranceDueDate[2], $vehcileInsuranceDueDate[0]);
		
		$diff = $vehicle_insurance_due_date - $vehicle_insurance_date;   //chassis date cannot be small than to date

		if($diff < 0 ) {
			echo VEHICLE_INSURANCE_DATE_NOT_GREATER;
			die;
		}
		*/
		
		
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if($busNo!='') {  //DUPLICATE CHECK
				$conditions = "WHERE busId = $busNo AND claimDate = '$claimDate'";
				$returnDateStatus = $insuranceClaimManager->getInsuranceClaim($conditions);
				if ($returnDateStatus[0]['totalRecords'] > 0) {
					echo INSURANCE_CLAIM_ALREADY_TAKEN;
					die;
				}
				$returnStatus = $insuranceClaimManager->addInsuranceClaim($selfExpenses);
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
			 else {
				echo BUS_NO_NOT_EXIST;
				die;
			 }
			}
			else{
			  echo FAILURE;
			  die;
		 }
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/08/10    Time: 6:47p
//Updated in $/Leap/Source/Library/InsuranceClaim
//fixed bug nos. 0002810, 0002808, 0002807, 0002806, 0002803, 0002804
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Library/InsuranceClaim
//new ajax files for vehicle insurance claim
//
//
?>