<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (11-June-2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignOptionalSubjects');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$categorySubjectId = $REQUEST_DATA['categorySubjects'];

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$subjectTypeArray = $studentManager->getSubjectCode($subjectId);
	$subjectTypeId = $subjectTypeArray[0]['subjectTypeId'];

	$sort = trim($REQUEST_DATA['sort']);
	$sortBy = '';
	if (empty($sort)) {
		echo NO_OPTION_SELECTED;
		die;
	}
	else {
		if ($sort == 1) {
			$sortBy = 'rollNo';
		}
		else if ($sort == 2) {
			$sortBy = 'universityRollNo';
		}
		else if ($sort == 3) {
			$sortBy = 'studentName';
		}
		else {
			echo INVALID_OPTION_SELECTED;
			die;
		}
	}


	$condition = '';
	if ($subjectTypeId == 1) {//Theory
		$condition = " AND a.groupTypeId IN (1,3)"; // Show Theory,Tut groups in case of Theory
	}
	elseif ($subjectTypeId == 2) {//Practical
		$condition = " AND a.groupTypeId = 2"; // Practical
	}
	$thisSubjectId = $subjectId;
	if ($categorySubjectId != '' and !empty($categorySubjectId)) {
		$thisSubjectId = $categorySubjectId;
	}
	$groupsArray = CommonQueryManager::getInstance()->getLastLevelGroups(' a.groupName'," AND a.classId = $classId AND a.isOptional = 1 and a.optionalSubjectId = $thisSubjectId $condition");

	$hasParentCategoryArray = $studentManager->hasParentCategory($classId, $subjectId);
	$hasParentCategory = $hasParentCategoryArray[0]['hasParentCategory'];
	if ($hasParentCategory == 1) {
		$optionalSubjectArray = $studentManager->countClassOptionalSubjects($classId);
		$optionalSubjectCount = $optionalSubjectArray[0]['cnt'];
		$classStudentsArray = $studentManager->getOptionalPendingStudents($classId, $subjectId, $categorySubjectId, $optionalSubjectCount, $sortBy);
		echo json_encode(array('students' => $classStudentsArray, 'groups' => $groupsArray));
	}
	else {
		$classStudentsArray = $studentManager->getStudents($classId, $sortBy, " AND studentId NOT IN (SELECT studentId FROM student_optional_subject WHERE subjectId = $subjectId AND classId = $classId) ");
		echo json_encode(array('students' => $classStudentsArray, 'groups' => $groupsArray));
	}


// for VSS
// $History: ajaxGetOptionalSubjectGroups.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:29p
//Updated in $/LeapCC/Library/Student
//done changes as per FCNS No. 1601
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 2/11/10    Time: 4:49p
//Updated in $/LeapCC/Library/Student
//done changes as per FCNS No.1292
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/09    Time: 5:50p
//Updated in $/LeapCC/Library/Student
//fetched theory groups, earlier it fetched tut groups only. 
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:16a
//Updated in $/LeapCC/Library/Student
//changed calling of 'function'
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:08a
//Updated in $/LeapCC/Library/Student
//added code for category subjects.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:47p
//Updated in $/LeapCC/Library/Student
//added access defines.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/11/09    Time: 10:52a
//Created in $/LeapCC/Library/Student
//file added for assigning optional subject to students
//



?>