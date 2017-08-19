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
	$instituteId = $REQUEST_DATA['instituteId'];
	$isActive = $REQUEST_DATA['isActive'];
	$condition = '';

   require_once(MODEL_PATH . "/Fee/FineSetUpManager.inc.php");   
$fineSetUpManager = FineSetUpManager::getInstance();  
   
	if($degreeId==''){
	$degreeId='All';
	}
	if($branchId==''){
	$branchId='All';
	}
	if($batchId==''){
	$batchId='All';
	}
	if($instituteId==''){
	$instituteId='All';
	}



             if($isActive != ''){
		$condition = " WHERE c.isActive = '$isActive'";
		}

	if(($degreeId !='' && $branchId != '') && $batchId != ''){
		if($instituteId != 'All' ){
		$condition .= "AND c.instituteId = '".$sessionHandler->getSessionVariable('InstituteId')."'";
		}

		if($degreeId != 'All' ){
		$condition .= "AND c.degreeId = '$degreeId' ";
		}
		
		if($branchId != 'All'){
			$condition .=" AND c.branchId = '$branchId'";
		}
		if($batchId != 'All'){
			$condition .=" AND c.batchId = '$batchId'";
		}

		
		$classArray = $fineSetUpManager->fetchClases($condition);
		
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
