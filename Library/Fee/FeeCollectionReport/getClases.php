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
	define('MODULE','FeeCollectionReport');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$degreeId = $REQUEST_DATA['degreeId'];
	$branchId = $REQUEST_DATA['branchId'];
	$batchId = $REQUEST_DATA['batchId'];
	$condition = '';
    require_once(MODEL_PATH . "/Fee/FeeCollectionReportManager.inc.php");
    $FeeCollectionReportManager = FeeCollectionReportManager::getInstance();
	if(($degreeId !='' && $branchId != '') && $batchId != ''){
		
		$condition = "WHERE c.degreeId = '$degreeId'";
		if($branchId != 'all'){
			$condition .=" AND c.branchId = '$branchId'";
		}
		if($batchId != 'all'){
			$condition .=" AND c.batchId = '$batchId'";
		}
		$classArray = $FeeCollectionReportManager->fetchClases($condition);
		
		if(count($classArray) > 0 && is_array($classArray)) {
			echo json_encode($classArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
