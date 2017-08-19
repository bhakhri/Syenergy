<?php
//-------------------------------------------------------
//  This File contains code for making transfer process variable to false.
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TransferInternalMarksAdvanced');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


	require_once(MODEL_PATH . "/TransferMarksManager.inc.php");
	
	if (false == TransferMarksManager::getInstance()->fetchTransferMarksManager()) {
		$transferMarksManager = TransferMarksManager::getInstance();
	}
	else {
		$transferMarksManager = TransferMarksManager::getInstance()->fetchTransferMarksManager();
	}

	$transferMarksManager->setTransferProcessRunning(false);
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);

?>