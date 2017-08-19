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
	$degreeId = $REQUEST_DATA['degreeId'];
	$branchId = $REQUEST_DATA['branchId'];
	$batchId = $REQUEST_DATA['batchId'];
	$isActive = $REQUEST_DATA['isActive'];
	$condition = '';
    require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");
    $FeeHeadValueManager = FeeHeadValuesManager::getInstance();
	if(($degreeId !='' && $branchId != '') && $batchId != ''){
		
		$condition = "WHERE c.degreeId = '$degreeId' AND c.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'";
		if($branchId != 'all'){
			$condition .=" AND c.branchId = '$branchId'";
		}
		if($batchId != 'all'){
			$condition .=" AND c.batchId = '$batchId'";
		}
		$classArray = $FeeHeadValueManager->fetchClases($condition);
		
		if(count($classArray) > 0 && is_array($classArray)) {
			echo json_encode($classArray);
		}
		else {
			echo 0;
		}
	}
	else{		
		$condition= " AND c.isActive IN ($isActive)";
		$classArray = $FeeHeadValueManager->fetchFeeClases($condition);
		
		if(count($classArray) > 0 && is_array($classArray)) {
			echo json_encode($classArray);
		}
		else {
			echo 0;
		}
	   
	}
?>
