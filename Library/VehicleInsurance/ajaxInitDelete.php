<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleInsuranceMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['insuringCompanyId']) || trim($REQUEST_DATA['insuringCompanyId']) == '') {
        $errorMessage = INVALID_INSURANCE_COMPANY;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
		$vehicleInsuranceManager = VehicleInsuranceManager::getInstance();

            if($vehicleInsuranceManager->deleteVehicleInsurance($REQUEST_DATA['insuringCompanyId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
//
?>