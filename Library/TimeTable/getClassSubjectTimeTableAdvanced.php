<?php
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();

    $classId = $REQUEST_DATA['classId'];
    if (!$classId) {
	    echo 'please select class';
	    die;
    }

    $subjectId = $REQUEST_DATA['subjectId'];
    if (!$subjectId) {
        echo 'please select subject';
        die;
    }


    $timeTableLabelId = $REQUEST_DATA['labelId'];
    if (!$timeTableLabelId) {
	    echo 'please select time table';
	    die;
    }

    $periodSlotId = $REQUEST_DATA['periodSlotId'];


    $newDayArray = $daysArr;
    foreach($newDayArray as $key => $value) {
	    $newDayArray[$key] = strtolower($value);
    }

    $str = '';
    $condition2 = ' having 1=1 and (';
    $condition3 = '';
    $condition4 = " AND a.subjectId = '$subjectId'";
    if (isset($REQUEST_DATA['day'])) {
	    $str .= ",
		    (
			    select convert(GROUP_CONCAT(distinct(b.periodNumber) order by b.periodNumber),char) from  ".TIME_TABLE_TABLE." c, period b
			    where b.periodId = c.periodId
			    and a.employeeId = c.employeeId
			    and a.subjectId = c.subjectId
			    and a.groupId = c.groupId
			    and a.roomId = c.roomId
                and c.subjectId = '$subjectId'
			    and c.daysOfWeek = ".$REQUEST_DATA['day']."
			    and a.timeTableLabelId = c.timeTableLabelId
			    and a.fromDate = c.fromDate
			    and c.toDate is null
		    ) as `".$newDayArray[$REQUEST_DATA['day']]."`
	    ";
	    $condition2 .= $newDayArray[$REQUEST_DATA['day']]." is not null";
	    if (isset($REQUEST_DATA['roomId']) and !is_array($REQUEST_DATA['roomId'])) {
		    $condition4 .= " and a.roomId = ".$REQUEST_DATA['roomId'];
	    }
        $condition2 .= ')';
        $timeTableArray = $timeTableManager->getClassTimeTableAdvanced($periodSlotId, $timeTableLabelId, $classId, $str, $condition2, $condition4);
        echo json_encode($timeTableArray);
        die;
    }
    else {
       $timeTableArray = $timeTableManager->getClassTimeTableAdvancedNew($periodSlotId, $timeTableLabelId, $classId,$subjectId);
       echo json_encode($timeTableArray);
       die;
    }
?>