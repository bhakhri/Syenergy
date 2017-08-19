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
	define('MODULE','TyreRetreading');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$tyreNo = $REQUEST_DATA['tyreNo'];
    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
	if ($tyreNo != '') {
		$tyreRetreadingArray = $tyreRetreadingManager->getTyreHistoryBus($tyreNo);
		/*$tyreBusNo = $tyreRetreadingArray[0]['busNo'];
		$busId = $tyreHistoryArray[0]['busId'];
		$otherBusesArray = $tyreHistoryManager->getAllOtherBuses($busId);
		$mainArray = array('thisBusNo'=>$tyreBusNo, 'otherBusesArray'=>$otherBusesArray);*/
		if(count($tyreRetreadingArray) > 0 && is_array($tyreRetreadingArray)) {
			echo json_encode($tyreRetreadingArray);
		}
		else {
			echo 0;
		}
	}

// $History: getTyreRetreading.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>