<?php
//-------------------------------------------------------
//  This File contains logic for ledgers
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Ledger');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::ifCompanyNotSelected();
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['ledgerId'] ) != '') {

	require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
	$ledgerManager = LedgerManager::getInstance();

	$ledgerArray = $ledgerManager->getLedger(' AND led.ledgerId='.$REQUEST_DATA['ledgerId']);

	if(is_array($ledgerArray) && count($ledgerArray)>0 ) {  
		echo json_encode($ledgerArray[0]);
	}
	else {
		echo 0;
	}
	
}

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/Ledger
//file added
//



?>