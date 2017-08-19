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

    $timeTableLabelId = $REQUEST_DATA['labelId'];
    if (!$timeTableLabelId) {
	    echo 'please select time table';
	    die;
    }

    $periodSlotId = $REQUEST_DATA['periodSlotId'];


    $condition = '';
    if(isset($REQUEST_DATA['day'])) {
       $condition .= " AND a.daysOfWeek = '".$REQUEST_DATA['day']."'";
       if(isset($REQUEST_DATA['roomId']) and !is_array($REQUEST_DATA['roomId'])) {
        $condition .= " AND a.roomId = '".$REQUEST_DATA['roomId']."'";
       }
    }
    

    $timeTableArray = $timeTableManager->getClassTimeTableAdvancedAllNew($periodSlotId, $timeTableLabelId, $classId, $condition);
    echo json_encode($timeTableArray);

?>