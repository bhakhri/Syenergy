<?php
//-------------------------------------------------------
//  This File contains logic for trial balance
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
    define('MODULE','TrialBalance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];
	$groupLedger = $REQUEST_DATA['groupLedger'];

	$_SESSION['drillLink'] = Array();
	$_SESSION['userTrack'] = '';

	$module = 'Trial Balance';

	if (!stristr($_SESSION['userTrack'], $module)) {
		if(!empty($_SESSION['userTrack'])) {
			$_SESSION['userTrack'] .= ',';
		}
		$_SESSION['userTrack'] .= $module;
		$_SESSION['drillLink'][] = "<a href='listTrialBalance.php?date=$tillDate&groupLedger=$groupLedger'>$module</a>";
	}

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();
?>
	<table border='0' cellspacing='1' cellpadding='1' width='100%'>
		<tr class="rowheading">
			<td colspan='1' class='searchhead_text' width='80%' align='center'>Particulars</td>
			<td colspan='1' class='searchhead_text' width='10%' align='center'>Debit</td>
			<td colspan='1' class='searchhead_text' width='10%' align='center'>Credit</td>
		</tr>

<?php
	if ($groupLedger == 'group') {
		$mainChildrenGroupArray = $voucherManager->getMainChildrenGroups();
		$totalDebitBalance = 0;
		$totalCreditBalance = 0;
		foreach($mainChildrenGroupArray as $childGroupRecord) {
			$groupId = $childGroupRecord['groupId'];
			$groupName = $childGroupRecord['groupName'];
			$tbRecordArray = $voucherManager->getTreeSum($groupId, $tillDate);

			$drAmount = $tbRecordArray[0]['totalDrAmount'];
			$crAmount = $tbRecordArray[0]['totalCrAmount'];
			if ($drAmount != $crAmount) {
				$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
	?>
			<tr <?php echo $bg;?>>
				<td  colspan='1' class=''><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo $groupName;?></a></td>
			<?php
				if ($drAmount > $crAmount) {
					$balance = $drAmount - $crAmount;
					$totalDebitBalance += $balance;
			?>
					<td  colspan='1' class='' align='right'><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
					<td  colspan='1' class=''></td>
			<?php
				}
				elseif ($crAmount > $drAmount) {
					$balance = $crAmount - $drAmount;
					$totalCreditBalance += $balance;
			?>
					<td  colspan='1' class=''></td>
					<td  colspan='1' class='' align='right'><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
			<?php
				}
			?>
			</tr>
			<?php
			}
		}
	}
	elseif ($groupLedger == 'ledger') {
		$ledgerSumArray = $voucherManager->getEachLedgerSum($tillDate);
		$totalDebitBalance = 0;
		$totalCreditBalance = 0;
		foreach($ledgerSumArray as $ledgerSumRecord) {
			$ledgerId = $ledgerSumRecord['ledgerId'];
			$ledgerName = $ledgerSumRecord['ledgerName'];
			$drAmount = $ledgerSumRecord['totalDrAmount'];
			$crAmount = $ledgerSumRecord['totalCrAmount'];

			if ($drAmount != $crAmount) {
				$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
	?>
			<tr <?php echo $bg;?>>
				<td  colspan='1' class=''><a class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo $ledgerName;?></a></td>
			<?php
				if ($drAmount > $crAmount) {
					$balance = $drAmount - $crAmount;
					$totalDebitBalance += $balance;
			?>
					<td  colspan='1' class='' align='right'><a class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
					<td  colspan='1' class=''></td>
			<?php
				}
				elseif ($crAmount > $drAmount) {
					$balance = $crAmount - $drAmount;
					$totalCreditBalance += $balance;
			?>
					<td  colspan='1' class=''></td>
					<td  colspan='1' class='' align='right'><a class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
			<?php
				}
			?>
			</tr>
			<?php
			}
		}
	}

		$openingBalanceArray = $voucherManager->getOpeningBalances();
		$opDrAmount = $openingBalanceArray[0]['opDrAmount'];
		$opCrAmount = $openingBalanceArray[0]['opCrAmount'];

		$diffOpDrAmount = 0;
		$diffOpCrAmount = 0;
		if ($opDrAmount > $opCrAmount) {
			$diffOpDrAmount = $opDrAmount - $opCrAmount;
			$totalCreditBalance += $diffOpDrAmount;
		}
		elseif ($opCrAmount > $opDrAmount) {
			$diffOpCrAmount = $opCrAmount - $opDrAmount;
			$totalDebitBalance += $diffOpCrAmount;
		}

		if ($diffOpDrAmount > 0) {
			$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
		?>
			<tr <?php echo $bg;?>>
				<td  colspan='1' class=''>Diff. in Opening Balances</td>
				<td  colspan='1' class=''></td>
				<td  colspan='1' class='' align='right'><?php echo formatValue($diffOpDrAmount);?></td>
			</tr>
		<?php
		}
		if ($diffOpCrAmount > 0) {
			$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
		?>
			<tr <?php echo $bg;?>>
				<td  colspan='1' class=''>Diff. in Opening Balances</td>
				<td  colspan='1' class='' align='right'><?php echo formatValue($diffOpCrAmount);?></td>
				<td  colspan='1' class=''></td>
			</tr>
		<?php
		}
	?>
	<tr class="rowheading">
		<td  colspan='1' class='searchhead_text' align='right'>Total&nbsp;</td>
		<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalDebitBalance);?></td>
		<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalCreditBalance);?></td>
	</tr>
	</table>
<?php
// $History: initTrialBalance.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:49p
//Created in $/LeapCC/Library/Accounts/TrialBalance
//file added
//



?>