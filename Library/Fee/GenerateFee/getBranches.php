<?php
//-------------------------------------------------------
//  This File is used for fetching Branches
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','GenerateStudentFees');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$degreeId = $REQUEST_DATA['degreeId'];
	
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance();
	if ($degreeId != '') {
		$branchArray = $generateFeeManager->fetchAllBranches($degreeId);
		
		if(count($branchArray) > 0 && is_array($branchArray)) {
			echo json_encode($branchArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
