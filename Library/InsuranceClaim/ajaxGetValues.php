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


if(trim($REQUEST_DATA['claimId'] ) != '') {
    require_once(MODEL_PATH . "/InsuranceClaimManager.inc.php");
	$insuranceVehicleManager = InsuranceClaimManager::getInstance();
    $foundArray = $insuranceVehicleManager->getVehicleInsuranceClaim(' AND vic.claimId="'.$REQUEST_DATA['claimId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Library/InsuranceClaim
//new ajax files for vehicle insurance claim
//
//
?>