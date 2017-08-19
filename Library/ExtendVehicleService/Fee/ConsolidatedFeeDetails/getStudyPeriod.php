<?php
//-------------------------------------------------------
// This File is used for fetching Study Periods
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    	UtilityManager::headerNoCache(); 
	$instituteId = $REQUEST_DATA['instituteId'];
	if($instituteId !='all'){
		$condition = " AND c.instituteId = '$instituteId'";
	}
      	require_once(MODEL_PATH . "/Fee/ConsolidatedFeeDetailsManager.inc.php");
    	$ConsolidatedFeeDetailsManager = ConsolidatedFeeDetailsManager::getInstance();
	
	$studyPeriodArray = $ConsolidatedFeeDetailsManager->fetchStudyPeriod($condition);
	if(count($studyPeriodArray) > 0 && is_array($studyPeriodArray)){
		echo json_encode($studyPeriodArray);
	}
	else{
		echo 0;
	}
	
?>
