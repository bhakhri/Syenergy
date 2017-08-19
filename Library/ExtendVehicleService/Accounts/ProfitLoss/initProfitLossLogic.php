<?php
//-------------------------------------------------------
//  This File contains logic for profit loss
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	$expenditureGroupArray = $voucherManager->getChildGroups(4);
	$expenditureSumArray = Array();
	$printLogicArray = Array();
	$exCtr = 0;
	$printLogicCtrE = -1;
	foreach($expenditureGroupArray as $expenditureGroupRecord) {
		$groupId = $expenditureGroupRecord['groupId'];
		$groupName = $expenditureGroupRecord['groupName'];
		$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
		$expenditureSumArray['totalDrAmount'] += $groupSumArray[0]['totalDrAmount'];
		$expenditureSumArray['totalCrAmount'] += $groupSumArray[0]['totalCrAmount'];
		$groupBalance = $groupSumArray[0]['totalDrAmount'] - $groupSumArray[0]['totalCrAmount'];
		if ($groupBalance > 0) {
			$exCtr++;
			$printLogicArray[++$printLogicCtrE]['expenses'] = Array('Type'=>'Main', 'Name'=>$groupName, 'Amount'=>$groupBalance);
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
			$printLogicArray[++$printLogicCtrE]['expenses'] = Array('Type'=>'Sub', 'Name'=>$childGroupName, 'Amount'=>$childGroupBalance);
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
			$printLogicArray[++$printLogicCtrE]['expenses'] = Array('Type'=>'Sub', 'Name'=>$ledgerName, 'Amount'=>$ledgerSumBalance);
		}
	}

	$balanceExpenditure = $expenditureSumArray['totalDrAmount'] - $expenditureSumArray['totalCrAmount'];

	$incomeGroupArray = $voucherManager->getChildGroups(3);
	$incomeSumArray = Array();
	$incCtr = 0;
	$printLogicCtrI = -1;
	foreach($incomeGroupArray as $incomeGroupRecord) {
		$groupId = $incomeGroupRecord['groupId'];
		$groupName = $incomeGroupRecord['groupName'];
		$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
		$incomeSumArray['totalDrAmount'] += $groupSumArray[0]['totalDrAmount'];
		$incomeSumArray['totalCrAmount'] += $groupSumArray[0]['totalCrAmount'];
		$groupBalance = $groupSumArray[0]['totalCrAmount'] - $groupSumArray[0]['totalDrAmount'];
		if ($groupBalance > 0) {
			$incCtr++;
			$printLogicArray[++$printLogicCtrI]['incomes'] = Array('Type'=>'Main', 'Name'=>$groupName, 'Amount'=>$groupBalance);
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
			$printLogicArray[++$printLogicCtrI]['incomes'] = Array('Type'=>'Sub', 'Name'=>$childGroupName, 'Amount'=>$childGroupBalance);
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
			$printLogicArray[++$printLogicCtrI]['incomes'] = Array('Type'=>'Sub', 'Name'=>$ledgerName, 'Amount'=>$ledgerSumBalance);
		}
	}

	$balanceIncomes = $incomeSumArray['totalCrAmount'] - $incomeSumArray['totalDrAmount'];



	$totalInDirectExpenses = 0; 
	$totalInDirectIncomes = 0;

	$netProfitLoss = '';
	$totalExpenses = $balanceExpenditure;//$balanceDirectExpenses + $balanceInDirectExpenses;
	$totalIncomes = $balanceIncomes;//$balanceDirectIncomes + $balanceInDirectIncomes;

	if ($totalIncomes > $totalExpenses) {
		$exCtr++;
		$netProfitLoss = 'Nett Profit';
		$netProfitLossAmount = $totalIncomes - $totalExpenses;
		$totalExpenses +=  $netProfitLossAmount;
		$printLogicArray[++$printLogicCtrE]['expenses'] = Array('Type'=>'Main', 'Name'=>'Nett Profit', 'Amount'=>$netProfitLossAmount);
	}
	elseif ($totalExpenses > $totalIncomes) {
		$incCtr++;
		$netProfitLoss = 'Nett Loss';
		$netProfitLossAmount = $totalExpenses - $totalIncomes;
		$totalIncomes +=   $netProfitLossAmount;
		$printLogicArray[++$printLogicCtrI]['incomes'] = Array('Type'=>'Main', 'Name'=>'Nett Loss', 'Amount'=>$netProfitLossAmount);
	}

// $History: initProfitLossLogic.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/ProfitLoss
//file added
//



?>