<?php
//-------------------------------------------------------
//  This File contains logic for ledger vouchers
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
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];
	$ledgerId = $REQUEST_DATA['ledgerId'];
	$from = $REQUEST_DATA['from'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();


	$module = 'LedgerSummary_'.$groupId;
	if ($from == 'ledger') {
		$drillLink = "<a href='listLedgerVouchers.php?id=$ledgerId&date=$tillDate'>Ledger Vouchers</a>";
	}
	elseif ($from == 'ledgerDisplay') {
		$_SESSION['drillLink'] = Array();
		$_SESSION['userTrack'] = '';
		$drillLink = "<a href='listLedgerDisplay.php?id=$ledgerId&date=$tillDate'>Ledger Display</a>";
	}

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

	$ledgerOpeningBalanceArray = $voucherManager->getLedgerOpeningBalance($ledgerId);
	$ledgerName = $ledgerOpeningBalanceArray[0]['ledgerName'];
	$opDrAmount = $ledgerOpeningBalanceArray[0]['opDrAmount'];
	$opCrAmount = $ledgerOpeningBalanceArray[0]['opCrAmount'];

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
	if ($opDrAmount != $opCrAmount) {

		$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
?>
		<tr <?php echo $bg;?>>
			<td  colspan='1' class=''></td>
			<td  colspan='1' class=''>Opening Balance</td>
			<td  colspan='1' class=''></td>
			<td  colspan='1' class=''></td>

<?php
		if ($opDrAmount > $opCrAmount) {
			$totalDrAmount += $opDrAmount - $opCrAmount;
?>
			<td  colspan='1' class='' align='right'><?php echo formatValue($opDrAmount - $opCrAmount);?></td>
			<td  colspan='1' class=''></td>
<?php
		}
		elseif ($opCrAmount > $opDrAmount) {
			$totalCrAmount += $opCrAmount - $opDrAmount;
?>
			<td  colspan='1' class=''></td>
			<td  colspan='1' class='' align='right'><?php echo formatValue($opCrAmount - $opDrAmount);?></td>
<?php
		}
?>
		</tr>
<?php
	}
	$ledgerVouchersArray = $voucherManager->getLedgerVouchers($ledgerId, $tillDate);

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
		<tr class="rowheading">
			<td  colspan='4' class='searchhead_text' align='right'>Total&nbsp;</td>
			<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalDrAmount);?></td>
			<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalCrAmount);?></td>
		</tr>
		<?php
			if ($totalDrAmount > $totalCrAmount) {
		?>
		<tr>
			<td  colspan='4' class='' align='right'><B>Closing Balance</B></td>
			<td  colspan='1' class='' align='right'><B><?php echo formatValue($totalDrAmount - $totalCrAmount);?></B></td>
			<td  colspan='1' class=''></td>
		</tr>
		<?php
		}
		elseif ($totalCrAmount > $totalDrAmount) {
		?>
		<tr>
			<td  colspan='4' class=''><B>Closing Balance</B></td>
			<td  colspan='1' class=''></td>
			<td  colspan='1' class='' align='right'><B><?php echo formatValue($totalCrAmount - $totalDrAmount);?></B></td>
		</tr>
		<?php
		}
		?>
</table>
<?php
// $History: initLedgerVouchers.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/LedgerVouchers
//file added
//



?>