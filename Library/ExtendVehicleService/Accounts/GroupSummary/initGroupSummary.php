<?php
//-------------------------------------------------------
//  This File contains logic for groups summary
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
    define('MODULE','GroupSummary');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];
	$groupId = $REQUEST_DATA['groupId'];
	$from = $REQUEST_DATA['from'];

	$module = 'GroupSummary_'.$groupId;

	if ($from == 'groupSummary') {
		$drillLink = "<a href='listGroupSummary.php?id=$groupId&date=$tillDate'>Group Summary</a>";
	}
	elseif ($from == 'groupSummaryDisplay') {
		$_SESSION['drillLink'] = Array();
		$_SESSION['userTrack'] = '';
		$drillLink = "<a href='listGroupSummaryDisplay.php?id=$groupId&date=$tillDate'>Group Summary</a>";
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

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	$groupLedgerArray = $voucherManager->getOneLevelTree($groupId, $tillDate);
	$groupArray = $groupLedgerArray['parentGroupsArray'];
	$ledgerArray = $groupLedgerArray['parentLedgerArray'];
?>
<table border='0' cellspacing='1' cellpadding='1' width='100%'>
	<tr class="rowheading">
		<td colspan='1' class="searchhead_text" width='80%' align='center'>Particulars</td>
		<td  colspan='1' class="searchhead_text" width='10%' align='center'>Debit</td>
		<td  colspan='1' class="searchhead_text" width='10%' align='center'>Credit</td>
	</tr>

<?php
	foreach($groupArray as $groupLedgerRecord) {
		$groupName = $groupLedgerRecord['groupName'];
		$thisGroupId = $groupLedgerRecord['groupId'];
		$groupSumArray = $voucherManager->getTreeSum($thisGroupId, $tillDate);
		$drAmount = $groupSumArray[0]['totalDrAmount'];
		$crAmount = $groupSumArray[0]['totalCrAmount'];
		if ($drAmount != $crAmount) {
			$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
?>
		<tr <?php echo $bg;?>>
			<td  colspan='1' class=''><a class='accounts' href='listGroupSummary.php?id=<?php echo $thisGroupId;?>&date=<?php echo $tillDate;?>'><?php echo $groupName;?></a></td>
		<?php
			if ($drAmount > $crAmount) {
				$balance = $drAmount - $crAmount;
				$totalDebitBalance += $balance;
		?>
				<td  colspan='1' class='' align='right'><a class='accounts' href='listGroupSummary.php?id=<?php echo $thisGroupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
				<td  colspan='1' class=''></td>
		<?php
			}
			elseif ($crAmount > $drAmount) {
				$balance = $crAmount - $drAmount;
				$totalCreditBalance += $balance;
		?>
				<td  colspan='1' class=''></td>
				<td  colspan='1' class='' align='right'><a class='accounts' href='listGroupSummary.php?id=<?php echo $thisGroupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
		<?php
			}
		?>
		</tr>
		<?php
		}
	}
	foreach($ledgerArray as $ledgerRecrod) {
		$ledgerId = $ledgerRecrod['ledgerId'];
		$ledgerName = $ledgerRecrod['ledgerName'];
		$ledgerSumArray = $voucherManager->getLedgerSum($ledgerId, $tillDate);
		$drAmount = $ledgerSumArray[0]['totalDrAmount'];
		$crAmount = $ledgerSumArray[0]['totalCrAmount'];
		$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
		if ($drAmount != $crAmount) {
?>
		<tr <?php echo $bg;?>>
			<td  colspan='1' class=''><a  class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo $ledgerName;?></a></td>
		<?php
			if ($drAmount > $crAmount) {
				$balance = $drAmount - $crAmount;
				$totalDebitBalance += $balance;
		?>
				<td  colspan='1' class='' align='right'><a  class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
				<td  colspan='1' class=''></td>
		<?php
			}
			elseif ($crAmount > $drAmount) {
				$balance = $crAmount - $drAmount;
				$totalCreditBalance += $balance;
		?>
				<td  colspan='1' class=''></td>
				<td  colspan='1' class='' align='right'><a  class='accounts' href='listLedgerVouchers.php?id=<?php echo $ledgerId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
		<?php
			}
		?>
		</tr>
		<?php
		}
	}
?>
<tr class="rowheading">
	<td  colspan='1' class='searchhead_text' align='right'>Total&nbsp;</td>
	<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalDebitBalance);?></td>
	<td  colspan='1' class='searchhead_text' align='right'><?php echo formatValue($totalCreditBalance);?></td>
</tr>
</table>
<?php
// $History: initGroupSummary.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/GroupSummary
//file added
//



?>