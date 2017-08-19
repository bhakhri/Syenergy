<?php
//-------------------------------------------------------
// Purpose: To delete fee cycle detail
// Author : Nishu Bindal
// Created on : (14.03.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','FeeLedger');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	$errorMessage = '';
	require_once(MODEL_PATH . "/Fee/FeeLedgerManager.inc.php");   
	$feeLedgerManager = FeeLedgerManager::getInstance();

global $sessionHandler;
$errorMessage =''; 

	if (!isset($REQUEST_DATA['feeLedgerDebitCreditId']) || trim($REQUEST_DATA['feeLedgerDebitCreditId']) == '') {
		$errorMessage = 'Invalid Fee Ledger';
	}
	

	if(trim($errorMessage) == ''){
		if ($feeLedgerManager->deleteFeeLedger($REQUEST_DATA['feeLedgerDebitCreditId'])) {
			echo DELETE;
		}
	}
	else {
		echo $errorMessage;
	}
   
    

?>

