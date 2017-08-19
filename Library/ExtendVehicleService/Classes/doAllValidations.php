<?php
////  This File does the whole validation
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

//sessionId=1&batchId=2&universityId=1&degreeId=1&branchId=2&degreeDurationId=3&periodicityId=1&lastSessionYear=&sessionYear=&batchYear=&classDetails=Get Study Periods&description=&sp_1=2&sp_2=2&sp_3=1&sp_4=2&sp_5=3&sp_6=2

require_once(MODEL_PATH . "/ClassesManager.inc.php");
$classManager = ClassesManager::getInstance();

if (!isset($REQUEST_DATA['sessionId']) or trim($REQUEST_DATA['sessionId']) == '') {
	echo SELECT_SESSION;
	die;
}
if (!isset($REQUEST_DATA['batchId']) or trim($REQUEST_DATA['batchId']) == '') {
	echo SELECT_BATCH;
	die;
}
if (!isset($REQUEST_DATA['universityId']) or trim($REQUEST_DATA['universityId']) == '') {
	echo SELECT_UNIVERSITY;
	die;
}
if (!isset($REQUEST_DATA['degreeId']) or trim($REQUEST_DATA['degreeId']) == '') {
	echo SELECT_DEGREE;
	die;
}
if (!isset($REQUEST_DATA['branchId']) or trim($REQUEST_DATA['branchId']) == '') {
	echo SELECT_BRANCH;
	die;
}
if (!isset($REQUEST_DATA['degreeDurationId']) or trim($REQUEST_DATA['degreeDurationId']) == '') {
	echo SELECT_DEGREE_DURATION;
	die;
}
if (!isset($REQUEST_DATA['periodicityId']) or trim($REQUEST_DATA['periodicityId']) == '') {
	echo SELECT_PERIODICITY;
	die;
}

$sessionYearArray = $classManager->getSessionYear($REQUEST_DATA['sessionId']);
$sessionYear = $sessionYearArray[0]['sessionYear'];
if (empty($sessionYear)) {
	echo INVALID_SESSION_YEAR;
	die;
}
$batchYearArray = $classManager->getBatchYear($REQUEST_DATA['batchId']);
$batchYear = $batchYearArray[0]['batchYear'];
if (empty($batchYear)) {
	echo INVALID_BATCH_YEAR;
	die;
}

$universityIdArray = $classManager->getUniversityId($REQUEST_DATA['universityId']);
$universityId = $universityIdArray[0]['universityId'];
if (empty($universityId)) {
	echo INVALID_UNIVERSITY;
	die;
}

$degreeIdArray = $classManager->getDegreeId($REQUEST_DATA['degreeId']);
$degreeId = $degreeIdArray[0]['degreeId'];
if (empty($degreeId)) {
	echo INVALID_DEGREE;
	die;
}

$branchIdArray = $classManager->getBranchId($REQUEST_DATA['branchId']);
$branchId = $branchIdArray[0]['branchId'];
if (empty($branchId)) {
	echo INVALID_BRANCH;
	die;
}

$degreeDuration = $REQUEST_DATA['degreeDurationId'];
$ttDegreeDuration = $REQUEST_DATA['degreeDurationId'];
if (!is_numeric($degreeDuration) or ($degreeDuration < 1) or ($degreeDuration > 6)) {
	echo INVALID_DEGREE_DUATION;
	die;
}


$lastSessionYear = 0;

if ($sessionYear < $batchYear) {
	echo SESSION_STARTING_BEFORE_BATCH;
	die;
}

$degreeDuration = $REQUEST_DATA['degreeDurationId'];    
$periodicityId = $REQUEST_DATA['periodicityId'];
$lastSessionYear = $batchYear + (ceil($degreeDuration) - 1); //last session year is decremented 'coz 2008 + 3 = 2011 (but degree year will end in 2010)


if ($sessionYear > $lastSessionYear) {
	echo SESSION_STARTING_AFTER_DEGREE;
	die;
}

