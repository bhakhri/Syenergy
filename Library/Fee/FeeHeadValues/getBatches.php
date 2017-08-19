<?php
//-------------------------------------------------------
//  This File is used for fetching Batches
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    	UtilityManager::headerNoCache(); 
	$branchId = $REQUEST_DATA['branchId'];
	$degreeId = $REQUEST_DATA['degreeId'];
	$condition = '';
	require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");   
	$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
	if ($degreeId != '' && $branchId != '') {
		$condition = " AND c.degreeId = '$degreeId'";
		if($branchId != 'all'){
			$condition .= " AND c.branchId = '$branchId'";
		}
		$branchArray = $feeHeadValuesManager->fetchAllBatches($condition);
		
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
