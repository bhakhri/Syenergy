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

	require_once(BL_PATH . '/Accounts/AccountsReportManager.inc.php');
	$accountsReportsManager = AccountsReportManager::getInstance();

	$tillDate = $REQUEST_DATA['tillDate'];
	$groupLedger = $REQUEST_DATA['groupLedger'];
	$showDate = date('d-M-Y', strtotime($tillDate));

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

    $accountsReportsManager->setReportWidth(700);
    
    $accountsReportsManager->setReportInformation("As on $showDate");

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

	if ($groupLedger == 'group') {
		$printLogicArray = Array();
		$mainChildrenGroupArray = $voucherManager->getMainChildrenGroups();
		foreach($mainChildrenGroupArray as $childGroupRecord) {
			$groupId = $childGroupRecord['groupId'];
			$groupName = $childGroupRecord['groupName'];
			$tbRecordArray = $voucherManager->getTreeSum($groupId, $tillDate);
			$drAmount = $tbRecordArray[0]['totalDrAmount'];
			$crAmount = $tbRecordArray[0]['totalCrAmount'];
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
		if ($diffOpDrAmount > 0) {
			$printLogicArray['Diff. in Opening Balances'] = Array('Amount' => $diffOpDrAmount, 'Type'=>'Cr');
		}
		elseif ($diffOpDrAmount > 0) {
			$printLogicArray['Diff. in Opening Balances'] = Array('Amount' => $diffOpCrAmount, 'Type'=>'Dr');
		}
		$accountsReportsManager->setReportHeading("Trial Balance-GroupWise");
		$accountsReportsManager->showTrialBalanceGroupWise($printLogicArray);
	}
	elseif ($groupLedger == 'ledger') {
		$printLogicArray = Array();
		$ledgerSumArray = $voucherManager->getEachLedgerSum($tillDate);
		foreach($ledgerSumArray as $ledgerSumRecord) {
			$ledgerId = $ledgerSumRecord['ledgerId'];
			$ledgerName = $ledgerSumRecord['ledgerName'];
			$drAmount = $ledgerSumRecord['totalDrAmount'];
			$crAmount = $ledgerSumRecord['totalCrAmount'];
			if ($drAmount != $crAmount) {
				if ($drAmount > $crAmount) {
					$balance = $drAmount - $crAmount;
					$printLogicArray[$ledgerName] = Array('Amount' => $balance, 'Type'=>'Dr');
				}
				elseif ($crAmount > $drAmount) {
					$balance = $crAmount - $drAmount;
					$printLogicArray[$ledgerName] = Array('Amount' => $balance, 'Type'=>'Cr');
				}
			}
		}
		if ($diffOpDrAmount > 0) {
			$printLogicArray['Diff. in Opening Balances'] = Array('Amount' => $diffOpDrAmount, 'Type'=>'Cr');
		}
		elseif ($diffOpDrAmount > 0) {
			$printLogicArray['Diff. in Opening Balances'] = Array('Amount' => $diffOpCrAmount, 'Type'=>'Dr');
		}
		$accountsReportsManager->setReportHeading("Trial Balance-LedgerWise");
		$accountsReportsManager->showTrialBalanceLedgerWise($printLogicArray);
	}

?>
<?php
// $History: listTrialBalanceReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/TrialBalance
//



?>