<?php
//-------------------------------------------------------
//  This File contains logic for company
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
	define('MODULE','CompanyCreate');
	define('ACCESS',$accessMode);
	UtilityManager::ifNotLoggedIn(true);
	//UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	$companyName	=	add_slashes(trim($REQUEST_DATA['companyName']));
	$address		=	add_slashes(trim($REQUEST_DATA['address']));
	$email			=	add_slashes(trim($REQUEST_DATA['email']));
	$phone			=	add_slashes(trim($REQUEST_DATA['phone']));
	$fyearFrom		=	add_slashes(trim($REQUEST_DATA['fyearFrom']));
	$companyId		=	add_slashes(trim($REQUEST_DATA['companyId']));


	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	require_once(MODEL_PATH . '/Accounts/CompanyManager.inc.php');
	$companyManager = CompanyManager::getInstance();

	if ($mode == 'Delete') {

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$returnStatus = $companyManager->deleteCompanyVouchersTrans($companyId);
			if($returnStatus === false) {
				echo FAILURE_WHILE_REMOVING_COMPANY_VOUCHER_DETAILS;
				die;
			}
			$returnStatus = $companyManager->deleteCompanyVouchersMaster($companyId);
			if($returnStatus === false) {
				echo FAILURE_WHILE_REMOVING_COMPANY_VOUCHER_MASTERS;
				die;
			}
			$returnStatus = $companyManager->deleteCompanyLedgers($companyId);
			if($returnStatus === false) {
				echo FAILURE_WHILE_REMOVING_COMPANY_LEDGERS;
				die;
			}
			$returnStatus = $companyManager->deleteCompanyGroups($companyId);
			if($returnStatus === false) {
				echo FAILURE_WHILE_REMOVING_COMPANY_GROUPS;
				die;
			}
			$returnStatus = $companyManager->deleteCompany($companyId);
			if($returnStatus === false) {
				echo FAILURE_WHILE_REMOVING_COMPANY;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo DELETE;
				die;
			}
			else {
				echo FAILURE_WHILE_COMMITTING_TRANSACTION;
				die;
			}
		}
		else {
			echo FAILURE_WHILE_STARTING_TRANSACTION;
			die;
		}
	}
	else {
		if (empty($companyName) or $companyName == '') {
			echo ENTER_COMPANY_NAME;
			die;
		}
		elseif (empty($address) or $address == '') {
			echo ENTER_COMPANY_ADDRESS;
			die;
		}
		elseif (empty($email) or $email == '') {
			echo ENTER_EMAIL_ADDRESS;
			die;
		}
		elseif(HtmlFunctions::getInstance()->isEmail($email) == 0) {
			echo ENTER_VALID_EMAIL_ADDRESS;
			die;
		}
		elseif (empty($phone) or $phone == '') {
			echo ENTER_PHONE_NUMBER;
			die;
		}
		elseif (empty($fyearFrom) or $fyearFrom == '') {
			echo SELECT_FINANCIAL_START;
			die;
		}
		$countArray = $companyManager->checkNameYear($companyName, $fyearFrom);
		if ($countArray[0]['cnt'] > 0) {
			echo COMPANYNAME_AND_FINANCIAL_YEAR_ALREADY_EXIST;
			die;
		}

		if ($mode == 'Add') {

			require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
			if(SystemDatabaseManager::getInstance()->startTransaction()) {
				$returnStatus = $companyManager->addCompany($companyName, $address, $email, $phone, $fyearFrom);
				if($returnStatus === false) {
					echo FAILURE_WHILE_ADDING_NEW_COMPANY;
					die;
				}

				$companyIdArray = $companyManager->getLatestCompanyId();
				$companyId = $companyIdArray[0]['companyId'];

				$returnStatus = $companyManager->addGroupsToCompany($companyId);
				if($returnStatus === false) {
					echo FAILURE_WHILE_ADDING_GROUPS_TO_COMPANY;
					die;
				}

				$returnStatus = $companyManager->addLedgersToCompany($companyId);
				if($returnStatus === false) {
					echo FAILURE_WHILE_ADDING_LEDGERS_TO_COMPANY;
					die;
				}

				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					echo SUCCESS;
					die;
				}
				else {
					echo FAILURE_WHILE_COMMITTING_TRANSACTION;
					die;
				}
			}
			else {
				echo FAILURE_WHILE_STARTING_TRANSACTION;
				die;
			}
		}
		elseif ($mode == 'Edit') {
			$outOfDateVouchersArray = $companyManager->checkVouchersOutOfFYearDate($companyId, $fyearFrom);
			$outOfDateVouchers = $outOfDateVouchersArray[0]['cnt'];
			if ($outOfDateVouchers > 0) {
				echo VOUCHERS_ENTERED_ALREADY_IN_OLD_FINANCIAL_DATES;
				die;
			}
			$returnStatus = $companyManager->editCompany($companyId, $companyName, $address, $email, $phone, $fyearFrom);
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
//Updated in $/LeapCC/Library/Accounts/Company
//removed access rights, placed accidently
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Company
//file added
//



	


?>