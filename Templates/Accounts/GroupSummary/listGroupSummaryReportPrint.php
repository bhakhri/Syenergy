<?php
//-------------------------------------------------------
//  This File outputs the balancesheet to printer
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();
	UtilityManager::ifCompanyNotSelected();

	require_once(BL_PATH . '/Accounts/AccountsReportManager.inc.php');
	$accountsReportsManager = AccountsReportManager::getInstance();

	$tillDate = $REQUEST_DATA['tillDate'];
	$groupId = $REQUEST_DATA['groupId'];
	$showDate = date('d-M-Y', strtotime($tillDate));

	require_once(MODEL_PATH . '/Accounts/GroupsManager.inc.php');
	$groupsManager = GroupsManager::getInstance();
	$groupDetailsArray = $groupsManager->getGroup(" AND grp.groupId = $groupId");
	$thisGroupName = $groupDetailsArray[0]['groupName'];
	$parentGroup = $groupDetailsArray[0]['parentGroup'];



	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$printLogicArray = Array();
	$groupLedgerArray = $voucherManager->getOneLevelTree($groupId, $tillDate);
	$groupArray = $groupLedgerArray['parentGroupsArray'];
	$ledgerArray = $groupLedgerArray['parentLedgerArray'];

	foreach($groupArray as $groupLedgerRecord) {
		$groupName = $groupLedgerRecord['groupName'];
		$thisGroupId = $groupLedgerRecord['groupId'];
		$groupSumArray = $voucherManager->getTreeSum($thisGroupId, $tillDate);
		$drAmount = $groupSumArray[0]['totalDrAmount'];
		$crAmount = $groupSumArray[0]['totalCrAmount'];
		if ($drAmount != $crAmount) {
			if ($drAmount > $crAmount) {
				$balance = $drAmount - $crAmount;
				$printLogicArray[$groupName] = Array('Amount' => $balance, 'Type'=>'Dr');
			}
			elseif ($crAmount > $drAmount) {
				$balance = $crAmount - $drAmount;
				$printLogicArray[$groupName] = Array('Amount' => $balance, 'Type'=>'Cr');
			}
		}
	}

	foreach($ledgerArray as $ledgerRecrod) {
		$ledgerId = $ledgerRecrod['ledgerId'];
		$ledgerName = $ledgerRecrod['ledgerName'];
		$ledgerSumArray = $voucherManager->getLedgerSum($ledgerId, $tillDate);
		$drAmount = $ledgerSumArray[0]['totalDrAmount'];
		$crAmount = $ledgerSumArray[0]['totalCrAmount'];
		if ($drAmount > $crAmount) {
			$balance = $drAmount - $crAmount;
			$printLogicArray[$ledgerName] = Array('Amount' => $balance, 'Type'=>'Dr');
		}
		elseif ($crAmount > $drAmount) {
			$balance = $crAmount - $drAmount;
			$printLogicArray[$ledgerName] = Array('Amount' => $balance, 'Type'=>'Cr');
		}
	}


    $accountsReportsManager->setReportWidth(700);
	$accountsReportsManager->setReportHeading("Group Summary - $thisGroupName");
	$accountsReportsManager->setReportInformation("Under: $parentGroup");
	$accountsReportsManager->showGroupSummary($printLogicArray);

// $History: listGroupSummaryReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/GroupSummary
//



?>