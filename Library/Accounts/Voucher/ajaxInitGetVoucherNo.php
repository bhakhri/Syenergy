<?php
//-------------------------------------------------------
//  This File contains logic for vouchers
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
	$mode = add_slashes(trim($REQUEST_DATA['mode']));

	$accessMode = strtolower($mode);
	define('MODULE','Voucher');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	$voucherType = $REQUEST_DATA['voucherType'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$maxVoucherNoArray = $voucherManager->getMaxVoucherNo($voucherType);
	$maxVoucherNo = $maxVoucherNoArray[0]['maxVoucherNo'];
	echo $maxVoucherNo;


// $History: ajaxInitGetVoucherNo.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:49p
//Created in $/LeapCC/Library/Accounts/Voucher
//file added
//



?>