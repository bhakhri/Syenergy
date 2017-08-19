<?php
//-------------------------------------------------------
//  This File contains logic for day book
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
    define('MODULE','DayBook');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	
	$_SESSION['drillLink'] = Array();
	$_SESSION['userTrack'] = '';

	$module = 'DayBook';

	if (!stristr($_SESSION['userTrack'], $module)) {
		if(!empty($_SESSION['userTrack'])) {
			$_SESSION['userTrack'] .= ',';
		}
		$_SESSION['userTrack'] .= $module;
		$_SESSION['drillLink'][] = "<a href='listDayBook.php?date=$tillDate'>$module</a>";
	}
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

	$dayVouchersArray = $voucherManager->getDayVouchers($tillDate);
	if (!count($dayVouchersArray)) {
?>
		<tr>
			<td  colspan='6'  width='10%' align='center'>No Data Found</td>
		</tr>
<?php
	}

	foreach($dayVouchersArray as $dayVoucherRecord) {
		$voucherId = $dayVoucherRecord['voucherId'];
		$voucherDate = UtilityManager::formatDate($dayVoucherRecord['voucherDate']);
		$voucherType = $voucherTypeArray[$dayVoucherRecord['voucherTypeId']];
		$voucherNo = $dayVoucherRecord['voucherNo'];
		$ledgerName = $dayVoucherRecord['ledgerName'];
		$crAmount = $dayVoucherRecord['crAmount'];
		$drAmount = $dayVoucherRecord['drAmount'];
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
// $History: initDayBook.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/DayBook
//file added
//



?>