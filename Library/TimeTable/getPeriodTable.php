<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();


$periodSlotId = $REQUEST_DATA['periodSlotId'];

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();
$periodsArray = $timeTableManager->getSlotPeriods($periodSlotId);
if(is_array($periodsArray) && count($periodsArray)>0){
?>
	<td valign='top' colspan='5' class="contenttab_internal_rows" width="80%">
		<table border='1' cellspacing='0' cellpadding='0' rules="all" style="border-collapse:collapse;" align="center" bgcolor="#FFFF99" width="100%">
			<tr>
				<td valign='top' colspan='1' >
					<b>Timings</b>
				</td>
				<?php
				foreach($periodsArray as $periodRecord) {
					echo "<td class='contenttab_internal_rows' nowrap>".
						date('h:i',strtotime($periodRecord['startTime'])).' '.$periodRecord['startAmPm'].' - '.date('h:i',strtotime($periodRecord['endTime'])).' '.$periodRecord['endAmPm']."</td>";
				}
				?>
			</tr>
			<tr>
				<td valign='top' colspan='1' class="contenttab_internal_rows">
					<b>Periods</b>
				</td>
				<?php
				foreach($periodsArray as $periodRecord) {
					echo "<td class='contenttab_internal_rows'>".$periodRecord['periodNumber']."</td>";
				}
				?>
			</tr>
		</table>
	</td>
<?php
}else
{ ?>
<table border='1' cellspacing='0' cellpadding='0' rules="all" style="border-collapse:collapse;" align="center" bgcolor="#FFFF99" width="100%">
			<tr>
				<td valign='top' colspan='1'style="color:red;">
					<b>&nbsp Note: Periods are not defined for the selected period slot</b>
				</td></tr>
<?php
}?>
