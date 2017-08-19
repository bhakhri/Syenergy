<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE INSURANCE LIST
//
// Author : Jaineesh
// Created on : (26.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceVehicle');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['insuranceId'] ) != '') {
    require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
	$insuranceVehicleManager = InsuranceVehicleManager::getInstance();
    $foundArray = $insuranceVehicleManager->getVehicleInsurance(' AND insuranceId="'.$REQUEST_DATA['insuranceId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
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
//
?>