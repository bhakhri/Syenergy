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
define('MODULE','TyreRetreading');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['tyreNo']) || trim($REQUEST_DATA['tyreNo']) == '') {
        $errorMessage .= ENTER_TYRE_NO."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['reading']) || trim($REQUEST_DATA['reading']) == '')) {
        $errorMessage .= ENTER_READING."\n"; 
    }

	$retreadingDate = $REQUEST_DATA['retreadingDate'];
	$curDate = explode('-',date('Y-m-d'));
	$cur_date  =gregoriantojd($curDate[1], $curDate[2], $curDate[0]);

	$vehicleRetreadingDate = explode('-',$retreadingDate);
	$retreading_date  = gregoriantojd($vehicleRetreadingDate[1], $vehicleRetreadingDate[2], $vehicleRetreadingDate[0]);

	$diff = $cur_date - $retreading_date;   //purchase date cannot be greater than current date

	if($diff < 0 ) {
		echo RETREADING_DATE_NOT_GREATER;
		die;
	}

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
		$tyreRetreadingManager = TyreRetreadingManager::getInstance();

			$retreadingId = $REQUEST_DATA['retreadingId'];
			$tyreNo = $REQUEST_DATA['tyreNo'];
			$foundArray = $tyreRetreadingManager->getTyreHistoryBus($tyreNo);
			$tyreId = $foundArray[0]['tyreId'];
			$busId = $foundArray[0]['busId'];

			
			if ($tyreId != '' && $busId != '') {
			if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$returnStatus1 = $tyreRetreadingManager->updateTyreRetreading($tyreId,$busId);
				if($returnStatus1 === false) {
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
		 echo VEHICLE_TYRE_NOT_EXIST;
		 die;
	  }
    else {
        echo $errorMessage;
    }


// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/TyreRetreading
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
?>
