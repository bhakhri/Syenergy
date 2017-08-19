<?php
//-------------------------------------------------------
//  This File is used for fetching Study Period
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelFeeMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$batchId = $REQUEST_DATA['batchId'];
	$condition = '';
    require_once(MODEL_PATH . "/Fee/HostelFeeManager.inc.php");
    $HostelFeeManager = HostelFeeManager::getInstance();
	if($batchId != ''){
		
		$classArray = $HostelFeeManager->fetchStudyPeriod($batchId);
		
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
