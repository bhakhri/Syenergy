<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
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

$fromDate = $REQUEST_DATA['fromDate'];


$str = '';
$condition2 = '';//having 1=1 and (';
$condition3 = '';
$condition4 = '';

$str .= ", 
	(
		select convert(GROUP_CONCAT(distinct(b.periodNumber) order by b.periodNumber),char) from  ".TIME_TABLE_TABLE." c, period b
		where b.periodId = c.periodId
		and a.employeeId = c.employeeId
		and a.subjectId = c.subjectId
		and a.groupId = c.groupId
		and a.roomId = c.roomId
		and a.timeTableLabelId = c.timeTableLabelId
		and a.fromDate = c.fromDate
		and c.toDate is null
	) as `periods`
";
//$condition2 .= $newDayArray[$REQUEST_DATA['day']]." is not null";
if (isset($REQUEST_DATA['roomId']) and !is_array($REQUEST_DATA['roomId'])) {
	$condition4 = " and a.roomId = ".$REQUEST_DATA['roomId'];
}
//$condition2 .= ')';


$timeTableArray = $timeTableManager->getClassTimeTableAdvancedDayWise($fromDate,$periodSlotId, $timeTableLabelId, $classId, $str, $condition2, $condition4);
echo json_encode($timeTableArray);

// $History: getClassTimeTableAdvancedDayWise.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:32p
//Created in $/LeapCC/Library/TimeTable
//initial checkin
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 10/06/09   Time: 11:09a
//Updated in $/LeapCC/Library/TimeTable
//applied changes for multi-slot time table.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/03/09   Time: 4:05p
//Updated in $/LeapCC/Library/TimeTable
//done changes for 
//1. fetching groups based on subjects
//2. showing mba subjects.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:14p
//Created in $/LeapCC/Library/TimeTable
//file added for class based time table
//


?>