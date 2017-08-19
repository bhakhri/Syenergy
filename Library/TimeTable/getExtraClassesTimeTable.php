<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExtraClassesTimeTable');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();
$classId = $REQUEST_DATA['classId'];
if (!$classId) {
	echo 'please select class';
	die;
}

$timeTableLabelId = $REQUEST_DATA['labelId'];
if (!$timeTableLabelId) {
	echo 'please select time table';
	die;
}

$periodSlotId = $REQUEST_DATA['periodSlotId'];

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();
$newDayArray = $daysArr;
foreach($newDayArray as $key => $value) {
	$newDayArray[$key] = strtolower($value);
}

$str = '';
$condition2 = ' having 1=1 and (';
$date = $REQUEST_DATA['date'];



$dateDay = date('N', strtotime($date));

$str .= ", 
	(
		select convert(GROUP_CONCAT(distinct(b.periodNumber) order by b.periodNumber),char) from time_table_adjustment c, period b
		where b.periodId = c.periodId
		and a.employeeId = c.employeeId
		and a.subjectId = c.subjectId
		and a.groupId = c.groupId
		and a.roomId = c.roomId
		and c.fromDate = '".$date."'
		and a.timeTableLabelId = c.timeTableLabelId
		and a.fromDate = c.fromDate
	) as `periods`
";
$condition2 .= "periods is not null";
$condition2 .= ')';

$timeTableArray = $timeTableManager->getExtraClassesTimeTable($periodSlotId, $timeTableLabelId, $classId, $str, $condition2);
echo json_encode($timeTableArray);

// $History: getExtraClassesTimeTable.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:57a
//Created in $/LeapCC/Library/TimeTable
//file added for extra classes



?>