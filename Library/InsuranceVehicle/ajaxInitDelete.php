<?php
//-------------------------------------------------------
// Purpose: To delete Insurance Vehicle detail
//
// Author : Jaineesh
// Created on : (03.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceVehicle');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['insuranceId']) || trim($REQUEST_DATA['insuranceId']) == '') {
        $errorMessage = INVALID_INSURANCE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
		$insuranceVehicleManager = InsuranceVehicleManager::getInstance();
		$insuranceVehicleArray = $insuranceVehicleManager->getVehicleInsuranceDetail($REQUEST_DATA['insuranceId']);
		if ($insuranceVehicleArray[0]['totalRecords'] == 0) {
			echo DISCARD_VEHICLE_NOT_DELETE;
			die;
		}

		$busArray = $insuranceVehicleManager->checkBus($REQUEST_DATA['insuranceId']);
			$busId = $busArray[0]['busId'];
			if($busId != '') {
				$busActiveArray = $insuranceVehicleManager->checkBusActive($busId);
				if ($busActiveArray[0]['isActive'] == 1) {
					echo INSURANCE_CANNOT_DELETE_ACTIVE_BUS;
					die;
				}
				else {
					if($insuranceVehicleManager->deleteInsuredVehicle($REQUEST_DATA['insuranceId'])) {
						echo DELETE;
					}
					else {
						echo DEPENDENCY_CONSTRAINT;
					}
				}
		  }
	 }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug in fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/InsuranceVehicle
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:16p
//Created in $/Leap/Source/Library/InsuranceVehicle
//new ajax files for vehicle insurance
// 
//
?>