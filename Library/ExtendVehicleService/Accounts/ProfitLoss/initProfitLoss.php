<?php
//-------------------------------------------------------
//  This File contains logic for profit loss
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
    define('MODULE','ProfitLoss');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	$tillDate = $REQUEST_DATA['tillDate'];

	$module = 'Profit & Loss';
	$drillLink = "<a href='listProfitLoss.php?date=$tillDate'>Profit & Loss A/c</a>";

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

	require_once(BL_PATH . "/Accounts/ProfitLoss/initProfitLossLogic.php");

?>
<table border='0' cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td valign="top" colspan='1' width='50%' class="rightBorder">
			<table border='0' cellspacing='1' cellpadding='1'  width='100%'>
				<tr class="rowheading">
					<td colspan='1' class="searchhead_text" width='80%' align="center">Expenses</td>
					<td  colspan='1' class="searchhead_text" width='20%' align="center">Amount</td>
				</tr>
				<?php
				
				foreach($expenditureGroupArray as $expenditureGroupRecord) {
					$groupId = $expenditureGroupRecord['groupId'];
					$groupName = $expenditureGroupRecord['groupName'];
					$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
					$balance = $groupSumArray[0]['totalDrAmount'] - $groupSumArray[0]['totalCrAmount'];
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
				if ($netProfitLoss != '') {
					if ($netProfitLoss == 'Nett Profit') {
					$bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
					?>
					<tr <?php echo $bg1;?>>
						<td  colspan='1' class=''><?php echo $netProfitLoss;?></td>
						<td  colspan='1' class='' align='right'><?php echo formatValue($netProfitLossAmount);?></td>
					</tr>
					<?php
					}
				}
				while ($exCtr < $incCtr) {
					$bg1 = $bg1=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
					<tr <?php echo $bg1;?>>
						<td  colspan='1' class='' ></td>
						<td  colspan='1' class='' align='right'></td>
					</tr>
				<?php
					$exCtr++;
				}
				?>
			</table>
		</td>
		<td valign="top" colspan='1' class='' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' >
				<tr class="rowheading">
					<td  colspan='1' class="searchhead_text" width='80%' align="center">Incomes</td>
					<td  colspan='1' class="searchhead_text" width='20%' align="center">Amount</td>
				</tr>
				<?php
				$bg2 = '';
				
				foreach($incomeGroupArray as $incomeGroupRecord) {
					$groupId = $incomeGroupRecord['groupId'];
					$groupName = $incomeGroupRecord['groupName'];
					$groupSumArray = $voucherManager->getTreeSum($groupId, $tillDate);
					$balance = $groupSumArray[0]['totalCrAmount'] - $groupSumArray[0]['totalDrAmount'];
					if ($balance == 0) {
						continue;
					}
					$bg2 = $bg2=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
				<tr <?php echo $bg2;?>>
					<td  colspan='1'><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo $groupName;?></a></td>
					<td  colspan='1' align="right"><a class='accounts' href='listGroupSummary.php?id=<?php echo $groupId;?>&date=<?php echo $tillDate;?>'><?php echo formatValue($balance);?></a></td>
				</tr>
				<?php
				}
				if ($netProfitLoss != '') {
					if ($netProfitLoss == 'Nett Loss') {
					$bg2 = $bg2 =='class="row0"' ? 'class="row1"' : 'class="row0"';
					?>
					<tr <?php echo $bg2;?>>
						<td  colspan='1' class='' ><?php echo $netProfitLoss;?></td>
						<td  colspan='1' class='' align='right'><?php echo $netProfitLossAmount;?></td>
					</tr>
					<?php
					}
				}
				while ($incCtr < $exCtr) {
					$bg2 = $bg2=='class="row0"' ? 'class="row1"' : 'class="row0"';
				?>
					<tr <?php echo $bg2;?>>
						<td  colspan='1' class='' ></td>
						<td  colspan='1' class='' align='right'></td>
					</tr>
				<?php
					$incCtr++;
				}
				?>
			</table>
			
		</td>
	</tr>
	<tr>
		<td  colspan='1' class='' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%' class="rightBorder">
				<tr class="rowheading">
					<td  colspan='1' class="searchhead_text" width='80%' align="center">Total</td>
					<td  colspan='1' class="searchhead_text" width='20%' align='right'><?php echo formatValue($totalExpenses);?></td>
				</tr>
			</table>
		</td>
		<td  colspan='1' class='' width='50%'>
			<table border='0' cellspacing='1' cellpadding='1' width='100%'>
				<tr class="rowheading">
					<td colspan='1' class="searchhead_text" width='80%' align="center">Total</td>
					<td  colspan='1' class="searchhead_text" width='20%' align='right'><?php echo formatValue($totalIncomes);?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
// $History: initProfitLoss.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:45p
//Created in $/LeapCC/Library/Accounts/ProfitLoss
//file added
//



?>