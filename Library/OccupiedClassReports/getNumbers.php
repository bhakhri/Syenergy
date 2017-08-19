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
	define('MODULE','OccupiedFreeClass');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$periodSlotId = $REQUEST_DATA['periodSlotId'];
    require_once(MODEL_PATH . "/OccupiedClassManager.inc.php");
    $occupiedClassManager = OccupiedClassManager::getInstance();
	if ($periodSlotId != '') {
		$periodArray = $occupiedClassManager->getPeriods($periodSlotId);
		if(count($periodArray) > 0 && is_array($periodArray)) {
			echo json_encode($periodArray);
		}
		else {
			echo 0;
		}
	}

// $History: getNumbers.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:17p
//Created in $/LeapCC/Library/OccupiedClassReports
//new ajax files for occupied/free class reports
//
?>