$sessionYearArray = range($batchYear, ceil($lastSessionYear));
$allYearsFound = true;
foreach($sessionYearArray as $key => $value) {
	$recordArray = $classManager->countSessionYear($value);
	$count = $recordArray[0]['count'];
	if ($count == 0) {
		echo "Session year $value not found"; //THIS STRING IS NOT SET IN COMMON MESSAGES, BECAUSE IT CONTAINS A VARIABLE.
		die;
	}
}

$pfArray = $classManager->getPeriodicityFrequencyById($periodicityId);
$periodicityFrequency = $pfArray[0]['periodicityFrequency'];
if (empty($periodicityFrequency)) {
	echo INVALID_PERIODICITY;
	die;
}
$totalPeriods = $periodicityFrequency * $degreeDuration;
$spArray = $classManager->getStudyPeriods($periodicityId, $totalPeriods);
$allSpValid = true;
$activeFound = 0;
$unSelected = 0;
foreach($spArray as $spRecord) {
	$spId = $spRecord['studyPeriodId'];
	$spName = $spRecord['periodName'];
	if (!isset($REQUEST_DATA["sp_".$spId])) {
		echo STUDY_PERIOD_NOT_EXIST;
		die;
	}
	else {
		if (intval($REQUEST_DATA["sp_".$spId]) == 1) {
			$activeFound++;
			if ($activeFound > 1) {
				echo ONE_STUDY_PERIOD_CAN_ACTIVE;
				die;
			}
		}
		else if(intval($REQUEST_DATA["sp_".$spId]) === 0) {
			echo ALL_STUDY_PERIOD_MUST_HAVE_VALUE;
			die;
		}
	}
	$thisSPIdValue = $REQUEST_DATA["sp_".$spId];
	foreach($spArray as $spRecordOld) {
		$oldSpId = $spRecordOld['studyPeriodId'];
		$oldSpName = $spRecordOld['periodName'];
		if ($oldSpId >= $spId) {
			break;
		}
		$oldSPIdValue = intval($REQUEST_DATA["sp_".$oldSpId]);
		if ($thisSPIdValue == 1) {
			if ($oldSPIdValue == 2) {
				echo "'$spName' Can not be Active, As '$oldSpName' is Future ";
				die;
			}
		}
		elseif ($thisSPIdValue == 3) {
			if ($oldSPIdValue == 1) {
				echo "'$spName' Can not be Past, As '$oldSpName' is Active ";
				die;
			}
			elseif ($oldSPIdValue == 2) {
				echo "'$spName' Can not be Past, As '$oldSpName' is Future";
				die;
			}
		}
		elseif ($thisSPIdValue == 4) {
			if ($oldSPIdValue == 1) {
				echo "'$spName' Can not be Unused, as '$oldSpName' is Active ";
				die;
			}
			elseif ($oldSPIdValue == 2) {
				echo "'$spName' Can not be Unused, as '$oldSpName' is Future ";
				die;
			}
			elseif ($oldSPIdValue == 3) {
				echo "'$spName' Can not be Unused, as '$oldSpName' is Past ";
				die;
			}
		}

	}
}


