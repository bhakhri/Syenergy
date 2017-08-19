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
    
    require_once(MODEL_PATH . "/Fee/HostelFeeManager.inc.php");
    $HostelFeeManager = HostelFeeManager::getInstance();
    
	$hostelIdList = $REQUEST_DATA['hostelIdList'];
	$condition = '';
    if($hostelIdList != '' ){
		$roomArray = $HostelFeeManager->fetchHostelRoomTypes($hostelIdList);
		if(count($roomArray) > 0 && is_array($roomArray)) {
			echo json_encode($roomArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
