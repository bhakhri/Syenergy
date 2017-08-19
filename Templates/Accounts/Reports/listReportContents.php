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
$task = $REQUEST_DATA['t'];
if ($task == 'bs') {
?>
<iframe  id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listBalanceSheet.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'pl') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listProfitLoss.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'tb') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listTrialBalance.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'db') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listDayBook.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'cb') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listCashBook.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'lr') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listLedgerDisplay.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'gsd') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listGroupSummaryDisplay.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'gv') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listGroupVoucherDisplay.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
elseif ($task == 'vr') {
?>
<iframe id='innerIframe' src="<?php echo UI_HTTP_PATH;?>/Accounts/listVoucherTypeRegister.php" width='100%' style="height:450px;" frameborder='0'></iframe>
<?php
}
?>
<?php
// $History: listReportContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/Reports
//



?>