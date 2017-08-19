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
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	if ($classId == '' or $subjectId == '') {
		echo PLEASE_SELECT_CLASS_AND_SUBJECT;
		die;
	}

	$subjectTypeArray = $studentManager->getSubjectCode($subjectId);
	$subjectTypeId = $subjectTypeArray[0]['subjectTypeId'];

	$condition = '';
	if ($subjectTypeId == 1) {//Theory
		$condition = " AND a.groupTypeId IN (1,3)"; // Show only Tut groups in case of Theory
	}
	elseif ($subjectTypeId == 2) {//Practical
		$condition = " AND a.groupTypeId = 2"; // Practical
	}
	$groupsArray = CommonQueryManager::getInstance()->getLastLevelGroups(' a.groupName'," AND a.classId = $classId AND a.isOptional = 1 $condition");

	$parentGroupArray = array();
	foreach($groupsArray as $groupRecord) {
		$groupId = $groupRecord['groupId'];
		$parentGroupIdArray = $studentManager->getParentId($groupId);//fetch parent theory group
		$parentGroupArray[$groupId] = $parentGroupIdArray[0]['parentGroupId'];
	}


	$hasParentCategoryArray = $studentManager->hasParentCategory($classId, $subjectId);
	$hasParentCategory = $hasParentCategoryArray[0]['hasParentCategory'];
	$mappedForSubjectId = "NULL";
	if ($hasParentCategory == 1) {
		$categorySubjectId = $REQUEST_DATA['categorySubjects'];
		$optionalSubjectArray = $studentManager->countClassOptionalSubjects($classId);
		$optionalSubjectCount = $optionalSubjectArray[0]['cnt'];
		$classStudentsArray = $studentManager->getOptionalPendingStudents($classId, $subjectId, $categorySubjectId, $optionalSubjectCount, 'universityRollNo');
		$subjectId = $categorySubjectId;//so that when the data is saved as per category subject, if exists.
		$mappedForSubjectId = $REQUEST_DATA['subjectId'];
	}
	else {
		$classStudentsArray = $studentManager->getStudents($classId, 'rollNo', " AND studentId NOT IN (SELECT studentId FROM student_optional_subject WHERE subjectId = $subjectId AND classId = $classId) ");
	}
	$insertStr = '';
	foreach($classStudentsArray as $studentRecord) {
		$studentId = $studentRecord['studentId'];

		if (isset($REQUEST_DATA['s_'.$studentId])) {
			foreach($groupsArray as $groupRecord) {
				$groupId = $groupRecord['groupId'];
				if ($REQUEST_DATA['s_'.$studentId] == $groupId) {
					if (!empty($insertStr)) {
						$insertStr .= ', ';
					}
					$insertStr .= "($subjectId, $studentId, $classId, $groupId, $mappedForSubjectId)";
					$parentGroupId = $parentGroupArray[$groupId];
					if ($parentGroupId) {
						$insertStr .= ",($subjectId, $studentId, $classId, $parentGroupId, $mappedForSubjectId)";
					}
				}
			}
		}
	}

	if (empty($insertStr)) {
		echo NO_DATA_SUBMIT;
		die;
	}
	$return = $studentManager->assignOptionalGroup($insertStr);
	if ($return) {
		echo GROUP_ASSIGNED_SUCCESSFULLY;
	}
	else {
		echo FAILURE;
	}



// for VSS
// $History: initListAssignOptionalGroup.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 3/04/10    Time: 3:32p
//Updated in $/LeapCC/Library/Student
//added function parameter
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:02p
//Updated in $/LeapCC/Library/Student
//fetched theory groups as well, earlier we were fetching tut groups
//only.
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
//User: Ajinder      Date: 6/11/09    Time: 10:46a
//Created in $/LeapCC/Library/Student
//file added for assigning optional subject to students
//



?>
