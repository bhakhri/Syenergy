<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 01-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UpdateTotalMarks');
	define('ACCESS','edit');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
    
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

	$classId = trim($REQUEST_DATA['classId']);
	$subjectId = trim($REQUEST_DATA['subjectId']);
	$studentId = trim($REQUEST_DATA['studentId']);
	$finalMarks = trim($REQUEST_DATA['finalMarks']);

	if (!is_numeric($classId) or !is_numeric($subjectId)  or !is_numeric($studentId)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	if ($classId < 0 or $subjectId < 0  or $studentId < 0) {
		echo INVALID_DETAILS_FOUND;
		die;
	}


	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$returnStatus = $studentManager->makeOldResultInActive($classId, $studentId, $subjectId);
		if ($returnStatus == false) {
			echo FAILURE;
			die;
		}
		$returnStatus = $studentManager->makePrevResultInActive($classId, $studentId, $subjectId);
		if ($returnStatus == false) {
			echo FAILURE;
			die;
		}
		if ($finalMarks == 'regular') {
			$returnStatus = $studentManager->makeOldResultActive($classId, $studentId, $subjectId);
			if ($returnStatus == false) {
				echo FAILURE;
				die;
			}
		}
		else {
			$marksArray2 = $studentManager->getReappearMarks($studentId,$classId,$subjectId);
			$isValid = false;
			$updateDateTime = '';
			foreach($marksArray2 as $record) {
				$marksUpdationId = $record['marksUpdationId'];
				if ($marksUpdationId == $finalMarks) {
					$isValid = true;
					$updateDateTime = $record['updateDateTime'];
					break;
				}
			}
			if ($isValid == false) {
				echo INVALID_DETAILS_FOUND;
				die;
			}
			$return = $studentManager->makeMarksActive($updateDateTime, $studentId,$classId,$subjectId);
			if ($return == false) {
				echo FAILURE;
				die;
			}
		}
		####################	CGPA CODE STARTS ###################################
		$studentMarksArray = $studentManager->getStudentOldCGPA($studentId,$classId);
		$oldCGPAExists = false;
		$studentsArray = array();

		foreach($studentMarksArray as $record) {
			$totalGradeIntoPoints = $record['gradeIntoCredits'];
			$totalCredits = $record['credits'];
			$oldCGPAExists = true;
		}
		$studentManager->addCgpaLog($studentId,$classId,$totalGradeIntoPoints, $totalCredits,$reason);

		
		$newMarksArray = $studentInformationManager->getStudentCGPA($studentId,$classId);

		$totalGradePoints = 0;
		$totalCredits = 0;
		foreach($newMarksArray as $newRecord) {
			$totalGradePoints += $newRecord['gradePoints'] * $newRecord['credits'];
			$totalCredits += $newRecord['credits'];
		}
		if ($oldCGPAExists) {
			$returnStatus = $studentManager->updateStudentCGPA($studentId,$classId,$totalGradePoints,$totalCredits);
		}
		else {
			$returnStatus = $studentManager->insertStudentCGPA($studentId,$classId,$totalGradePoints,$totalCredits);
		}

		if ($returnStatus == false) {
			echo ERROR_OCCURED_WHILE_UPDATING_CGPA;
			exit;
		}
		####################	CGPA CODE ENDS ###################################
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


?>


<?php
// $History: scSaveHoldUnholdResult.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/01/09    Time: 5:04p
//Created in $/Leap/Source/Library/ScStudent
//file added for hold/unhold result.
//



?>