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
	$voucherTypeId = $REQUEST_DATA['voucherTypeId'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$showDate = date('d-M-Y', strtotime($tillDate));

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$voucherTypeRegisterArray = $voucherManager->getTypeVouchers($voucherTypeId, $tillDate);

	$voucherTypeName = $voucherTypeArray[$voucherTypeId];

    $accountsReportsManager->setReportWidth(700);
    
    $accountsReportsManager->setReportInformation("As on $showDate");

	$accountsReportsManager->setReportHeading("$voucherTypeName Register");
	$accountsReportsManager->showVoucherTypeRegister($voucherTypeRegisterArray);

?>
<?php
// $History: listVoucherTypeRegisterReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:53p
//Created in $/LeapCC/Templates/Accounts/VoucherTypeRegister
//



?>