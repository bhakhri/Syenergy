<?php
//--------------------------------------------------------
//The file contains data base functions for marks
//
// Author :Nishu Bindal
// Created on : 10.May.12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/FineManager.inc.php");   
	$fineManager = FineManager::getInstance();

	$foundArray = $fineManager->getLastEntry();
	$lastEntry = NOT_APPLICABLE_STRING;
	$receiptDate = date('Y-m-d');
	$paidAt = '';

	if(is_array($foundArray) && count($foundArray)>0 ) {
		$lastEntry = $foundArray[0]['fineReceiptNo'].' ('.UtilityManager::formatDate($foundArray[0]['receiptDate']).")";
		$receiptDate = $foundArray[0]['receiptDated'];
        $rr = $foundArray[0]['fineReceiptNo'];
	}
	echo $lastEntry.'!~~!'.$receiptDate.'!~~!'.$rr;
	die;
?> 
