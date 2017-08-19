<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','PromoteStudents');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	global $sessionHandler;
	$queryDescription =''; 
	$classId = $REQUEST_DATA['class1'];
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
	/*
	$gradesGivenArray = $studentManager->checkGrades($degreeId);
	if ($gradesGivenArray[0]['cnt'] > 0) {
		echo ASSIGN_GRADES_FIRST;
	}
	else {
	*/
	$array = array('yes'=>1,'no'=>0);
	$attendance = trim($REQUEST_DATA['attendance']);
	$attendance = add_slashes($attendance);

	$test = trim($REQUEST_DATA['test']);
	$test = add_slashes($test);

	$marks = trim($REQUEST_DATA['marks']);
	$marks = add_slashes($marks);

	if (!array_key_exists($attendance,$array)) {
		echo NO_OPTION_SELECTED_FOR_ATTENDANCE;
		die;
	}
	if (!array_key_exists($test,$array)) {
		echo NO_OPTION_SELECTED_FOR_TESTS;
		die;
	}
	if (!array_key_exists($marks,$array)) {
		echo NO_OPTION_SELECTED_FOR_MARKS_TRANSFERRED;
		die;
	}
	if (empty($classId)) {
		echo CLASS_NOT_SELECTED;
		die;
	}
	$attendance1 = $array[$attendance];
	$test1 = $array[$test];
	$marks1 = $array[$marks];

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		require_once(MODEL_PATH . "/ClassesManager.inc.php");
		$classManager = ClassesManager::getInstance();
		$classIdArray = $studentManager->getNextClassId($classId);
		$nextClassId = 0;
		$isActive = 0;
		if (isset($classIdArray[0]['classId']) and !empty($classIdArray[0]['classId'])) {
			$nextClassId = $classIdArray[0]['classId'];
			$isActive = $classIdArray[0]['isActive'];
		}
		if (empty($nextClassId)) {
			# TO CREATE STUDY PERIODS WITH 99999 PERIOD VALUE
			$return = $classManager->checkCopyPeriodicity();
			if ($return == false) {
				echo FAILURE;
				die;
			}
			$nextClassIdArray = $classManager->getAlumniClassId($classId);
			$nextClassId = $nextClassIdArray[0]['classId'];
			if (empty($nextClassId)) {
				$alumniClassArray = $classManager->getLastClassDetails($classId);
				if (count($alumniClassArray) != 1) {
					echo MORE_THAN_ONE_ALUMNI_CLASS_FOUND;
					die;
				}
				$return = $classManager->createAlumniClassInTransaction($alumniClassArray[0]);
				if ($return == false) {
					echo ERROR_WHILE_CREATING_ALUMNI_CLASS;
					die;
				}
			}
			$nextClassIdArray = $classManager->getAlumniClassId($classId);
			$nextClassId = $nextClassIdArray[0]['classId'];
		}

		if (empty($nextClassId)) {
			echo ERROR_WHILE_CREATING_ALUMNI_CLASS;
			die;
		}

		$return = $studentManager->promoteClassStudentsInTransaction($nextClassId, $classId);
		if ($return == false) {
			echo FAILURE_WHILE_PROMOTING_STUDENTS;
			die;
		}
		if ($nextClassId != 0 and $isActive == 2) {
			$return = $studentManager->makeClassActiveInTransaction($nextClassId);
			if ($return == false) {
				echo FAILURE_WHILE_MAKING_NEW_CLASS_ACTIVE;
				die;
			}
		}
		$return = $studentManager->makeClassPastInTransaction($classId);
		if ($return == false) {
			echo FAILURE_WHILE_MAKING_OLD_CLASS_PAST;
			die;
		}
		$return = $studentManager->storePromotionRecord($classId, $attendance1, $test1, $marks1);
		if ($return == false) {
			echo FAILURE_WHILE_MAKING_OLD_CLASS_PAST;
			die;
		}

		$classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId = $classId");
		$className = $classNameArray[0]['className'];

		/*
		$classIdArray = $studentManager->getNextClassId($degreeId);
		$nextClassId = 0;
		$isActive = 0;
		if (isset($classIdArray[0]['classId']) and !empty($classIdArray[0]['classId'])) {
			$nextClassId = $classIdArray[0]['classId'];
			$isActive = $classIdArray[0]['isActive'];
		}
		$classStudentsArray = $studentManager->getClassStudents($degreeId);

		$classStudentList = UtilityManager::makeCSList($classStudentsArray, 'studentId');

		$return = $studentManager->promoteStudentsInTransaction($nextClassId, $classStudentList);
		if ($return == false) {
			echo FAILURE_WHILE_PROMOTING_STUDENTS;
			die;
		}
		if ($nextClassId != 0 and $isActive == 2) {
			$return = $studentManager->makeClassActiveInTransaction($nextClassId);
			if ($return == false) {
				echo FAILURE_WHILE_MAKING_NEW_CLASS_ACTIVE;
				die;
			}

		}
		$return = $studentManager->makeClassPastInTransaction($degreeId);
		if ($return == false) {
			echo FAILURE_WHILE_MAKING_OLD_CLASS_PAST;
			die;
		}
		*/
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ##########################################
		$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
		$auditTrialDescription = "students have been promoted for class: $className ";
		$type = PROMOTE_STUDENT; //STUDENTS PROMOTED
		//$auditTrialDescription .= $subjectList;
		require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
		$commonQueryManager = CommonQueryManager::getInstance();
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ##########################################
			echo STUDENTS_PROMOTED;
		}
		else {
			echo FAILURE;
		}

	}
	else {
		echo FAILURE;
	}

		/*
		$pendingStudentArray = $studentManager->countPendingStudents($degreeId);
		$pendingStudents = $pendingStudentArray[0]['cnt'];
		if ($pendingStudents == 0) {
			$studentManager->makeClassPast($degreeId);
			echo STUDENTS_PROMOTED;
		}
		else {
			$pendingStudentsArray = $studentManager->getPendingClassStudents($degreeId);
			for($i=0;$i<$pendingStudents;$i++) {
				// add stateId in actionId to populate edit/delete icons in User Interface
				$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$pendingStudentsArray[$i]);
			}

			echo json_encode($valueArray);
		}
		*/
//	}


// $History: initPromoteStudents.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/25/09    Time: 12:55p
//Updated in $/LeapCC/Library/Student
//added transaction.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/29/09    Time: 11:35a
//Created in $/LeapCC/Library/Student
//file added for student promotion.
//





?>
