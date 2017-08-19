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
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '')) {
        $errorMessage .= SELECT_BUS. '<br/>';
    }
    
	if (trim($errorMessage) == '') {
		$busId = $REQUEST_DATA['busNo'];
		$extendedServiceValue = $REQUEST_DATA['extendedServices'];

		require_once(MODEL_PATH . "/ExtendVehicleServiceManager.inc.php");
		$extendVehicleServiceManager = ExtendVehicleServiceManager::getInstance();
		
		$vehicleServiceArray = $extendVehicleServiceManager->getVehicleFreeService($busId);
		$totalService = $vehicleServiceArray[0]['countRecords'];
		$x = $totalService;

		$oldServiceKm = 0;
		$oldServiceDate = '';
		$serviceKMArray = array();

		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if($x > 0 ) {
				if ($extendedServiceValue > 0 ) {
					for($i=0;$i<$extendedServiceValue;$i++) {
						$x = $x+1;
						$serviceNo = 'Free service no.'.$x;
						//$serviceDueDate = 'serviceDate_'.$x;
						$serviceDate = $REQUEST_DATA['serviceDate_'.$x];
						//$serviceDueKM = 'kmRun_'.$x;
						$serviceKM = $REQUEST_DATA['kmRun_'.$x];
						//echo($serviceKM);
						//die('here'.__LINE__);

						if ($oldServiceDate != '') {
							if ($serviceDate <= $oldServiceDate) {
								echo INVALID_DATE_NOT_SMALLER.' at Free service no.'.$x;
								die;
							}
						}
						if ($oldServiceKm != 0) {
							if ($serviceKM < $oldServiceKm) {
								echo INVALID_KM_NOT_SMALLER.' at Free service no.'.$x;
								die;
							}
						}

						if ($serviceKM == '') {
							echo ENTER_SERVICE_KM.' at Free service no.'.$x;
							die;
						}
						if ($serviceKM != '') {
							if ($serviceKM == '0' || $serviceKM < 1) {
								echo INVALID_SERVICE_KM.' at Free service no.'.$x;
								die;
							}
							if (!is_numeric($serviceKM) or (intval($serviceKM) != floatval($serviceKM)) or ($serviceKM < 1)) {
								echo INVALID_SERVICE_KM.' at Free service no.'.$x;
								die;
							}
							if (in_array($serviceKM, $serviceKMArray)) {
								echo SERVICE_KM_ALREADY_EXISTS.' at Free service no.'.$x;;
								die;
							}
							else {
								$serviceKMArray[] = $serviceKM;
							}
						}
						
						$serviceType = 1;
						
						$returnStatus = $extendVehicleServiceManager->addExtendVehicleFreeService($busId,$serviceType,$serviceNo,$serviceDate,$serviceKM);
						if($returnStatus === false) {
							echo FAILURE;
							die;
						}
						$oldServiceDate = $serviceDate;
						$oldServiceKm = $serviceKM;
					}

					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
						echo SUCCESS;
						die;
					 }
					else {
						echo FAILURE;
					}
					//die('i m here');
				}
			}
		}
		else {
		  echo FAILURE;
		  die;
	   }
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:52p
//Created in $/Leap/Source/Library/ExtendVehicleService
//new files to add extend vehicle service
//
//
?>