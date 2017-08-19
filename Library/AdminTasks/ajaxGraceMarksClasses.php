<?php
//-------------------------------------------------------
//  This File is used for fetching classes for a time label
// Author :Ajinder Singh
// Created on : 28-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
	$adminTaskManager = AdminTasksManager::getInstance();
	define('MODULE','GraceMarks');
	define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = $REQUEST_DATA['labelId'];
	$classArray = $adminTaskManager->getClasses($labelId);
	echo json_encode($classArray);
?>