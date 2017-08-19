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
define('MODULE','VehicleInsuranceMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['insuringCompanyName']) || trim($REQUEST_DATA['insuringCompanyName']) == '')) {
        $errorMessage .= ENTER_INSURING_COMPANY. '<br/>';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
		$vehicleInsuranceManager = VehicleInsuranceManager::getInstance();
		$foundArray = $vehicleInsuranceManager->getVehicleInsurance(' WHERE LCASE(insuringCompanyName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['insuringCompanyName']))).'"');
		
			if(trim($foundArray[0]['insuringCompanyName'])=='') {  //DUPLICATE CHECK
				$returnStatus = $vehicleInsuranceManager->addVehicleInsurance();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				if(trim(strtoupper($foundArray[0]['insuringCompanyName']))==trim(strtoupper($REQUEST_DATA['insuringCompanyName']))) {
				 echo INSURANCE_COMPANY_ALREADY_EXIST;
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
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
?>