<?php
//-------------------------------------------------------
// Purpose: To get values of timetable from the database
//
// Author : Rajeev Aggarwal
// Created on : (31.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['studentClass'] ) != '' && trim($REQUEST_DATA['studentGroup'] ) != '' && trim($REQUEST_DATA['teacher'] ) != '' && trim($REQUEST_DATA['timeTableLabelId'] ) != '' ) {
	//global $sessionHandler;
	//$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	//$sessionId	 = $sessionHandler->getSessionVariable('SessionId');
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $foundArray = TimeTableManager::getInstance()->checkIntoTimeTable(' AND tt.subjectId="'.$REQUEST_DATA['subject'].'" AND tt.employeeId="'.$REQUEST_DATA['teacher'].'" AND tt.groupId="'.$REQUEST_DATA['studentGroup'].'" AND tt.timeTableLabelId="'.$REQUEST_DATA['timeTableLabelId'].'"');

    $resultsCount = count($foundArray);
	
	$periodArray = TimeTableManager::getInstance()->getPeriodList('AND p.periodSlotId='.$REQUEST_DATA['periodSlotId']);
	$periodCount = count($periodArray);

	if(is_array($periodArray) && $periodCount>0) {
        $jsonPeriod = '';
        for($s = 0; $s<$periodCount; $s++) {
            $jsonPeriod .= json_encode($periodArray[$s]). ( $s==($periodCount-1) ? '' : ',' );                }
    }

    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTable  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonTimeTable .= json_encode($foundArray[$s]). ( $s==($resultsCount-1) ? '' : ',' );                }
    }
	 

		echo '{"timeTableArr":['.$jsonTimeTable.'],"jsonPeriodArr":['.$jsonPeriod.']}';
}
// $History: ajaxGetValues.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/15/09    Time: 3:08p
//Updated in $/LeapCC/Library/TimeTable
//role permission added
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 3/04/09    Time: 2:10p
//Updated in $/LeapCC/Library/TimeTable
//added the condition for period slot in getPeriodList function
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/17/08   Time: 4:53p
//Updated in $/LeapCC/Library/TimeTable
//commented out $instituteId, $sessionId
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 10/07/08   Time: 5:43p
//Updated in $/Leap/Source/Library/TimeTable
//Added the functionality for Time Table Labels
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 9/20/08    Time: 12:59p
//Updated in $/Leap/Source/Library/TimeTable
//removed trailing spaces
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 9/20/08    Time: 12:56p
//Updated in $/Leap/Source/Library/TimeTable
//removed leading spaces
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 9/19/08    Time: 8:19p
//Updated in $/Leap/Source/Library/TimeTable
//replaced getTimeTable with checkIntoTimeTable function 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/25/08    Time: 4:07p
//Updated in $/Leap/Source/Library/TimeTable
//updated refresh screen for room
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:23p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
?>