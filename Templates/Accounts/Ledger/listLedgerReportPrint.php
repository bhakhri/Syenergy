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


	$tillDate = $REQUEST_DATA['tillDate'];
	$ledgerId = $REQUEST_DATA['ledgerId'];

	$showDate = date('d-M-Y', strtotime($tillDate));

	require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
	$ledgerManager = LedgerManager::getInstance();


	$ledgerNameArray = $ledgerManager->getLedgerName($ledgerId);
	$thisLedgerName = $ledgerNameArray[0]['ledgerName'];

	require_once(MODEL_PATH . '/Accounts/CompanyManager.inc.php');
	$companyManager = CompanyManager::getInstance();

	$companyId = $sessionHandler->getSessionVariable('CompanyId');
	$companyFYearFromArray = $companyManager->getCompanyFYearFrom($companyId);
	$companyFYearFrom = $companyFYearFromArray[0]['fyearFrom'];
	$companyFYearFrom = date('d-M-y', strtotime($companyFYearFrom));


	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$ledgerOpeningBalanceArray = $voucherManager->getMultiLedgerOpeningBalance($ledgerId);


	$ledgerName = $ledgerOpeningBalanceArray[0]['ledgerName'];
	$opDrAmount = $ledgerOpeningBalanceArray[0]['opDrAmount'];
	$opCrAmount = $ledgerOpeningBalanceArray[0]['opCrAmount'];

	$ledgerCounter = -1;

	$totalDrAmount = 0;
	$totalCrAmount = 0;

	if ($opDrAmount != $opCrAmount) {

		if ($opDrAmount > $opCrAmount) {
			$totalDrAmount += $opDrAmount - $opCrAmount;
			$printLogicArray[++$ledgerCounter]['StartOpeningBalance'] = Array('voucherDate'=>$companyFYearFrom,'drcr'=>'Cr', 'ledgerName'=>	'Opening Balance', 'voucherType'=>'','drAmount'=>	$totalDrAmount, 'crAmount'=>'');
		}
		elseif ($opCrAmount > $opDrAmount) {
			$totalCrAmount += $opCrAmount - $opDrAmount;
			$printLogicArray[++$ledgerCounter]['StartOpeningBalance'] = Array('voucherDate'=>$companyFYearFrom,'drcr'=>'Dr', 'ledgerName'=>	'Opening Balance', 'voucherType'=>'', 'drAmount'=>'', 'crAmount'=>$totalCrAmount);
		}
	}

	$ledgerVouchersArray = $voucherManager->getLedgerVoucherDetails($ledgerId, $tillDate);

	$voucherDateArray = Array();
	
	$currentDate = '';
	$drcr = '';
	$oppDrCr = '';
	$loopCounter = 0;
	foreach($ledgerVouchersArray as $ledgerVoucherRecord) {
		
		$voucherId = $ledgerVoucherRecord['voucherId'];
		$voucherDate = UtilityManager::formatDate($ledgerVoucherRecord['voucherDate']);
		$voucherType = $voucherTypeArray[$ledgerVoucherRecord['voucherTypeId']];
		$voucherNo = $ledgerVoucherRecord['voucherNo'];
		$ledgerName = $ledgerVoucherRecord['ledgerName'];
		$crAmount = $ledgerVoucherRecord['crAmount'];
		$drAmount = $ledgerVoucherRecord['drAmount'];
		$narration = $ledgerVoucherRecord['narration'];

		if ($currentDate != $voucherDate and $loopCounter > 0) {
			if ($totalDrAmount > $totalCrAmount) {
				$balance = $totalDrAmount - $totalCrAmount;
				$printLogicArray[++$ledgerCounter]['Total'] = Array('voucherDate'=>$currentDate,'drcr'=>$drcr, 'ledgerName'=>'Total', 'voucherType'=>'','drAmount'=>$balance, 'crAmount'=>'');
				$printLogicArray[++$ledgerCounter]['ClosingBalance'] = Array('voucherDate'=>$currentDate,'drcr'=>$oppDrCr, 'ledgerName'=>'Closing Balance', 'voucherType'=>'','drAmount'=>'', 'crAmount'=>$balance);
				$printLogicArray[++$ledgerCounter]['OpeningBalance'] = Array('voucherDate'=>$voucherDate,'drcr'=>$drcr, 'ledgerName'=>'Opening Balance', 'voucherType'=>'','drAmount'=>$balance, 'crAmount'=>'');
			}
			elseif ($totalCrAmount > $totalDrAmount) {
				$balance = $totalCrAmount - $totalDrAmount;
				$printLogicArray[++$ledgerCounter]['Total'] = Array('voucherDate'=>$currentDate,'drcr'=>$drcr, 'ledgerName'=>'Total', 'voucherType'=>'','drAmount'=>'', 'crAmount'=>$balance);
				$printLogicArray[++$ledgerCounter]['ClosingBalance'] = Array('voucherDate'=>$currentDate,'drcr'=>$oppDrCr, 'ledgerName'=>'Closing Balance', 'voucherType'=>'','drAmount'=>$balance, 'crAmount'=>'');
				$printLogicArray[++$ledgerCounter]['OpeningBalance'] = Array('voucherDate'=>$voucherDate,'drcr'=>$drcr, 'ledgerName'=>'Opening Balance', 'voucherType'=>'','drAmount'=>'', 'crAmount'=>$balance);
			}
		}

		if ($drAmount > $crAmount) {
			$drcr = 'Cr';
			$oppDrCr = 'Dr';
			$thisBalance = $drAmount - $crAmount;
			$totalDrAmount += $thisBalance;
			$printLogicArray[++$ledgerCounter]['voucher'] = Array('voucherDate'=>$voucherDate,'drcr'=>$drcr, 'ledgerName'=>	$ledgerName, 'voucherNo' => $voucherNo, 'voucherType'=>$voucherType,'drAmount'=>$thisBalance, 'crAmount'=>'');
		}
		elseif ($crAmount > $drAmount) {
			$drcr = 'Dr';
			$oppDrCr = 'Cr';
			$thisBalance = $crAmount - $drAmount;
			$totalCrAmount += $thisBalance;
			$printLogicArray[++$ledgerCounter]['voucher'] = Array('voucherDate'	=>$voucherDate, 'drcr'=>$drcr, 'ledgerName'	=>$ledgerName, 'voucherNo' => $voucherNo, 'voucherType'=>$voucherType,'drAmount'=>'', 'crAmount'=>$thisBalance);
		}
		if ($REQUEST_DATA['narration'] == 'yes' and $narration != '') {
			$narration = $ledgerVoucherRecord['narration'];
			$printLogicArray[++$ledgerCounter]['narration'] = $narration;
		}
		$currentDate = $voucherDate;
		$loopCounter++;
	}



	require_once(BL_PATH . '/Accounts/AccountsReportManager.inc.php');
	$accountsReportsManager = AccountsReportManager::getInstance();
	$accountsReportsManager->setReportWidth(700);
	$accountsReportsManager->setReportHeading("Vouchers For : $thisLedgerName");
	$accountsReportsManager->setReportInformation("As on $showDate");
	$accountsReportsManager->showGroupVoucherLedger($printLogicArray);


// $History: listLedgerReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/Ledger
//



?>