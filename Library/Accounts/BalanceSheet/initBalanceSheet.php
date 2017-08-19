<?php
//-------------------------------------------------------
//  This File contains logic for balance sheet
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
    define('MODULE','BalanceSheet');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];

	require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
	$voucherManager = VoucherManager::getInstance();

	
	$_SESSION['drillLink'] = Array();
	$_SESSION['userTrack'] = '';

	$module = 'BalanceSheet';

	if (!stristr($_SESSION['userTrack'], $module)) {
		if(!empty($_SESSION['userTrack'])) {
			$_SESSION['userTrack'] .= ',';
		}
		$_SESSION['userTrack'] .= $module;
		$_SESSION['drillLink'][] = "<a href='listBalanceSheet.php?date=$tillDate'>$module</a>";
	}

	require_once(BL_PATH . "/Accounts/BalanceSheet/initBalanceSheetLogic.php");

?>
<table border='0' cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td valign="top" colspan='1' class='rightBorder' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' >
				<tr class="rowheading">
					<td colspan='1' class="searchhead_text" width='80%' align="center">Liabilities</td>
					<td colspan='1' class="searchhead_text" width='20%' align="center">Amount</td>
				</tr>
				<?php
				foreach($liabilitiesGroupArray as $liabilitiesGroupRecord) {
					$groupId = $liabilitiesGroupRecord['groupId'];
					$groupName = $liabilitiesGroupRecord['groupName'];
					$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
					$balance = $groupSumArray[0]['totalCrAmount'] - $groupSumArray[0]['totalDrAmount'];
					if ($balance == 0) {
						continue;
					}
					$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
				<tr <?php echo $bg;?>>
					<td  colspan='1'><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo $groupName;?></a></td>
					<td  colspan='1' align="right"><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
				</tr>
				<?php
				}
				if ($diffOpDrAmount > 0) {
					$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
				<tr <?php echo $bg;?>>
						<td  colspan='1' class=''>Diff. in Opening Balances</td>
						<td  colspan='1' class='' align='right'><?php echo formatValue($diffOpDrAmount);?></td>
					</tr>
				<?php
				}
				if ($profitLoss != '') {
					if ($profitLoss == 'Profit') {
					$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					?>
				<tr <?php echo $bg;?>>
						<td  colspan='1' class=''><a class='accounts' href='listProfitLoss.php?date=<?php echo $tillDate;?>'>Profit & Loss Account</a></td>
						<td  colspan='1' class='' align="right"><a class='accounts' href='listProfitLoss.php?date=<?php echo $tillDate;?>'><?php echo formatValue($profitLossAmount);?></a></td>
					</tr>
					<?php
					}
				}
				?>
			</table>
		</td>
		<td valign="top" colspan='1' class='' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' >
				<tr class="rowheading">
					<td colspan='1' class="searchhead_text" width='80%' align="center">Assets</td>
					<td colspan='1' class="searchhead_text" width='20%' align="center">Amount</td>
				</tr>
				<?php
				foreach($assetGroupArray as $assetGroupRecord) {
					$groupId = $assetGroupRecord['groupId'];
					$groupName = $assetGroupRecord['groupName'];
					$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
					$balance = $groupSumArray[0]['totalDrAmount'] - $groupSumArray[0]['totalCrAmount'];
					if ($balance == 0) {
						continue;
					}
					$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
				<tr <?php echo $bg;?>>
					<td  colspan='1' class=''><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo $groupName;?></a></td>
					<td  colspan='1' class='' align="right"><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
				</tr>
				<?php
				}
				if ($diffOpCrAmount > 0) {
					$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
				<tr <?php echo $bg;?>>
						<td  colspan='1' class=''>Diff. in Opening Balances</td>
						<td  colspan='1' class='' align="right"><?php echo formatValue($diffOpCrAmount);?></td>
					</tr>
				<?php
				}
				if ($profitLoss != '') {
					if ($profitLoss == 'Loss') {
					?>
					<tr>
						<td  colspan='1' class=''><a class='accounts' href='listProfitLoss.php?date=<?php echo $tillDate;?>'>Profit & Loss Account</a></td>
						<td  colspan='1' class='' align="right"><a class='accounts' href='listProfitLoss.php?date=<?php echo $tillDate;?>'><?php echo formatValue($profitLossAmount);?></a></td>
					</tr>
					<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td  colspan='1' class='rightBorder' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' >
				<tr class="rowheading">
					<td  colspan='1' class='searchhead_text' width='80%' align='right'>Total&nbsp;</td>
					<td  colspan='1' class='searchhead_text' width='20%' align='right'><?php echo formatValue($totalLiabilities);?></td>
				</tr>
			</table>
		</td>
		<td  colspan='1' class='' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' >
				<tr class="rowheading">
					<td  colspan='1' class='searchhead_text' width='80%' align='right'>Total&nbsp;</td>
					<td  colspan='1' class='searchhead_text' width='20%' align='right'><?php echo formatValue($totalAssets);?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
// $History: initBalanceSheet.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/BalanceSheet
//file added
//



?>