<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','InsuranceClaim');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$busId = $REQUEST_DATA['busId'];
    require_once(MODEL_PATH . "/InsuranceClaimManager.inc.php");
    $insuranceClaimManager = InsuranceClaimManager::getInstance();
	if ($busId != '') {
		$vehicleNCBArray = $insuranceClaimManager->getNCBValues($busId);
		if(count($vehicleNCBArray) > 0 && is_array($vehicleNCBArray)) {
			echo json_encode($vehicleNCBArray);
		}
		else {
			echo 0;
		}
	}

// $History: getNcbClaim.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:45a
//Created in $/Leap/Source/Library/InsuranceClaim
//new ajax files for vehicle insurance claim
//
//
?>