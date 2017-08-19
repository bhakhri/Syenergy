<?php
//-------------------------------------------------------
//  This File contains logic for all reports
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
$task = $REQUEST_DATA['t'];
if ($task == 'bs') {
	$title = 'Balance Sheet';
	define('MODULE','BalanceSheet');
}
elseif ($task == 'pl') {
	$title = 'Profit & Loss';
	define('MODULE','ProfitLoss');
}
elseif ($task == 'tb') {
	$title = 'Trial Balance';
	define('MODULE','TrialBalance');
}
elseif ($task == 'db') {
	$title = 'Day Book';
	define('MODULE','DayBook');
}
elseif ($task == 'cb') {
	$title = 'Cash Book';
	define('MODULE','CashBook');
}
elseif ($task == 'lr') {
	$title = 'Ledger Account';
	define('MODULE','LedgerAccount');
}
elseif ($task == 'gsd') {
	$title = 'Group Summary';
	define('MODULE','GroupSummary');
}
elseif ($task == 'gv') {
	$title = 'Group Vouchers';
	define('MODULE','GroupVouchers');
}
elseif ($task == 'jr') {
	$title = 'Voucher Register';
	define('MODULE','VoucherRegister');
}

define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::ifCompanyNotSelected();
?>
<title><?php echo SITE_NAME;?>: <?php echo $title;?> </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Accounts/Reports/listReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listReport.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>