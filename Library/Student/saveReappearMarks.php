<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 01-May-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	$gradeId = trim($REQUEST_DATA['gradeId']);
    
   
	if (!is_numeric($classId) or !is_numeric($subjectId)  or !is_numeric($studentId)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	if ($classId < 0 or $subjectId < 0  or $studentId < 0) {
		echo INVALID_DETAILS_FOUND;
		die;
	}

	$countTotalMarksArray = $studentManager->countMarks($classId, $studentId, $subjectId); //count total records
	$cnt = $countTotalMarksArray[0]['cnt'];
	if ($cnt == 0) {
		echo NO_DATA_FOUND;
		die;
	}


	$getOldGradeArray = $studentManager->getGradeSetGrades($classId, $studentId, $subjectId); //to verify
	$oldGradeIdArray = explode(',', UtilityManager::makeCSList($getOldGradeArray, 'gradeId'));
	if ($gradeId != '' and !in_array($gradeId, $oldGradeIdArray)) {
		echo INVALID_GRADE_FOUND;
		die;
	}

	$gradeId = trim($gradeId);

	if($gradeId=='') {
	  $gradeId = "NULL";
	}

	$getOldGradeDetailsArray = $studentManager->getGradeSetGradingLabel($classId, $studentId, $subjectId); //fetch the grade_set, grading labelId
	$gradeSetId = $getOldGradeDetailsArray[0]['gradeSetId'];
	$gradingLabelId = $getOldGradeDetailsArray[0]['gradingLabelId'];

    if($gradeSetId=='') {
      $gradeSetId = "NULL";  
    }
    
    if($gradingLabelId=='') {
      $gradingLabelId = "NULL"; 
    }

	$reappearReason = trim($REQUEST_DATA['reappearReason']);
	if (empty($reappearReason)) {
		echo ENTER_REASON;
		die;
	}
	$reappearReason = add_slashes($reappearReason);

	$reappearExam = $REQUEST_DATA['reappearExam'];
	if ($reappearExam != 1 and $reappearExam != 2 and $reappearExam != 3 and $reappearExam != 4 and $reappearExam != 5) {
		echo INVALID_OPTION_SELECTED;
		die;
	}
	$marksSelectArray = array('A', 'UMC', 'I', 'MU', 'Marks');

	$finalMarksArray = array();

	$userId = $sessionHandler->getSessionVariable('UserId');

	if ($reappearExam == 1) {	# DIFFERENT MARKS FOR ATTENDANCE / PRE-COMPRE / COMPRE
		$updateAttendanceSelect = $REQUEST_DATA['updateAttendanceSelect'];
		if ($updateAttendanceSelect != 'Marks') {
			echo INVALID_OPTION_SELECTED_FOR_ATTENDANCE;
			die;
		}
		$updateAttendanceMarksScored = $REQUEST_DATA['updateAttendanceMarksScored'];
		$updateAttendanceMaxMarks = $REQUEST_DATA['updateAttendanceMaxMarks'];
		if (!is_numeric($updateAttendanceMarksScored) or $updateAttendanceMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updateAttendanceMaxMarks) or $updateAttendanceMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updateAttendanceMaxMarks < $updateAttendanceMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}


		$finalMarksArray[] = array('conductingAuthority' => ATTENDANCE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updateAttendanceMaxMarks, 'marksScored' => $updateAttendanceMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updateAttendanceSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");

		$updatePreCompreSelect = $REQUEST_DATA['updatePreCompreSelect'];
		if (!in_array($updatePreCompreSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_PRECOMPRE_MARKS;
			die;
		}
		$updatePreCompreMarksScored = $REQUEST_DATA['updatePreCompreMarksScored'];
		$updatePreCompreMaxMarks = $REQUEST_DATA['updatePreCompreMaxMarks'];
		if (!is_numeric($updatePreCompreMarksScored) or $updatePreCompreMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updatePreCompreMaxMarks) or $updatePreCompreMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updatePreCompreMaxMarks < $updatePreCompreMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}
		$finalMarksArray[] = array('conductingAuthority' => PRECOMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updatePreCompreMaxMarks, 'marksScored' => $updatePreCompreMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");


		$updateCompreSelect = $REQUEST_DATA['updateCompreSelect'];
		if (!in_array($updateCompreSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_COMPRE_MARKS;
			die;
		}
		$updateCompreMarksScored = $REQUEST_DATA['updateCompreMarksScored'];
		$updateCompreMaxMarks = $REQUEST_DATA['updateCompreMaxMarks'];
		if (!is_numeric($updateCompreMarksScored) or $updateCompreMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updateCompreMaxMarks) or $updateCompreMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updateCompreMaxMarks < $updateCompreMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}
		$finalMarksArray[] = array('conductingAuthority' => COMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updateCompreMaxMarks, 'marksScored' => $updateCompreMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");
	}
	elseif ($reappearExam == 2) { # DIFFERENT MARKS FOR ATTENDANCE / PRE-COMPRE + COMPRE
		$updateAttendanceSelect = $REQUEST_DATA['updateAttendanceSelect'];
		if ($updateAttendanceSelect != 'Marks') {
			echo INVALID_OPTION_SELECTED_FOR_ATTENDANCE;
			die;
		}
		$updateAttendanceMarksScored = $REQUEST_DATA['updateAttendanceMarksScored'];
		$updateAttendanceMaxMarks = $REQUEST_DATA['updateAttendanceMaxMarks'];
		if (!is_numeric($updateAttendanceMarksScored) or $updateAttendanceMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updateAttendanceMaxMarks) or $updateAttendanceMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updateAttendanceMaxMarks < $updateAttendanceMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}

		$finalMarksArray[] = array('conductingAuthority' => ATTENDANCE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updateAttendanceMaxMarks, 'marksScored' => $updateAttendanceMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updateAttendanceSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");

		$updatePreCompreCompreSelect = $REQUEST_DATA['updatePreCompreCompreSelect'];
		if (!in_array($updatePreCompreCompreSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_PRECOMPRE_COMPRE_MARKS;
			die;
		}
		$updatePreCompreCompreMarksScored = $REQUEST_DATA['updatePreCompreCompreMarksScored'];
		$updatePreCompreCompreMaxMarks = $REQUEST_DATA['updatePreCompreCompreMaxMarks'];
		if (!is_numeric($updatePreCompreCompreMarksScored) or $updatePreCompreCompreMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updatePreCompreCompreMaxMarks) or $updatePreCompreCompreMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updatePreCompreCompreMaxMarks < $updatePreCompreCompreMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}
		$finalMarksArray[] = array('conductingAuthority' => PRECOMPRE_COMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updatePreCompreCompreMaxMarks, 'marksScored' => $updatePreCompreCompreMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreCompreSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");
	}
	elseif ($reappearExam == 3) { # DIFFERENT MARKS FOR COMPRE / PRE-COMPRE + ATTENDANCE
		$updateCompreSelect = $REQUEST_DATA['updateCompreSelect'];
		if (!in_array($updateCompreSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_COMPRE_MARKS;
			die;
		}
		$updateCompreMarksScored = $REQUEST_DATA['updateCompreMarksScored'];
		$updateCompreMaxMarks = $REQUEST_DATA['updateCompreMaxMarks'];
		if (!is_numeric($updateCompreMarksScored) or $updateCompreMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updateCompreMaxMarks) or $updateCompreMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updateCompreMaxMarks < $updateCompreMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}

		$finalMarksArray[] = array('conductingAuthority' => COMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updateCompreMaxMarks, 'marksScored' => $updateCompreMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updateCompreSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");

		$updatePreCompreAttendanceSelect = $REQUEST_DATA['updatePreCompreAttendanceSelect'];
		if (!in_array($updatePreCompreAttendanceSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_PRECOMPRE_ATTENDANCE_MARKS;
			die;
		}
		$updatePreCompreAttendanceMarksScored = $REQUEST_DATA['updatePreCompreAttendanceMarksScored'];
		$updatePreCompreAttendanceMaxMarks = $REQUEST_DATA['updatePreCompreAttendanceMaxMarks'];
		if (!is_numeric($updatePreCompreAttendanceMarksScored) or $updatePreCompreAttendanceMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updatePreCompreAttendanceMaxMarks) or $updatePreCompreAttendanceMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updatePreCompreAttendanceMaxMarks < $updatePreCompreAttendanceMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}
		$finalMarksArray[] = array('conductingAuthority' => PRECOMPRE_ATTENDANCE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updatePreCompreAttendanceMaxMarks, 'marksScored' => $updatePreCompreAttendanceMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreAttendanceSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");
        
	}
	elseif ($reappearExam == 4) { # DIFFERENT MARKS FOR PRECOMPRE / COMPRE + ATTENDANCE
		$updatePreCompreSelect = $REQUEST_DATA['updatePreCompreSelect'];
		if (!in_array($updatePreCompreSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_PRECOMPRE_MARKS;
			die;
		}
		$updatePreCompreMarksScored = $REQUEST_DATA['updatePreCompreMarksScored'];
		$updatePreCompreMaxMarks = $REQUEST_DATA['updatePreCompreMaxMarks'];
		if (!is_numeric($updatePreCompreMarksScored) or $updatePreCompreMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updatePreCompreMaxMarks) or $updatePreCompreMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updatePreCompreMaxMarks < $updatePreCompreMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}

		$finalMarksArray[] = array('conductingAuthority' => PRECOMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updatePreCompreMaxMarks, 'marksScored' => $updatePreCompreMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");

		$updateCompreAttendanceSelect = $REQUEST_DATA['updateCompreAttendanceSelect'];
		if (!in_array($updateCompreAttendanceSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_COMPRE_ATTENDANCE_MARKS;
			die;
		}
		$updateCompreAttendanceMarksScored = $REQUEST_DATA['updateCompreAttendanceMarksScored'];
		$updateCompreAttendanceMaxMarks = $REQUEST_DATA['updateCompreAttendanceMaxMarks'];
		if (!is_numeric($updateCompreAttendanceMarksScored) or $updateCompreAttendanceMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updateCompreAttendanceMaxMarks) or $updateCompreAttendanceMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updateCompreAttendanceMaxMarks < $updateCompreAttendanceMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}
		$finalMarksArray[] = array('conductingAuthority' => COMPRE_ATTENDANCE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updateCompreAttendanceMaxMarks, 'marksScored' => $updateCompreAttendanceMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updateCompreAttendanceSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");
	}
	elseif ($reappearExam == 5) { # COMBINED MARKS FOR PRECOMPRE + COMPRE + ATTENDANCE
		$updatePreCompreCompreAttendanceSelect = $REQUEST_DATA['updatePreCompreCompreAttendanceSelect'];
		if (!in_array($updatePreCompreCompreAttendanceSelect, $marksSelectArray)) {
			echo INVALID_OPTION_SELECTED_FOR_PRECOMPRE_COMPRE_ATTENDANCE_MARKS;
			die;
		}
		$updatePreCompreCompreAttendanceMarksScored = $REQUEST_DATA['updatePreCompreCompreAttendanceMarksScored'];
		$updatePreCompreCompreAttendanceMaxMarks = $REQUEST_DATA['updatePreCompreCompreAttendanceMaxMarks'];
		if (!is_numeric($updatePreCompreCompreAttendanceMarksScored) or $updatePreCompreCompreAttendanceMarksScored < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if (!is_numeric($updatePreCompreCompreAttendanceMaxMarks) or $updatePreCompreCompreAttendanceMaxMarks < 0) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		if ($updatePreCompreCompreAttendanceMaxMarks < $updatePreCompreCompreAttendanceMarksScored) {
			echo MAX_MARKS_CAN_NOT_BE_LESS_THAN_MARKS_SCORED;
			die;
		}

		$finalMarksArray[] = array('conductingAuthority' => PRECOMPRE_ATTENDANCE_COMPRE_REAPPEAR, 'studentId' => $studentId, 'classId' => $classId, 'subjectId' => $subjectId, 'maxMarks' => $updatePreCompreCompreAttendanceMaxMarks, 'marksScored' => $updatePreCompreCompreAttendanceMarksScored, 'holdResult' => 0, 'gradeId' => $gradeId, 'gradeSetId' => $gradeSetId, 'gradingLabelId' => $gradingLabelId, 'marksScoredStatus' => "'$updatePreCompreCompreAttendanceSelect'", 'isActive' => 1, 'userId' => $userId, 'updateDateTime' => "NOW()", 'reason' => "'$reappearReason'");
	}


	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
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
        foreach($finalMarksArray as $record) {
			$str = "";
			foreach($record as $key => $value) {
				if ($str != "") {
					$str .= ",";
				}
				$str .= "$key = $value";
			}
			$returnStatus = $studentManager->insertRecordInTransaction($str);
			if ($returnStatus == false) {
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