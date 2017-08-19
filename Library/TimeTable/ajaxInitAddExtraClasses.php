<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExtraClassesTimeTable');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);            
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/TimeTableConflictManager.inc.php");
$timeTableConflictManager = TimeTableConflictManager::getInstance();

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();


$periodSlotId = $REQUEST_DATA['periodSlotId'];
$postPeriodSlotId = $REQUEST_DATA['periodSlotId'];
$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
$classId = $REQUEST_DATA['studentClass'];
$date = $REQUEST_DATA['toDate'];

$postDate = strtotime($date);
$currentDate = strtotime(date('Y-m-d'));

if ($postDate < $currentDate) {
	echo EXTRA_CLASSES_TIMETABLE_DATE_CANNOT_BE_PAST;
	die;
}



$totalRecords = count($REQUEST_DATA['teacherId']);

$timeTableConflictManager->setProcess('show conflicts');

$dateDay = date('N', strtotime($date));
$i = 0;
while ($i < $totalRecords) {
	$teacherId	=	$REQUEST_DATA['teacherId'][$i];
	$subjectId	=	$REQUEST_DATA['subjectId'][$i];
	$groupId	=	$REQUEST_DATA['groupId'][$i];
	$roomId		=	$REQUEST_DATA['roomId'][$i];
	$period		=	$REQUEST_DATA['period'][$i];
	$periodArray = explode(',', $period);
	$i++;

   foreach($periodArray as $period) {
	   $conflict = $timeTableConflictManager->checkConflict($teacherId, $subjectId, $groupId, $roomId, $dateDay, $period, $postPeriodSlotId, $timeTableLabelId, $date, $date, 0, 'extraLecture');
	   if($conflict != NO_CONFLICTS_FOUND) {
		   echo $conflict;
		   die;
	   }
   }

}
$doProcess = $REQUEST_DATA['do'];
if ($doProcess == 'checkConflicts') {
	echo NO_CONFLICTS_FOUND;
	die;
}

if ($doProcess == 'saveExtraClasses') {
	$periodsArray = $timeTableManager->getAllPeriods($postPeriodSlotId);
	$periodNumberArray = array();
	foreach($periodsArray as $periodRecord) {
		$periodNumberArray[$periodRecord['periodNumber']] = $periodRecord['periodId'];
	}

    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $userId = $sessionHandler->getSessionVariable('UserId');

	$insStr='';
	$i = 0;
	while ($i < $totalRecords) {
		$teacherId	=	$REQUEST_DATA['teacherId'][$i];
		$subjectId	=	$REQUEST_DATA['subjectId'][$i];
		$groupId	=	$REQUEST_DATA['groupId'][$i];
		$roomId		=	$REQUEST_DATA['roomId'][$i];
		$period		=	$REQUEST_DATA['period'][$i];

		if($insStr != ''){
			$insStr .= ",";
		}
		$insStr .= "(NULL, $roomId, $teacherId, $groupId, $instituteId, $sessionId, $dateDay, $postPeriodSlotId, ".$periodNumberArray[$period].", $subjectId, '$date', '$date', 1, $timeTableLabelId, $userId, $teacherId, CURRENT_DATE, NULL, NULL, 4)";
		$i++;
	}

	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$return = $timeTableManager->updateOldEntries($postPeriodSlotId, $timeTableLabelId, $classId, $date);
		if($return === false) {
			echo FAILURE;
			die;  
		}
		if ($insStr != '') {
			$return = $timeTableManager->addAdjustedTimeTableRecords($insStr);
			if($return === false) {
				echo FAILURE;
				die;  
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
			die;
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
		echo FAILURE;
		die;
	}
}

// $History: ajaxInitAddExtraClasses.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:57a
//Created in $/LeapCC/Library/TimeTable
//file added for extra classes


?>