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

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$showDate = date('d-M-Y', strtotime($tillDate));

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$dayVouchersArray = $voucherManager->getDayVouchers($tillDate);

    $accountsReportsManager->setReportWidth(700);
    
    $accountsReportsManager->setReportInformation("For $showDate");

	$accountsReportsManager->setReportHeading("Day Book");
	$accountsReportsManager->showVoucherTypeRegister($dayVouchersArray);

// $History: listDayBookReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/DayBook
//



?>