<?php
//-------------------------------------------------------
//  This File contains menu items for accounts module.
//
//
// Author :Ajinder Singh
// Created on : 10-Aug-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
if (defined('INCLUDE_ACCOUNTS') and (INCLUDE_ACCOUNTS === true)) {
	define("UI_ACCOUNTS_PATH", LIB_PATH ."/Interface/Accounts"); //accounts real path
	define("UI_HTTP_ACCOUNTS_PATH", HTTP_PATH ."/Interface/Accounts"); //accounts http path

	define('RECEIPT',1);
	define('PAYMENT',2);
	define('JOURNAL',3);
	define('CONTRA',4);
	define('VOUCHER_ENTRIES_ON_PAGE',10);

	$voucherTypeArray = Array(1 => 'Receipt', 2 => 'Payment', 3 => 'Journal', 4 => 'Contra');

	function formatValue($value) {
		return number_format($value,2);
	}
}

// $History : accountsCommon.inc.php  $
//




?>