<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();

$periodSlotId = $REQUEST_DATA['periodSlotId'];
//$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
$classId = $REQUEST_DATA['classId'];


require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();
$adjustmentArray = $timeTableManager->getClassAdjustments($timeTableLabelId, $classId);
if (count($adjustmentArray)) {
	$copyStringArray = array();
	$moveStringArray = array();
	$swapStringArray = array();
	$extraStringArray = array();
	foreach($adjustmentArray as $adjustmentRecord) {
		$newEmployee = $adjustmentRecord['newEmployee'];
		$oldEmployee = $adjustmentRecord['oldEmployee'];
		$groupShort = $adjustmentRecord['groupShort'];
		$subjectCode = $adjustmentRecord['subjectCode'];
		$periodNumber = $adjustmentRecord['periodNumber'];
		$adjustmentType = $adjustmentRecord['adjustmentType'];
		$fromDate = date('d-M-Y', strtotime($adjustmentRecord['fromDate']));
		$toDate = date('d-M-Y', strtotime($adjustmentRecord['toDate']));
		$subjectCode = $adjustmentRecord['subjectCode'];
		$dayName = $daysArr[$adjustmentRecord['daysOfWeek']];
		$periodNumber = $adjustmentRecord['periodNumber'];

		if ($adjustmentType == 1) {
			$copyStringArray[] = "<b>Teacher:</b> $newEmployee will be taking <b>Subject:</b> $subjectCode of <b>Group:</b> $groupShort <b>On</b> $toDate <b>Period:</b> $periodNumber ";
		}
		elseif ($adjustmentType == 2) {
			$moveStringArray[] = "<b>Teacher:</b> $newEmployee will be taking <b>Subject:</b> $subjectCode of <b>Group:</b> $groupShort <b>On</b> $toDate <b>Period:</b> $periodNumber";
		}
		elseif ($adjustmentType == 3) {
			$swapStringArray[] =  "<b>Teacher:</b> $newEmployee will be taking <b>Subject:</b> $subjectCode of <b>Group:</b> $groupShort <b>On</b> All {$dayName}s <b>From</b> $fromDate <b>To</b> $toDate <b>Period:</b> $periodNumber";
		}
		elseif ($adjustmentType == 4) {
			$extraStringArray[] = "<b>Teacher:</b> $newEmployee will be taking <b>Subject:</b> $subjectCode of <b>Group:</b> $groupShort <b>On</b> $toDate <b>Period:</b> $periodNumber";
		}
		else {
		}
	}
	$string = '';
	if (count($copyStringArray)) {
		$string .= "<b><u>Adjustment-Copy</u></b>";
		$string .= "<br>";
		foreach($copyStringArray as $key => $value) {
			$string .= "<br>";
			$string .= $key+1 . '. '.$value;
		}
		$string .= "<hr size='1'>";
	}
	if (count($moveStringArray)) {
		$string .= "<b><u>Adjustment-Move</u></b>";
		$string .= "<br>";
		foreach($moveStringArray as $key => $value) {
			$string .= "<br>";
			$string .= $key+1 . '. '.$value;
		}
		$string .= "<hr size='1'>";
	}
	if (count($swapStringArray)) {
		$string .= "<b><u>Adjustment-Swap</u></b>";
		$string .= "<br>";
		foreach($swapStringArray as $key => $value) {
			$string .= '<br>';
			$string .= $key+1 . '. '.$value;
		}
		$string .= "<hr size='1'>";
	}
	if (count($extraStringArray)) {
		$string .= "<b><u>Adjustment-Extra Classes</u></b>";
		$string .= "<br>";
		foreach($extraStringArray as $key => $value) {
			$string .= '<br>';
			$string .= $key+1 . '. '.$value;
		}
		$string .= "<hr size='1'>";
	}
	echo $string;
}
else {
	echo 'No Adjustments Found';
}

?>