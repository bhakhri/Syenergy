<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignGroupAdvanced');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$labelId =  $REQUEST_DATA['labelId'];  
$classId = trim($REQUEST_DATA['degree']);
$sortBy = trim($REQUEST_DATA['sortBy']);
if ($classId == '' or $sortBy == '') {
	echo 'Missing required parameteres';
	die;
}

$actualGroupAllocationArray = array();

$labelDateArray = $studentManager->getLabelDate($labelId);
$labelDate = $labelDateArray[0]['endDate'];


    

$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$sessionId = $sessionHandler->getSessionVariable('SessionId');

$groupsArray = $studentManager->getClassAllGroups($classId);

$newGroupArray = array();
foreach($groupsArray as $groupRecord) {
	$newGroupArray[$groupRecord['groupId']] = array('groupShort'=>$groupRecord['groupShort'], 'parentGroupId'=>$groupRecord['parentGroupId'], 'groupTypeId'=>$groupRecord['groupTypeId'], 'groupTypeName'=>$groupRecord['groupTypeName']);
}

$studentGroupArray = $studentManager->getStudentWithAttendanceTest($classId);

$studentGroupIdList = UtilityManager::makeCSList($studentGroupArray,'studentGroupId');
$studentGroupIdArray = explode(',', $studentGroupIdList);

$studentIdArray = $studentManager->getClassAllStudents($classId);
if(count($studentIdArray)==0) {
   $studentIdArray = $studentManager->getStudentGroupAllocationList($classId,$labelDate); 
}


$newStudentGroupArray = array();
foreach($studentIdArray as $studentRecord) {
   $newStudentGroupArray[$studentRecord['studentId']] = array('rollNo'=> $studentRecord['rollNo'],'studentName'=> $studentRecord['studentName']);
}


$chk = $REQUEST_DATA['chk'];
$inconsistencyArray = array();
if (count($chk)) {
	foreach($chk as $groupId => $studentArray) {
		foreach($studentArray as $studentId) {
			if (!is_array($actualGroupAllocationArray[$studentId])) {
				$actualGroupAllocationArray[$studentId] = array();
			}
			/*
			if (in_array($newGroupArray[$groupId]['groupTypeId'], $actualGroupAllocationArray[$studentId])) {
				$inconsistencyArray[] = MORE_THAN_ONE_GROUP_ALLOCATION_FOR_.$newStudentGroupArray[$studentId]['studentName'].' ('.$newStudentGroupArray[$studentId]['rollNo'].')'._FOR_GROUP_TYPE_.$newGroupArray[$groupId]['groupTypeName'];
				continue;
			}
			*/
			$actualGroupAllocationArray[$studentId][] = $newGroupArray[$groupId]['groupTypeId'];
		}
	}
}


$studentIdList = UtilityManager::makeCSList($studentIdArray,'studentId');
$studentIdArray = explode(',', $studentIdList);



if (count($chk)) {
	foreach($chk as $groupId => $studentArray) {
		if (!array_key_exists($groupId, $newGroupArray)) {
			$inconsistencyArray[] = INVALID_GROUP_ENTERED;
			break;
		}
        /*
		foreach($studentArray as $studentId) {
			if (!in_array($studentId, $studentIdArray)) {
				$inconsistencyArray[] = INVALID_STUDENT_FOUND;
				break;
			}
		}
        */
	}
}


$checkForMissingGroups = false;
if ($checkForMissingGroups === true) {
	if (!count($inconsistencyArray)) {
		foreach($studentGroupArray as $studentGroupRecord) {
			$studentId = $studentGroupRecord['studentId'];
			$groupId = $studentGroupRecord['groupId'];
			if (!is_array($chk) or !array_key_exists($groupId, $chk)) {
				$inconsistencyArray[$groupId] = GROUP_ALLOCATION_MISSING_FOR_.$newGroupArray[$groupId]['groupShort'];
				continue;
			}
			if (!in_array($studentId, $chk[$groupId])) {
				$rollNo = $studentGroupRecord['rollNo'];
				$studentName = $studentGroupRecord['studentName'];
				$inconsistencyArray[] = ATTENDANCE_TESTS_ALREADY_ENTERED_FOR_STUDENT.' '.$studentName.'('.$rollNo.')'._IN_GROUP_.$newGroupArray[$groupId]['groupShort'];
			}
		}
	}
}

if (!count($inconsistencyArray)) {
	if (count($chk)) {
		foreach($chk as $groupId => $studentArray) {
			$parentGroupId = $newGroupArray[$groupId]['parentGroupId'];
			if ($parentGroupId != 0) {
				foreach($studentArray as $studentId) {
					if (!is_array($chk[$parentGroupId]) or !in_array($studentId, $chk[$parentGroupId])) {
						$inconsistencyArray[] = PARENT_GROUP_NOT_SELECTED_FOR_STUDENT.' '.$newStudentGroupArray[$studentId]['studentName'].'('.$newStudentGroupArray[$studentId]['rollNo'].')'._FOR_GROUP_.$newGroupArray[$groupId]['groupShort'];
					}
				}
			}
		}
	}
}

if (count($inconsistencyArray)) {
	$ctr = 1;
	echo "<u><b>Following inconsistencies found during saving groups</b></u><br>";
	foreach($inconsistencyArray as $record) {
		echo "<br>".$ctr.'. '.$record;
		$ctr++;
	}
	//echo implode("<br>",$inconsistencyArray);
}
else {
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {

		$return = $studentManager->removeStudentGroupAllocation($classId);
		if ($return == false) {
			echo ERROR_WHILE_REMOVING_CURRENT_GROUP_ALLOCATION;
			die;
		}
		$insertArray = array();
		$chk = $REQUEST_DATA['chk'];
		foreach($chk as $groupId => $studentArray) {
			if (!isset($newGroupArray[$groupId])) {
				echo $groupId. 'not found';
				die;
			}
			foreach($studentArray as $key => $studentId) {
				$insertArray[] = "($studentId, $classId, $groupId, $instituteId, $sessionId)";
			}
		}
		if (count($insertArray)) {
			$insertStr = implode(',', $insertArray);
			$return = $studentManager->addStudentGroupAllocation($insertStr);
			if ($return == false) {
				echo ERROR_WHILE_UPDATING_GROUP_ALLOCATION;
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

//$History: initStudentAssignGroupAdvanced.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/30/10    Time: 3:03p
//Updated in $/LeapCC/Library/Student
//fixed query error. FCNS No.1492
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 12/29/09   Time: 5:24p
//Updated in $/LeapCC/Library/Student
//fixed issue of error coming on saving without selecting any group.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 12/02/09   Time: 3:56p
//Updated in $/LeapCC/Library/Student
//applied check back.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 11/16/09   Time: 5:38p
//Updated in $/LeapCC/Library/Student
//done changes to fix bug no. 1965
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:14p
//Created in $/LeapCC/Library/Student
//file added for assign groups advanced
//




?>