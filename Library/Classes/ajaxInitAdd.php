<?php
/*
This File calls addFunction used in adding class Records

Author :Ajinder Singh
Created on : 23-July-2008
Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CreateClass');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
//	require_once(BL_PATH . '/Classes/doAllValidations.php');
	$errorMessage ='';

	require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classManager = ClassesManager::getInstance();

	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$degreeDuration = $REQUEST_DATA['degreeDurationId'];

	$periodicityId = $REQUEST_DATA['periodicityId'];
	$pfArray = $classManager->getPeriodicityFrequencyById($periodicityId);
	$periodicityFrequency = $pfArray[0]['periodicityFrequency'];
	$totalPeriods = $periodicityFrequency * $degreeDuration;

	$recordsArray = $classManager->getStudyPeriods($periodicityId, $totalPeriods);
	$sessionUpdater = 0; //for updating session 
	$nextSessionId = '';

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
	
		foreach($recordsArray as $record) {
			$studyPeriodId = $record['studyPeriodId'];
			$periodName = $record['periodName'];
			$isActive = $REQUEST_DATA["sp_".$studyPeriodId];
			$classNameArray = $classManager->getClassName($periodName);
			$className = $classNameArray[0]['className'];

			if ($sessionUpdater == 0) {
				$batchYearArray = $classManager->getBatchYear($REQUEST_DATA['batchId']);
				$batchYear = $batchYearArray[0]['batchYear'];
				$nextSessionIdArray = $classManager->getSessionId($batchYear);
				$nextSessionId = $nextSessionIdArray[0]['sessionId'];
			}
			else if ($sessionUpdater%$periodicityFrequency == 0) {
				//fetch the next session year id
				$nextSessionIdArray = $classManager->getNextSessionYearId($nextSessionId);
				$nextSessionId = $nextSessionIdArray[0]['sessionId'];
			}
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');

			$foundArray = $classManager->getClass(" WHERE sessionId = ".$nextSessionId." AND batchId = ".$REQUEST_DATA['batchId']." AND universityId = ".$REQUEST_DATA['universityId']." AND degreeId  = ".$REQUEST_DATA['degreeId'] . " AND branchId  = ".$REQUEST_DATA['branchId'] . " AND instituteId = $instituteId AND studyPeriodId = $studyPeriodId");  

			if(trim($foundArray[0]['classId']) !='') {  //DUPLICATE CHECK
				echo CLASS_ALREADY_EXIST; //can not continue even if one class is found.
				die;
			}
			$return = $classManager->addClassInTransaction($studyPeriodId, $className, $isActive, $nextSessionId);
			if ($return == false) {
				echo ERROR_WHILE_MAKING_CLASS;
				die;
			}
			$sessionUpdater++;
		}
		$countArray = $classManager->countMultiplePeriodicity($REQUEST_DATA['batchId'], $REQUEST_DATA['degreeId'], $REQUEST_DATA['universityId'], $REQUEST_DATA['branchId'], $instituteId);
		$count = $countArray[0]['cnt'];
		if ($count > 1) {
			echo CLASS_WITH_SAME_DETAILS_ALREADY_EXISTS;
			die;
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

// $History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/29/09    Time: 6:06p
//Updated in $/LeapCC/Library/Classes
//added code for branch while adding class.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Classes
//done the common messaging
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:39p
//Created in $/Leap/Source/Library/Classes
//file added for adding class

?>

