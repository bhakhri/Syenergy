<?php
//-------------------------------------------------------
//  This File contains logic for balance sheet
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	$openingBalanceArray = $voucherManager->getOpeningBalances();
	$opDrAmount = $openingBalanceArray[0]['opDrAmount'];
	$opCrAmount = $openingBalanceArray[0]['opCrAmount'];

	$diffOpDrAmount = 0;
	$diffOpCrAmount = 0;
	if ($opDrAmount > $opCrAmount) {
		$diffOpDrAmount = $opDrAmount - $opCrAmount;
	}
	elseif ($opCrAmount > $opDrAmount) {
		$diffOpCrAmount = $opCrAmount - $opDrAmount;
	}

	$liabilitiesGroupArray = $voucherManager->getChildGroups(2);
	$liabilitiesSumArray = Array();
	$printLogicArray = Array();

	$printLogicCtrL = -1;
	foreach($liabilitiesGroupArray as $liabilitiesGroupRecord) {
		$groupId = $liabilitiesGroupRecord['groupId'];
		$groupName = $liabilitiesGroupRecord['groupName'];
		$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
		$liabilitiesSumArray['totalDrAmount'] += $groupSumArray[0]['totalDrAmount'];
		$liabilitiesSumArray['totalCrAmount'] += $groupSumArray[0]['totalCrAmount'];
		$groupBalance = $groupSumArray[0]['totalCrAmount'] - $groupSumArray[0]['totalDrAmount'];
		if ($groupBalance > 0) {
			$printLogicArray[++$printLogicCtrL]['liabilities'] = Array('Type'=>'Main', 'Name'=>$groupName, 'Amount'=>$groupBalance);
		}

		$childGroupsArray = $voucherManager->getChildGroups($groupId);
		foreach($childGroupsArray as $childGroupRecord) {
			$childGroupId = $childGroupRecord['groupId'];
			$childGroupName = $childGroupRecord['groupName'];
			$childGroupSumArray = $voucherManager->getTreeSum($childGroupId, $tillDate);
			$childGroupBalance = $childGroupSumArray[0]['totalCrAmount'] - $childGroupSumArray[0]['totalDrAmount'];
			if ($childGroupBalance == 0) {
				continue;
			}
			$printLogicArray[++$printLogicCtrL]['liabilities'] = Array('Type'=>'Sub', 'Name'=>$childGroupName, 'Amount'=>$childGroupBalance);
		}
		$groupLedgerArray = $voucherManager->getGroupLedgers($groupId);
		foreach($groupLedgerArray as $groupLedgerRecord) {
			$ledgerId = $groupLedgerRecord['ledgerId'];
			$ledgerName = $groupLedgerRecord['ledgerName'];
			$ledgerSumArray = $voucherManager->getLedgerSum($ledgerId, $tillDate);
			$ledgerSumBalance = $ledgerSumArray[0]['totalCrAmount'] - $ledgerSumArray[0]['totalDrAmount'];
			if ($ledgerSumBalance == 0) {
				continue;
			}
			$printLogicArray[++$printLogicCtrL]['liabilities'] = Array('Type'=>'Sub', 'Name'=>$ledgerName, 'Amount'=>$ledgerSumBalance);
		}
	}

	$balanceLiabilities = $liabilitiesSumArray['totalCrAmount'] - $liabilitiesSumArray['totalDrAmount'];

	$assetGroupArray = $voucherManager->getChildGroups(1);
	$assetsSumArray = Array();
	$assetsGroupWithSumArray = Array();
	$printLogicCtrA = -1;
	foreach($assetGroupArray as $assetGroupRecord) {
		$groupId = $assetGroupRecord['groupId'];
		$groupName = $assetGroupRecord['groupName'];
		$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
		$assetsSumArray['totalDrAmount'] += $groupSumArray[0]['totalDrAmount'];
		$assetsSumArray['totalCrAmount'] += $groupSumArray[0]['totalCrAmount'];
		$groupBalance = $groupSumArray[0]['totalDrAmount'] - $groupSumArray[0]['totalCrAmount'];

		if ($groupBalance > 0) {
			$printLogicArray[++$printLogicCtrA]['assets'] = Array('Type'=>'Main', 'Name'=>$groupName, 'Amount'=>$groupBalance);
		}

		$childGroupsArray = $voucherManager->getChildGroups($groupId);
		foreach($childGroupsArray as $childGroupRecord) {
			$childGroupId = $childGroupRecord['groupId'];
			$childGroupName = $childGroupRecord['groupName'];
			$childGroupSumArray = $voucherManager->getTreeSum($childGroupId, $tillDate);
			$childGroupBalance = $childGroupSumArray[0]['totalDrAmount'] - $childGroupSumArray[0]['totalCrAmount'];
			if ($childGroupBalance == 0) {
				continue;
			}
			$printLogicArray[++$printLogicCtrA]['assets'] = Array('Type'=>'Sub', 'Name'=>$childGroupName, 'Amount'=>$childGroupBalance);
		}
		$groupLedgerArray = $voucherManager->getGroupLedgers($groupId);
		foreach($groupLedgerArray as $groupLedgerRecord) {
			$ledgerId = $groupLedgerRecord['ledgerId'];
			$ledgerName = $groupLedgerRecord['ledgerName'];
			$ledgerSumArray = $voucherManager->getLedgerSum($ledgerId, $tillDate);
			$ledgerSumBalance = $ledgerSumArray[0]['totalDrAmount'] - $ledgerSumArray[0]['totalCrAmount'];
			if ($ledgerSumBalance == 0) {
				continue;
			}
			$printLogicArray[++$printLogicCtrA]['assets'] = Array('Type'=>'Sub', 'Name'=>$ledgerName, 'Amount'=>$ledgerSumBalance);
		}
	}

	$balanceAssets = $assetsSumArray['totalDrAmount'] - $assetsSumArray['totalCrAmount'];
	$profitLoss = '';

	$totalAssets = 0;
	$totalLiabilities = 0;


	if ($diffOpCrAmount > 0) {
		$totalAssets += $diffOpCrAmount;
	}
	elseif ($diffOpDrAmount > 0) {
		$totalLiabilities += $diffOpDrAmount;
	}

	$totalLiabilities += $balanceLiabilities;
	$totalAssets += $balanceAssets;

	$profitLossAmount = 0;

	if ($balanceLiabilities > $balanceAssets) {
		$profitLoss = 'Loss';
		$profitLossAmount = $totalLiabilities - $totalAssets;
		$totalAssets += $profitLossAmount;
		$printLogicArray[++$printLogicCtrA]['assets'] = Array('Type'=>'Main', 'Name'=>'Profit & Loss A/C', 'Amount'=>$profitLossAmount);
	}
	elseif ($balanceAssets > $balanceLiabilities) {
		$profitLoss = 'Profit';
		$profitLossAmount = $totalAssets - $totalLiabilities;
		$totalLiabilities += $profitLossAmount;
		$printLogicArray[++$printLogicCtrL]['liabilities'] = Array('Type'=>'Main', 'Name'=>'Profit & Loss A/C', 'Amount'=>$profitLossAmount);
	}
	if ($diffOpDrAmount > 0) {
		$printLogicArray[++$printLogicCtrL]['liabilities'] = Array('Type'=>'Main', 'Name'=>'Diff. in Op. Balances', 'Amount'=>$diffOpDrAmount);
	}
	elseif ($diffOpCrAmount > 0) {
		$printLogicArray[++$printLogicCtrA]['assets'] = Array('Type'=>'Main', 'Name'=>'Diff. in Op. Balances', 'Amount'=>$diffOpCrAmount);
	}

// $History: initBalanceSheetLogic.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/BalanceSheet
//file added
//




?>