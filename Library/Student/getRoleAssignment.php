<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (14.07.2008 )
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

	$classId = add_slashes($REQUEST_DATA['degree']);
	$rollNoLength = add_slashes($REQUEST_DATA['rollNoLength']);
	$prefix = add_slashes($REQUEST_DATA['prefix']);
	$suffix = add_slashes($REQUEST_DATA['suffix']);
	$sorting = add_slashes($REQUEST_DATA['sorting']);
	$leet = false;
	if (isset($REQUEST_DATA['leet']) and $REQUEST_DATA['leet'] == 'on') {
		$leet = true;
	}
	$alreadyAssigned = false;
	if (isset($REQUEST_DATA['alreadyAssigned']) and $REQUEST_DATA['alreadyAssigned'] == 'on') {
		$alreadyAssigned = true;
	}
	$orderBy = $sorting == 'alphabetic' ? ' firstName, lastName, studentId' : ' regNo';

	$rollNoDigitCount = intval($rollNoLength) - strlen($prefix) - strlen($suffix);

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$conditions = '';

	if ($leet === true) {
		$conditions .= "  ";					//NEED TO INCLUDE ALL, IRRESPECTIVE OF LEET OR NOT
	}
	else {
		$conditions .= " AND isLeet IN (0) ";	//NEED TO INCLUDE ONLY NON-LEETS
	}
	if ($alreadyAssigned === true) {
		$conditions .= " ";						//NEED TO INCLUDE ALL, IRRESPECTIVE OF ROLL NO. ASSIGNED OR NOT
	}
	else {
		$conditions .= " AND (rollNo = '' or rollNo is null) ";
	}
	

	$studentArray = $studentManager->getStudents($classId, $orderBy, $conditions);

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

	$displayCounter = 0;

	$rollNoList = '';
	$studentIdList = '';
	$studentUserIdList = '0';

	if (is_array($studentArray) and count($studentArray))  {
		foreach($studentArray as $studentRecord) {
			$studentId = $studentRecord['studentId'];
			$oldRollNo = $studentRecord['rollNo'];

			$studentUserIdArray = $studentManager->getStudentUserId($studentId);
			$studentUserId = $studentUserIdArray[0]['userId'];

			/*
			$stringRollNo = '';
			$rollNoCounter++;
			$rollNoCounterLength = strlen($rollNoCounter);
			while($rollNoCounterLength < $rollNoDigitCount) {
				$stringRollNo .= "0";
				$rollNoCounterLength++;
			}
			$stringRollNo .= $rollNoCounter;
			$actualRollNo = $prefix . $stringRollNo . $suffix;
			*/

			$cnt = 1;
			while ($cnt != 0) {
				$stringRollNo = '';
				$rollNoCounter++;
				$rollNoCounterLength = strlen($rollNoCounter);
				while($rollNoCounterLength < $rollNoDigitCount) {
					$stringRollNo .= "0";
					$rollNoCounterLength++;
				}
				$stringRollNo .= $rollNoCounter;
				$actualRollNo = $prefix . $stringRollNo . $suffix;
				$deletedCntArray = $studentManager->checkDeletedRollNo($actualRollNo);
				$cnt = $deletedCntArray[0]['cnt'];
			}
			$updatedArray[] = array('srNo'=>++$displayCounter, 'studentId'=>$studentId, 'regNo'=>$studentRecord['regNo'], 'studentName'=>$studentRecord['studentName'], 'rollNo'=>$actualRollNo, 'oldRollNo' => $oldRollNo);
			
			if (!empty($rollNoList)) {
				$rollNoList .= ', ';
				$studentIdList .= ', ';
				
			}
			$rollNoList .= "'".$actualRollNo."'";
			$studentIdList .= $studentId;
			if (!empty($studentUserId)) {
				$studentUserIdList .= ',';
				$studentUserIdList .= $studentUserId;
			}
		}
		$rollNoExistArray = $studentManager->checkRollNo($rollNoList, $studentIdList);
		$userExistArray = $studentManager->checkUserName($rollNoList, $studentUserIdList);
		
		if (intval($rollNoExistArray[0]['cnt']) != 0) {
			echo SERIES_ROLL_NO_ASSIGNED_ALREADY;
			die;
		}
		elseif (intval($userExistArray[0]['cnt']) != 0) {
			echo USERNAMES_EXIST_ALREADY;
			die;
		}
		else {
			echo json_encode($updatedArray);
		}
	}
	else {
		echo json_encode($studentArray);
	}

   
// for VSS
// $History: getRoleAssignment.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/04/09    Time: 1:22p
//Updated in $/LeapCC/Library/Student
//fixed bug no.s 842, 841, 840, 839, 814, 813, 812
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
//User: Ajinder      Date: 12/18/08   Time: 11:59a
//Updated in $/LeapCC/Library/Student
//improved coding for roll no. assignment
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 12:39p
//Updated in $/Leap/Source/Library/Student
//done the common messaging
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/25/08    Time: 4:24p
//Created in $/Leap/Source/Library/Student
//file added for "roll no. assignment"

?>
