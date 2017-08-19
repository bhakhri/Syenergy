<?php
//-------------------------------------------------------
//  This File outputs the balancesheet to printer
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
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();
	UtilityManager::ifCompanyNotSelected();

	$tillDate = $REQUEST_DATA['tillDate'];
	$groupId = 6;//For Cash
	$showDate = date('d-M-Y', strtotime($tillDate));

	
	require_once(BL_PATH . '/Accounts/AccountsReportManager.inc.php');
	$accountsReportsManager = AccountsReportManager::getInstance();

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$groupLedgerArray = $voucherManager->getOneLevelTree($groupId, $tillDate);
	$groupArray = $groupLedgerArray['parentGroupsArray'];
	$ledgerArray = $groupLedgerArray['parentLedgerArray'];

	$printLogicArray = array();
	foreach($groupArray as $groupLedgerRecord) {
		$groupName = $groupLedgerRecord['groupName'];
		$thisGroupId = $groupLedgerRecord['groupId'];
		$groupSumArray = $voucherManager->getTreeSum($thisGroupId, $tillDate);
		$drAmount = $groupSumArray[0]['totalDrAmount'];
		$crAmount = $groupSumArray[0]['totalCrAmount'];
		if ($drAmount > $crAmount) {
			$balance = $drAmount - $crAmount;
			$printLogicArray[$groupName] = Array('Amount'=>$balance, 'Type'=>'Dr');
		}
		elseif ($crAmount > $drAmount) {
			$balance = $crAmount - $drAmount;
			$printLogicArray[$groupName] = Array('Amount'=>$balance, 'Type'=>'Cr');
		}
	}

    $accountsReportsManager->setReportWidth(700);
    $accountsReportsManager->setReportInformation("As on $showDate");

	$accountsReportsManager->setReportHeading("Cash Book");
	$accountsReportsManager->showGroupSummary($printLogicArray);

// $History: listCashBookReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/CashBook
//



?>