if ($activeFound == 0) {
	echo NO_STUDY_PERIOD_ACTIVE;
	die;
}
if ($allSpValid == true) {
	$currentSessionYearArray = $classManager->getActiveSessionYear();
	$currentSessionYear = $sessionHandler->getSessionVariable('SessionYear');
	if ($batchYear > $currentSessionYear) {
		echo CLASSES_FOR_BATCH_.$batchYear._CAN_NOT_BE_MADE_IN_SESSION_.$currentSessionYear;
		die;
	}
	$yearsPassed = $currentSessionYear - $batchYear;
	if ($yearsPassed < 0) {
		echo FAILURE;
		die;
	}
	$yearsPassed = $sessionYear - $batchYear;
	$periodsPassed = $yearsPassed * $periodicityFrequency;
	$periodCounter = 0;
	$validPeriodActive = false;
        
	if ($REQUEST_DATA['task'] == 'Add') {
		$currentYear = $yearsPassed + 1;
		$maxActiveValue = $periodsPassed;
		$thisYearPeriodsPassed = $currentYear * $periodicityFrequency;
        while ($maxActiveValue < $thisYearPeriodsPassed) {
			$thisStudyPeriodId = $spArray[$maxActiveValue]['studyPeriodId'];
			if (intval($REQUEST_DATA["sp_".$thisStudyPeriodId]) == 1) {
				$validPeriodActive = true;
				break;
			}
			$maxActiveValue++;
		}
	}
	else {
		while($periodCounter <= $periodicityFrequency) {
			$nextPeriod = $periodsPassed + $periodCounter;
			$thisStudyPeriodIdArray = $classManager->getThisStudyPeriod($REQUEST_DATA['classId'], $nextPeriod);
			$thisStudyPeriodId = $thisStudyPeriodIdArray[0]['studyPeriodId'];
			if (intval($REQUEST_DATA["sp_".$thisStudyPeriodId]) == 1) {
				$validPeriodActive = true;
				break;
			}
			$periodCounter++;
		}
	}
    
    //At present we only have provision for making classes having duration in integer multiple of years(say 1 year, 4 years etc). 
    //But there are certain classes which are of duration which is non integer multiple of year(MPhil is of duration 3 Sems or 1 and half years) 
    //we have to make provision for this.
    if($ttDegreeDuration==1.5) {
      die; 
    }
    else {
	  if($validPeriodActive == false) {
		echo INVALID_STUDY_PERIOD_ACTIVE;
		die;
	  }
    }
}
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$sessionUpdater = 1; //for updating session

if ($REQUEST_DATA['task'] == 'Add') {
	foreach($spArray as $record) {
		$studyPeriodId = $record['studyPeriodId'];
		$periodName = $record['periodName'];
		$fetchedData = $REQUEST_DATA["sp_".$studyPeriodId];
		$classNameArray = $classManager->getClassName($periodName);

		$className = $classNameArray[0]['className'];
		if ($sessionUpdater == 1) {
			$nextSessionIdArray = $classManager->getSessionId($batchYear);
			$nextSessionId = $nextSessionIdArray[0]['sessionId'];
		}
		else if ($sessionUpdater == $periodicityFrequency) {
			$sessionUpdater = 0;
			//fetch the next session year id
			$nextSessionIdArray = $classManager->getNextSessionYearId($nextSessionId);
			$nextSessionId = $nextSessionIdArray[0]['sessionId'];
		}
		if (empty($nextSessionId)) {
			echo NEXT_SESSION_NOT_FOUND;
			die;
		}

		$foundArray = $classManager->getClass(" WHERE sessionId = ".$nextSessionId." AND batchId = ".$REQUEST_DATA['batchId']." AND universityId = ".$REQUEST_DATA['universityId']." AND degreeId  = ".$REQUEST_DATA['degreeId'] . " AND branchId = ".$REQUEST_DATA['branchId']." AND instituteId = $instituteId AND studyPeriodId = $studyPeriodId");

		if(trim($foundArray[0]['classId']) !='') {  //DUPLICATE CHECK
			echo CLASS_ALREADY_EXIST; //can not continue even if one class is found.
			die;
		}
		$sessionUpdater++;
	}
}

echo "";

// $History: doAllValidations.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 6:06p
//Updated in $/LeapCC/Library/Classes
//added code for class updation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/26/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Classes
//done the common messaging
//
//*****************  Version 2  *****************
//User: Kabir        Date: 9/08/08    Time: 12:39p
//Updated in $/Leap/Source/Library/Classes
//fixed a simple php tag parsing error
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:38p
//Created in $/Leap/Source/Library/Classes
//file added for doing all validations from server end
//


?>