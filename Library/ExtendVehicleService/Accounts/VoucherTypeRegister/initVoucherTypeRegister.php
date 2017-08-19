<?php
//-------------------------------------------------------
//  This File contains logic for voucher type register
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
    define('MODULE','VoucherRegister');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();


	$tillDate = $REQUEST_DATA['tillDate'];
	$voucherTypeId = $REQUEST_DATA['voucherTypeId'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$_SESSION['userTrack'] = '';


	$module = 'VoucherRegister'.$voucherTypeId;
	$drillLink = "<a href='listVoucherTypeRegister.php?voucherTypeId=$voucherTypeId&date=$tillDate'>Voucher Type Register</a>";

	if (!is_array($_SESSION['drillLink'])) {
		$_SESSION['drillLink'] = Array();
	}

	if (!stristr($_SESSION['userTrack'], $module)) {
		if(!empty($_SESSION['userTrack'])) {
			$_SESSION['userTrack'] .= ',';
		}
		$_SESSION['userTrack'] .= $module;
		$_SESSION['drillLink'][] = $drillLink;
	}


	echo '<table width="100%"><tr><td valign="top" class="title">';
	$thisValueFound = false;
	$i = 0;
	foreach($_SESSION['drillLink'] as $record) {
		if ($thisValueFound === true) {
			unset($_SESSION['drillLink'][$i]);
		}
		else {
			echo '&nbsp;&raquo;&nbsp;'.$record;
		}
		if ($record == $drillLink) {
			$thisValueFound = true;
		}
		$i++;
	}
	echo '</td></tr></table>';


	$totalDrAmount = 0;
	$totalCrAmount = 0;
?>
	<table border='0' cellspacing='1' cellpadding='1' width='100%'>
		<tr class="rowheading">
			<td  colspan='1' class='searchhead_text' width='10%' align='center'>Date</td>
			<td  colspan='1' class='searchhead_text' width='50%' align='center'>Particulars</td>
			<td  colspan='1' class='searchhead_text' width='10%' align='center'>Vch Type.</td>
			<td  colspan='1' class='searchhead_text' width='10%' align='center'>Vch No.</td>
			<td  colspan='1' class='searchhead_text' width='10%' align='center'>Debit</td>
			<td  colspan='1' class='searchhead_text' width='10%' align='center'>Credit</td>
		</tr>
<?php

	$ledgerVouchersArray = $voucherManager->getTypeVouchers($voucherTypeId, $tillDate);

	foreach($ledgerVouchersArray as $ledgerVoucherRecord) {
		$voucherId = $ledgerVoucherRecord['voucherId'];
		$voucherDate = UtilityManager::formatDate($ledgerVoucherRecord['voucherDate']);
		$voucherType = $voucherTypeArray[$ledgerVoucherRecord['voucherTypeId']];
		$voucherNo = $ledgerVoucherRecord['voucherNo'];
		$ledgerName = $ledgerVoucherRecord['ledgerName'];
		$crAmount = $ledgerVoucherRecord['crAmount'];
		$drAmount = $ledgerVoucherRecord['drAmount'];
		$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
?>
		<tr <?php echo $bg;?>>
			<td  colspan='1' class=''><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo $voucherDate;?></a></td>
			<td  colspan='1' class=''><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo $ledgerName;?></a></td>
			<td  colspan='1' class=''><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo $voucherType;?></a></td>
			<td  colspan='1' class='' align='right'><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo $voucherNo;?></a></td>

<?php
		if ($drAmount > $crAmount) {
			$thisBalance = $drAmount - $crAmount;
			$totalDrAmount += $thisBalance;
			
	?>
			<td  colspan='1' class='' align='right'><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo formatValue($thisBalance);?></a></td>
			<td  colspan='1' class=''></td>
	<?php
		}
		else {
			$thisBalance = $crAmount - $drAmount;
			$totalCrAmount += $thisBalance;
		?>
			<td  colspan='1' class=''></td>
			<td  colspan='1' class='' align='right'><a class='accounts' href='listVoucher.php?id=<?php echo $voucherId;?>'><?php echo formatValue($thisBalance);?></a></td>
		<?php
			}
		}
?>
		</tr>
	</table>
<?php
// $History: initVoucherTypeRegister.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:49p
//Created in $/LeapCC/Library/Accounts/VoucherTypeRegister
//file added
//



?>