<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$periodSlotId = $REQUEST_DATA['periodSlotId'];
$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
$classId = $REQUEST_DATA['studentClass'];
$totalRecords = count($REQUEST_DATA['teacherId']);

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();

require_once(MODEL_PATH . "/TimeTableConflictManager.inc.php");
$timeTableConflictManager = TimeTableConflictManager::getInstance();
$timeTableConflictManager->setProcess('show conflicts');
$return = $timeTableConflictManager->saveTimeTable();
if($return == SUCCESS){
	require_once(BL_PATH . "/Teacher/TeacherActivity/sendSmsAlertForChangeInTimeTable.php");
	require_once(BL_PATH . "/Student/sendSmsAlertForChangeInTimeTable.php");
}
echo $return;

//die(' File: '.__FILE__.'	Line: '.__LINE__);
/*
$noConflictFound = $timeTableConflictManager->checkConflict();

if ($noConflictFound === true) {
	$timeTableConflictManager->setProcess('save');
	$timeTableConflictManager->makeIds();
	$timeTableConflictManager->saveTimeTable();
}





$saveMode = true;
global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$sessionId = $sessionHandler->getSessionVariable('SessionId');
require_once(BL_PATH . '/TimeTable/ajaxInitAddAdvanced.php');
if ($success === true) {
	$periodsArray = $timeTableManager->getAllPeriods($postPeriodSlotId);
	$periodNumberArray = array();
	foreach($periodsArray as $periodRecord) {
		$periodNumberArray[$periodRecord['periodNumber']] = $periodRecord['periodId'];
	}

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {

		$conditions = '';
		$conditions2 = '';
		if (isset($REQUEST_DATA['day'])) {
			$conditions = " AND daysOfWeek = ".$REQUEST_DATA['day'];
			if (isset($REQUEST_DATA['roomId']) and !is_array($REQUEST_DATA['roomId'])) {
				$conditions2 = " AND roomId = ".$REQUEST_DATA['roomId'];
			}
			$return = $timeTableManager->updateCurrentTimeTable($postPeriodSlotId, $classId, $timeTableLabelId, $conditions, $conditions2);
		}
		else {
			$return = $timeTableManager->updateCurrentTimeTable($postPeriodSlotId, $classId, $timeTableLabelId);
		}
		if ($return == false) {
			echo ERROR_WHILE_CLEARING_PREVIOUS_TIME_TABLE;
			die;
		}
		$insertStr = '';
		foreach($allCombinationArray as $record) {
			list($teacherId,$subjectId,$groupId,$roomId,$dayId,$period) = explode('#',$record);
			if ($insertStr != '') {
				$insertStr .= ', ';
			}
			$insertStr .= "($roomId, $teacherId, $groupId, $instituteId, $sessionId, $dayId, $postPeriodSlotId, ".$periodNumberArray[$period].", $subjectId, CURRENT_DATE, NULL, $timeTableLabelId)";
		}
		if ($insertStr != '') {
			$return = $timeTableManager->addNewTimeTableInTransaction($insertStr);
			if ($return == false) {
				echo ERROR_WHILE_ADDING_NEW_TIME_TABLE;
				die;
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
		}
		else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
	}

}
*/
// $History: ajaxInitSaveAdvanced.php $
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:25a
//Updated in $/LeapCC/Library/TimeTable
//fixed issues:
//0001979
//0001973
//0001975
//0001927
//0001903
//0001902
//0001929
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/02/09   Time: 11:53a
//Updated in $/LeapCC/Library/TimeTable
//done changes to improve messaging. added code to update time table
//adjustment to update entries with new time table Id.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 10/27/09   Time: 1:28p
//Updated in $/LeapCC/Library/TimeTable
//stopped entry copying code.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 10/26/09   Time: 11:38a
//Updated in $/LeapCC/Library/TimeTable
//done changes for taking care of adjustment.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/06/09   Time: 11:09a
//Updated in $/LeapCC/Library/TimeTable
//applied changes for multi-slot time table.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/03/09   Time: 11:20a
//Updated in $/LeapCC/Library/TimeTable
//done changes to make compatible with class wise day wise room wise time
//table.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/01/09   Time: 4:37p
//Updated in $/LeapCC/Library/TimeTable
//changed module name define, and added logic for class wise day wise
//time table.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:14p
//Created in $/LeapCC/Library/TimeTable
//file added for class based time table
//

?>