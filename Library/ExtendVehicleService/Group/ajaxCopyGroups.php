<?php
//----------------------------------------------------------------
// THIS FILE IS USED TO copy groups from one class to another class
// Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','GroupCopy');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(MODEL_PATH . "/GroupManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $groupManager = GroupManager::getInstance();

	$oldClassId = trim($REQUEST_DATA['sourceClassId']);
	$newClassId = trim($REQUEST_DATA['targetClassId']);

	$callGroupCopyCodeFile = true;

	 //starting transaction
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {

		require_once(BL_PATH . '/Group/ajaxCopyGroupsCode.php');

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
			die;
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
		echo FAILURE;
		die;
	}





?>