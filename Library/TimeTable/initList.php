<?php
//-------------------------------------------------------
// Purpose: To store the records of timetable in array from the database, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (31.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	//echo "<pre>";
	//print_r($REQUEST_DATA);
	//die();
    global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timetableManager  = TimeTableManager::getInstance();
    
    define('MODULE','CreateTimeTable');
    define('ACCESS','view');
    
    UtilityManager::ifNotLoggedIn(true);

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
    if(UtilityManager::notEmpty($REQUEST_DATA['periodSlotId'])) {
        $condition =' AND ps.periodSlotId='.$REQUEST_DATA['periodSlotId'];
    }
    else {
        $condition = ' AND ps.isActive=1';
    }
	
	$filter = " AND rm.roomId IN (SELECT roomId FROM room_institute WHERE instituteId = $instituteId)";
    $periodRecordArray = $timetableManager->getPeriodList($condition);
	$results           = $timetableManager->getRoomList($filter,$limit);  
	
	$returnValues = '';
	if(isset($results) && is_array($results)) {
		$count = count($results);
		for($i=0;$i<$count;$i++) {
			if($results[$i]['roomId']==$selected) {
				$returnValues .='<option value="'.$results[$i]['roomId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomName']).'</option>';
			}
			else {
				$returnValues .='<option value="'.$results[$i]['roomId'].'">'.strip_slashes($results[$i]['roomName']).'</option>';
			}
		}
		
	}

// for VSS
// $History: initList.php $
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-10-12   Time: 11:53a
//Updated in $/LeapCC/Library/TimeTable
//Updated with Access right parameters
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Library/TimeTable
//applied check, so that only those rooms should come which belong to
//current instituteId
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 3:08p
//Updated in $/LeapCC/Library/TimeTable
//role permission added
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/16/08   Time: 6:53p
//Updated in $/LeapCC/Library/TimeTable
//make changes to incorporate Period Slots
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 10/31/08   Time: 6:58p
//Updated in $/Leap/Source/Library/TimeTable
//added "AND ttl.isActive=1" in else condition
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 10/25/08   Time: 6:01p
//Updated in $/Leap/Source/Library/TimeTable
//associated Time Table labels with Periods
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 9/20/08    Time: 5:47p
//Updated in $/Leap/Source/Library/TimeTable
//Removed unnecessary code
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:23p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
   
?>