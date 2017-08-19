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
	$mode = add_slashes(trim($REQUEST_DATA['mode']));

	$accessMode = strtolower($mode);
	define('MODULE','Ledgers');
	define('ACCESS',$accessMode);
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	$ledgerName		=	add_slashes(trim($REQUEST_DATA['ledgerName']));
	$parentGroup	=	add_slashes(trim($REQUEST_DATA['parentGroup']));
	$openingBalance =	add_slashes(trim($REQUEST_DATA['openingBalance']));
	$drcr			=	add_slashes(trim($REQUEST_DATA['drcr']));
	$ledgerId		=	$REQUEST_DATA['ledgerId'];

	require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
	$ledgerManager = LedgerManager::getInstance();

	if ($mode == 'Delete') {

		echo 'Ledger Deletion Stopped';
		die;
		
		require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
		$voucherManager = VoucherManager::getInstance();

		$voucherArray = $voucherManager->countLedgerVouchers($ledgerId);
		$cnt = $voucherArray[0]['cnt'];

		if ($cnt > 0) {
			echo VOUCHERS_ENTERED_FOR_THIS_LEDGER;
			die;
		}


		$returnStatus = $ledgerManager->deleteLedger($ledgerId);
		if($returnStatus === false) {
			echo FAILURE;
			die;
		}
		else {
			echo DELETE;
			die;
		}
	}
	else {
		if (empty($ledgerName) or $ledgerName == '') {
			echo ENTER_LEDGER_NAME;
			die;
		}
		elseif (empty($parentGroup) or $parentGroup == '') {
			echo ENTER_PARENT_GROUP_NAME;
			die;
		}
		elseif ((!empty($openingBalance) and !is_numeric($openingBalance)) or $openingBalance < 0) {
			echo ENTER_VALID_OPENING_BALANCE;
			die;
		}

		$ledgerName = ucwords($ledgerName);

		if ($mode == 'Add') {
			$countLedgerNameArray = $ledgerManager->countLedgerName($ledgerName);
		}
		elseif ($mode == 'Edit') {
			$countLedgerNameArray = $ledgerManager->countLedgerName($ledgerName, $ledgerId);
		}

		$cnt = $countLedgerNameArray[0]['cnt'];
		if ($cnt) {
			echo LEDGER_NAME_ALREADY_EXISTS;
			die;
		}

		$parentGroup = ucwords($parentGroup);
		$parentGroupIdArray = $ledgerManager->getGroupId($parentGroup);
		$parentGroupId = $parentGroupIdArray[0]['groupId'];
		if (empty($parentGroupId)) {
			echo INVALID_PARENT_GROUP_ENTERED;
			die;
		}

		$opBalance = ",opDrAmount = 0, opCrAmount = 0";
		if ($openingBalance) {
			if ($drcr == 'dr') {
				$opBalance = ",opDrAmount = $openingBalance, opCrAmount = 0";
			}
			elseif ($drcr == 'cr') {
				$opBalance = ",opDrAmount = 0, opCrAmount = $openingBalance";
			}
		}

		if ($mode == 'Add') {
			$returnStatus = $ledgerManager->addLedger($ledgerName, $parentGroupId, $opBalance);
		}
		elseif ($mode == 'Edit') {
			$returnStatus = $ledgerManager->editLedger($ledgerId, $ledgerName, $parentGroupId, $opBalance);
		}

		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	


// $History: ajaxInitAction.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:47p
//Updated in $/LeapCC/Library/Accounts/Ledger
//stopped deletion.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/Ledger
//file added
//



?>