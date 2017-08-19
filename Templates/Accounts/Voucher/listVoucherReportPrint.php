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
	require_once(BL_PATH . "/NumToWord.class.php");


?>
<form name='frm' method='post' action=''><input type='hidden' name='testing' value='' /><input type='hidden' name='formSubmitted' value='1' /></form>
<?php
if (isset($REQUEST_DATA['formSubmitted'])) {
	$voucherDetails = $REQUEST_DATA['testing'];
	$voucherDetailsArray = explode('&',$voucherDetails);
	$allDetailsArray = array();
	foreach($voucherDetailsArray as $voucherDetailRecord) {
		list($postKey, $postValue) = explode('=',$voucherDetailRecord);
		$allDetailsArray[$postKey] = $postValue;
	}
	$totalArray = array();
	$totalArray['voucherNo'] = $allDetailsArray['voucherNo'];
	$totalArray['voucherDate'] = date('d-M-Y', strtotime($allDetailsArray['voucherDate']));
	$totalArray['voucherType'] = $voucherTypeArray[$allDetailsArray['voucherType']];
	$totalArray['narration'] = $allDetailsArray['narration'];


	$i = 0;
	$entryCtrD = -1;
	$entryCtrC = -1;

	$debitTotal = 0;
	
	
	while($i < VOUCHER_ENTRIES_ON_PAGE) {
		if (isset($allDetailsArray['voucherLedgers_'.$i]) and $allDetailsArray['voucherLedgers_'.$i] != '') {
			if ($allDetailsArray['drcr_'.$i] == 'dr') {
				$totalArray[++$entryCtrD]['debit'] = Array($allDetailsArray['voucherLedgers_'.$i], $allDetailsArray['debit_'.$i]);
				$debitTotal += $allDetailsArray['debit_'.$i];
			}
			elseif ($allDetailsArray['drcr_'.$i] == 'cr') {
				$totalArray[++$entryCtrC]['credit'] = Array($allDetailsArray['voucherLedgers_'.$i], $allDetailsArray['credit_'.$i]);
			}
		}
		$i++;
	}

	 $numToWord = new NumberToWord($debitTotal);
	 $totalArray['total'] = 'Rs. '.ucwords($numToWord->word). ' Only';

	require_once(BL_PATH . '/Accounts/AccountsReportManager.inc.php');
	$accountsReportsManager = AccountsReportManager::getInstance();
	$accountsReportsManager->setReportHeading($totalArray['voucherType'] . ' Voucher');
	$accountsReportsManager->setReportWidth(600);
	$accountsReportsManager->showVoucher($totalArray);
	//$accountsReportsManager->showVoucher($totalArray);

}
else {
?>
<SCRIPT LANGUAGE="JavaScript">
	var pars = window.opener.generateQueryString('voucherForm');
	document.frm.testing.value=pars;
	document.frm.submit();
</SCRIPT>
<?php
}
?>
<?php
// $History: listVoucherReportPrint.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:53p
//Created in $/LeapCC/Templates/Accounts/Voucher
//



?>