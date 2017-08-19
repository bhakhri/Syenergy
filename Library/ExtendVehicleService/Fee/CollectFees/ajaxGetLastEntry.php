<?php
//--------------------------------------------------------
//The file contains data base functions for marks
//
// Author :Nishu Bindal
// Created on : 10.May.12
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CollectFeesNew');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/Fee/CollectFeesManager.inc.php");   
	$CollectFeesManager = CollectFeesManager::getInstance();

	$foundArray = $CollectFeesManager->getLastEntry();
	$lastEntry = NOT_APPLICABLE_STRING;
	$receiptDate = date('Y-m-d');
	$paidAt = '';

	if(is_array($foundArray) && count($foundArray)>0 ) {
		$lastEntry = $foundArray[0]['receiptNo'].' ('.UtilityManager::formatDate($foundArray[0]['receiptDate']).")";
		$receiptDate = $foundArray[0]['receiptDated'];
		$paidAt =   $foundArray[0]['paidAt'];
        $rr = $foundArray[0]['receiptNo'];
	}
	echo $lastEntry.'!~~!'.$receiptDate.'!~~!'.$paidAt.'!~~!'.$rr;
	die;
?> 
