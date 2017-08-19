<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (24.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignRollNumbers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId = $REQUEST_DATA['degree'];
$rollNoLength = $REQUEST_DATA['rollNoLength'];
$prefix = $REQUEST_DATA['prefix'];
$suffix = $REQUEST_DATA['suffix'];
$sorting = $REQUEST_DATA['sorting'];
$orderBy = $sorting == 'alphabetic' ? ' firstName, lastName, studentId' : ' studentId';

$rollNoDigitCount = intval($rollNoLength) - strlen($prefix) - strlen($suffix);

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();


$updatedArray = array(); //array created for showing results.

$rollNoCounter = 0;
if (!empty($REQUEST_DATA['startSeriesFrom'])) {
	if (is_numeric($REQUEST_DATA['startSeriesFrom']) and $REQUEST_DATA['startSeriesFrom'] > 0) {
		$rollNoCounter = $REQUEST_DATA['startSeriesFrom'] - 1;
	}
	else {
		echo INVALID_SERIES_STARTING_NUMBER;
		die;
	}
}

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {

	$classId = $REQUEST_DATA['degree'];
	$rollNoLength = $REQUEST_DATA['rollNoLength'];
	$prefix = $REQUEST_DATA['prefix'];
	$suffix = $REQUEST_DATA['suffix'];
	$sorting = $REQUEST_DATA['sorting'];
	$leet = false;
	if (isset($REQUEST_DATA['leet']) and $REQUEST_DATA['leet'] == 'on') {
		$leet = true;
	}
	$alreadyAssigned = false;
	if (isset($REQUEST_DATA['alreadyAssigned']) and $REQUEST_DATA['alreadyAssigned'] == 'on') {
		$alreadyAssigned = true;
	}
	$orderBy = $sorting == 'alphabetic' ? ' firstName, lastName, studentId' : ' studentId';
	$rollNoDigitCount = intval($rollNoLength) - strlen($prefix) - strlen($suffix);

	$conditions = '';

	if ($leet === true) {
		$conditions .= "";					//NEED TO INCLUDE ALL, IRRESPECTIVE OF LEET OR NOT
	}
	else {
		$conditions .= " AND isLeet IN (0) ";	//NEED TO INCLUDE ONLY NON-LEETS
	}
	if ($alreadyAssigned === true) {
		$conditions .= "";						//NEED TO INCLUDE ALL, IRRESPECTIVE OF ROLL NO. ASSIGNED OR NOT
	}
	else {
		$conditions .= " AND (rollNo = '' or rollNo is null) ";
	}
	
	$studentIdList = '0';

	$queryPart = '';
	$tableCtr = 0;
	$tables = '';
	$setCondition = '';
	$whereCondition = '';
	$unselectedUsers = $REQUEST_DATA['unselectedUsers'];


	$currentStudentArray = $studentManager->getStudents($classId, $orderBy, $conditions);
	if (is_array($currentStudentArray) and count($currentStudentArray))  {
		foreach($currentStudentArray as $studentRecord) {
			$studentId = $studentRecord['studentId'];
			$rollNo = $studentRecord['rollNo'];
			if ($studentIdList != '') {
				$studentIdList .= ', ';
			}
			$studentIdList .= $studentId;

			$studentUserIdArray = $studentManager->getStudentUserId($studentId);
			$studentUserId = $studentUserIdArray[0]['userId'];

			if (!empty($studentUserId)) {
				if (!empty($tables)) {
					$tables .= ',';
					$setCondition .= ',';
					$whereCondition .= ' AND ';
				}
				$rollNoMade = $studentUserId.'_'.rand(0,100)*rand(0,100).'_deleted';
				$tables .= "user as tm$tableCtr";
				$setCondition .= "tm$tableCtr.userName = '$rollNoMade'";
				$whereCondition .= "tm$tableCtr.userId = $studentUserId";
				$tableCtr++;
			}

			if ($tableCtr % 10 == 0) {
				if (!empty($tables)) {
					$return = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
					if ($return === false) {
						echo FAILURE;
						die;
					}
				}
				$tableCtr = 0;
				$tables = '';
				$setCondition = '';
				$whereCondition = '';
			}
		}
		if ($tableCtr > 0) {
			if (!empty($tables)) {
				$return = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
				if ($return === false) {
					echo FAILURE;
					die;
				}
			}
		}
	}


	$return = $studentManager->makeRollNoNullInTransaction($studentIdList);
	if ($return === false) {
		echo FAILURE;
		die;
	}

	$queryPart = '';
	$tableCtr = 0;
	$tables = '';
	$setCondition = '';
	$whereCondition = '';
	$tables2 = '';
	$setCondition2 = '';
	$whereCondition2 = '';

	$createUserCtr = 0;
	$tables3 = '';
	$setCondition3 = '';
	$whereCondition3 = '';

	$studentArray = $REQUEST_DATA['chk'];
	if (count($studentArray) == 0) {
		echo NO_STUDENT_SELECTED;
		die;
	}
	foreach($studentArray as $key => $studentId) {
		$actualRollNo = '';
		$stringRollNo = '';
		$rollNoCounter++;
		$rollNoCounterLength = strlen($rollNoCounter);
		if (($rollNoCounterLength + strlen($prefix) + strlen($suffix)) > $rollNoLength) {
			echo ROLL_NO_LENGTH_SHORT;
			die;
		}
		$studentUserIdArray = $studentManager->getStudentUserId($studentId);
		$studentUserId = $studentUserIdArray[0]['userId'];

		$cnt = 1;
		while ($cnt != 0) {
			$stringRollNo = '';
			$rollNoCounterLength = strlen($rollNoCounter);
			while($rollNoCounterLength < $rollNoDigitCount) {
				$stringRollNo .= "0";
				$rollNoCounterLength++;
			}
			$stringRollNo .= $rollNoCounter;
			$actualRollNo = $prefix . $stringRollNo . $suffix;
			$deletedCntArray = $studentManager->checkDeletedRollNo($actualRollNo);
			$cnt = $deletedCntArray[0]['cnt'];
			if ($cnt != 0) {
				$rollNoCounter++;
			}
		}

		if (!empty($studentUserId)) {
			//update user table's userName field.
			$return = $studentManager->updateRecordInTransaction('user', "userName = '$actualRollNo'", "userId = $studentUserId");
			if ($return === false) {
				echo FAILURE;
				die;
			}
			//update student table's rollNo field.
			$return = $studentManager->updateRecordInTransaction('student', "rollNo = '$actualRollNo'", "studentId = $studentId");
			if ($return === false) {
				echo FAILURE;
				die;
			}
		}
		else {
			//create user.
			$return = $studentManager->createUserInTransaction($actualRollNo, md5('change'), 4);
			if ($return == false) {
				echo ERROR_WHILE_CREATING_NEW_USER;
				die;
			}
			$newUserIdArray = $studentManager->getNewUserId();
			$newUserId = $newUserIdArray[0]['userId'];

			//put entry into user_role table
			$return = $studentManager->makeUserRoleInTransaction($newUserId,4);
			if ($return == false) {
				echo ERROR_WHILE_CREATING_NEW_USER_ROLE;
				die;
			}
			//update student table's rollNo field. and update student table's userId field.
			$return = $studentManager->updateRecordInTransaction('student', "rollNo = '$actualRollNo', userId = '$newUserId'", "studentId = $studentId");
			if ($return === false) {
				echo FAILURE;
				die;
			}
		}
	}

	$return = $studentManager->updateClassPrefixInTransaction($classId, $prefix);
	if ($return === false) {
		echo FAILURE;
		die;
	}
	$return = $studentManager->updateClassSuffixInTransaction($classId, $suffix);
	if ($return === false) {
		echo FAILURE;
		die;
	}
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo ROLL_NO_ASSIGNED_SUCCESSFULLY;
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



// for VSS
// $History: doRoleAssignment.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/31/09    Time: 3:47p
//Updated in $/LeapCC/Library/Student
//fixed bug no. 816
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/30/09    Time: 1:51p
//Updated in $/LeapCC/Library/Student
//fixed bug no.0000755
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/04/09    Time: 11:20a
//Updated in $/LeapCC/Library/Student
//fixed bug no. 955
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/18/08   Time: 11:58a
//Updated in $/LeapCC/Library/Student
//improved coding for roll no. assignment
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/26/08    Time: 12:39p
//Updated in $/Leap/Source/Library/Student
//done the common messaging
//
//*****************  Version 3  *****************
//User: Admin        Date: 8/05/08    Time: 12:03p
//Updated in $/Leap/Source/Library/Student
//file changed to make it as per new format
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/28/08    Time: 4:15p
//Updated in $/Leap/Source/Library/Student
//code added for updating class prefix
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/24/08    Time: 4:25p
//Created in $/Leap/Source/Library/Student
//file added for assigning roll no.s to students

?>
