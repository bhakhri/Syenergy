<?php
//-------------------------------------------------------
//  This File contains logic for vouchers
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
	if (empty($accessMode)) {
		echo ACCESS_DENIED;
		die;
	}
	define('MODULE','Voucher');
	define('ACCESS',$accessMode);
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();


	if (!isset($REQUEST_DATA['voucherType']) or $REQUEST_DATA['voucherType'] == '') {
		echo INVALID_VOUCHER_TYPE;
		die;
	}

	$voucherDate = $REQUEST_DATA['voucherDate'];

	list($date,$month,$year) = explode('-',$voucherDate);
	if (false ==  checkdate($month, $date, $year)) {
		echo ENTER_VALID_DATE;
		die;
	}
	$thisVoucherDate = $year . '-' . $month . '-' . $date;

	$checkFinancialYearArray = $voucherManager->checkDateInFinancialYear($thisVoucherDate);
	$cnt = $checkFinancialYearArray[0]['cnt'];
	if ($cnt == 0) {
		echo VOUCHER_DATE_NOT_FALLS_IN_COMPANY_FINANCIAL_YEAR;
		die;
	}

	$voucherType = $REQUEST_DATA['voucherType'];
	$debitLedgerEntriesArray = Array();
	$creditLedgerEntriesArray = Array();

	if ($voucherType == RECEIPT) {
		$debitLedgerEntriesArray = $voucherManager->getLedgersRDPC();
		$creditLedgerEntriesArray = $voucherManager->getLedgersRCPDJDJC();
	}
	elseif ($voucherType == PAYMENT) {
		$debitLedgerEntriesArray = $voucherManager->getLedgersRCPDJDJC();
		$creditLedgerEntriesArray = $voucherManager->getLedgersRDPC();
	}
	elseif ($voucherType == JOURNAL) {
		$debitLedgerEntriesArray = $voucherManager->getLedgersRCPDJDJC();
		$creditLedgerEntriesArray = $debitLedgerEntriesArray;//same in case of journal
	}
	elseif ($voucherType == CONTRA) {
		$debitLedgerEntriesArray = $voucherManager->getLedgersCDCC();
		$creditLedgerEntriesArray = $debitLedgerEntriesArray;//same in case of contra
	}
	else {
		echo INVALID_VOUCHER_TYPE;
		die;
	}

	$debitLedgerEntriesArray = explode(',', UtilityManager::makeCSList($debitLedgerEntriesArray, 'ledgerName'));
	$creditLedgerEntriesArray = explode(',', UtilityManager::makeCSList($creditLedgerEntriesArray, 'ledgerName'));

	$totalDebit = 0;
	$totalCredit = 0;

	$i = 0;
	while ($i < VOUCHER_ENTRIES_ON_PAGE) {
		$entryDrCr = $REQUEST_DATA['drcr_'.$i];
		$voucherLedger = $REQUEST_DATA['voucherLedgers_'.$i];
		if ($voucherLedger == '') {
			break;
		}
		if ($entryDrCr == 'dr') {
			if (!in_array($voucherLedger, $debitLedgerEntriesArray)) {
				echo INVALID_LEDGER_.$voucherLedger;
				die;
			}
			$totalDebit += floatval($REQUEST_DATA['debit_'.$i]);
		}
		elseif ($entryDrCr == 'cr') {
			if (!in_array($voucherLedger, $creditLedgerEntriesArray)) {
				echo INVALID_LEDGER_.$voucherLedger;
				die;
			}
			$totalCredit += floatval($REQUEST_DATA['credit_'.$i]);
		}
		$i++;
	}
	if ($totalDebit == 0 or $totalCredit == 0) {
		echo VOUCHER_INCOMPLETE;
		die;
	}
	if ($totalDebit != $totalCredit) {
		echo VOUCHER_INCOMPLETE;
		die;
	}

	$narration = $REQUEST_DATA['narration'];
	if ($REQUEST_DATA['voucherId'] != '') {
		
		//edit voucher

		$editVoucherId = $REQUEST_DATA['voucherId'];
		$validArray = $voucherManager->isVoucherValid($editVoucherId);
		if ($validArray[0]['cnt'] == 0) {
			echo INVALID_VOUCHER;
			die;
		}
		$editVoucherTypeArray = $voucherManager->getVoucherType($editVoucherId);
		$editVoucherType = $editVoucherTypeArray[0]['voucherTypeId'];

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if ($editVoucherType != $voucherType) {
				$voucherTypeId = $voucherType;
				$maxVoucherNoArray = $voucherManager->getMaxVoucherNo($voucherType);
				$voucherNo = $maxVoucherNoArray[0]['maxVoucherNo'];
				$postedVoucherNo = $REQUEST_DATA['voucherNo'];
				if ($voucherNo != intval($postedVoucherNo)) {
					echo INVALID_VOUCHER_NO_ENTERED;
					die;
				}

				$return = $voucherManager->updateVoucherMasterInTransaction($editVoucherId, $thisVoucherDate, $voucherType, $voucherNo, $narration);
				if ($return == false) {
					echo ERROR_WHILE_UPDATING_VOUCHER;
					die;
				}
			}
			else {
				$postedVoucherNo = $REQUEST_DATA['voucherNo'];
				$return = $voucherManager->updateSameTypeVoucherMasterInTransaction($editVoucherId, $thisVoucherDate, $postedVoucherNo, $narration);
				if ($return == false) {
					echo ERROR_WHILE_UPDATING_VOUCHER;
					die;
				}
			}
			
			$return = $voucherManager->deleteVoucherTransInTransaction($editVoucherId);
			if ($return == false) {
				echo ERROR_WHILE_UPDATING_VOUCHER;
				die;
			}
			$i = 0;
			$transStr = '';
			while ($i < VOUCHER_ENTRIES_ON_PAGE) {
				$entryDrCr = $REQUEST_DATA['drcr_'.$i];
				$voucherLedger = $REQUEST_DATA['voucherLedgers_'.$i];
				if ($voucherLedger == '') {
					break;
				}
				$voucherLedgerNameArray = $voucherManager->getLedgerId("'$voucherLedger'");
				$ledgerId = $voucherLedgerNameArray[0]['ledgerId'];

				if ($entryDrCr == 'dr') {
					$thisDebit = floatval($REQUEST_DATA['debit_'.$i]);
					if (!empty($transStr)) {
						$transStr .= ',';
					}
					$transStr .= "($editVoucherId, $ledgerId, $thisDebit,0)";
				}
				elseif ($entryDrCr == 'cr') {
					$thisCredit = floatval($REQUEST_DATA['credit_'.$i]);
					if (!empty($transStr)) {
						$transStr .= ',';
					}
					$transStr .= "($editVoucherId, $ledgerId,0,$thisCredit)";
				}
				$i++;
			}
			//store voucher : trans
			$return = $voucherManager->addVoucherTransRecordInTransaction($transStr);
			if ($return == false) {
				echo ERROR_WHILE_SAVING_VOUCHER;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}
	}
	else {
		//add voucher
		//store voucher : master
		$voucherTypeId = $voucherType;
		$maxVoucherNoArray = $voucherManager->getMaxVoucherNo($voucherTypeId);
		$voucherNo = $maxVoucherNoArray[0]['maxVoucherNo'];


		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$return = $voucherManager->addVoucherMasterRecordInTransaction($thisVoucherDate,$voucherTypeId, $voucherNo, $narration);
			if ($return == false) {
				echo ERROR_WHILE_SAVING_VOUCHER;
				die;
			}

			$voucherIdArray = $voucherManager->getMaxVoucherId($voucherTypeId);
			$voucherId = $voucherIdArray[0]['voucherId'];

			$i = 0;
			$transStr = '';
			while ($i < VOUCHER_ENTRIES_ON_PAGE) {
				$entryDrCr = $REQUEST_DATA['drcr_'.$i];
				$voucherLedger = $REQUEST_DATA['voucherLedgers_'.$i];
				if ($voucherLedger == '') {
					break;
				}
				$voucherLedgerNameArray = $voucherManager->getLedgerId("'$voucherLedger'");
				$ledgerId = $voucherLedgerNameArray[0]['ledgerId'];

				if ($entryDrCr == 'dr') {
					$thisDebit = floatval($REQUEST_DATA['debit_'.$i]);
					if (!empty($transStr)) {
						$transStr .= ',';
					}
					$transStr .= "($voucherId, $ledgerId, $thisDebit,0)";
				}
				elseif ($entryDrCr == 'cr') {
					$thisCredit = floatval($REQUEST_DATA['credit_'.$i]);
					if (!empty($transStr)) {
						$transStr .= ',';
					}
					$transStr .= "($voucherId, $ledgerId,0,$thisCredit)";
				}
				$i++;
			}
			//store voucher : trans
			$return = $voucherManager->addVoucherTransRecordInTransaction($transStr);
			if ($return == false) {
				echo ERROR_WHILE_SAVING_VOUCHER;
				die;
			}

			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}
	}


// $History: ajaxInitAction.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:49p
//Created in $/LeapCC/Library/Accounts/Voucher
//file added
//



?>