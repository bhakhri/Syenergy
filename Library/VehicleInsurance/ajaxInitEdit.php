<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A DEGREE
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleInsuranceMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['insuringCompanyName']) || trim($REQUEST_DATA['insuringCompanyName']) == '')) {
        $errorMessage .= ENTER_INSURING_COMPANY. '<br/>';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
			$foundArray1 = VehicleInsuranceManager::getInstance()->getVehicleInsurance('WHERE ( UCASE(insuringCompanyName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['insuringCompanyName']))).'" ) AND  insuringCompanyId!='.$REQUEST_DATA['insuringCompanyId']);
            
			if(trim($foundArray1[0]['insuringCompanyName'])=='') {  //DUPLICATE CHECK
            $returnStatus = VehicleInsuranceManager::getInstance()->editVehicleInsurance($REQUEST_DATA['insuringCompanyId']);
			if($returnStatus === false) {
                echo FAILURE;
                die;
            }
            else {
                echo SUCCESS;
                die;
            }
         }
		else {
			if(trim($foundArray1[0]['insuringCompanyName'])==$REQUEST_DATA['insuringCompanyName']){
             echo INSURANCE_COMPANY_ALREADY_EXIST;
             die;
            }
		}
	}
else {
	echo $errorMessage;
}

// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
//
?>