<?php
//-------------------------------------------------------
//  This File is used for fetching All class of All Time Tables 
//
//
// Author :Jaineesh
// Created on : 07.07.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = $REQUEST_DATA['labelId'];
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();
	
	if($labelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($conditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$conditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
	}
	else {
		$conditions = " AND ttc.timeTableLabelId = $labelId";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
	}
	echo json_encode($classArray);

// $History:  $
//
//
?>