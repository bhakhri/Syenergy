<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (14.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignGroupsToStudents');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	$classId = $REQUEST_DATA['degree'];
	$groupType = $REQUEST_DATA['groupType'];
	$groupId = $REQUEST_DATA['groupId'];
	$assignNo = $REQUEST_DATA['assignNo'];
	if (!isset($REQUEST_DATA['chk']) or !is_array($REQUEST_DATA['chk'])) {
		echo NO_STUDENT_SELECTED_FOR_GROUP_ASSIGNMENT;
		die;
	}
	//$selectedStudents = 

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	
	$studentArray = $studentManager->countUnassignedRollNumbers($classId);
	$studentCount = intval($studentArray[0]['count']);
	if ($studentCount > 0) {
		echo ALL_STUDENTS_NOT_ASSIGNED_ROLL_NO;
		die;
	}
	else {
		if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
		}
		else {
			$studentArray = $studentManager->countAttendance($classId);
			$studentCount = intval($studentArray[0]['count']);
			if ($studentCount > 0) {
				echo ATTENDANCE_ENTERED_FOR_THIS_CLASS;
				die;
			}
			else {
				$studentArray = $studentManager->countTests($classId);
				$studentCount = intval($studentArray[0]['count']);
				if ($studentCount > 0) {
					echo TESTS_TAKEN_FOR_THIS_CLASS;
					die;
				}
			}
		}
	}


	$groupAssignment = $REQUEST_DATA['groupAssignment'];

	$ordering = $groupAssignment == "rollNo" ? "numericRollNo" : "firstName, lastName";


	$prefixArray = $studentManager->getClassPrefixSuffix($classId);
	$prefix = $prefixArray[0]['rollNoPrefix'];
	$suffix = $prefixArray[0]['rollNoSuffix'];

	/*
	$pendingStudentsArray = $studentManager->getPendingStudents($classId, $groupType);
	$pendingStudents = $pendingStudentsArray[0]['cnt'];

	if ($pendingStudents == 0) { //remove previous assignment
		$studentManager->removeGroupAssignment($classId, $groupType);
	}
	*/

	if ($groupType == 1) { //FOR TUT
		$rollNoArray = $studentManager->getPendingGroupAllocation($classId, $groupType, $groupId, "ORDER BY $ordering LIMIT 0, $assignNo");
	}
	elseif ($groupType == 3) { //FOR THEORY
		$groupTypeAllocatedStudentsArray = $studentManager->getGroupTypeAllocatedStudents($classId, $groupType);
		$studentList = 0;
		if (count($groupTypeAllocatedStudentsArray) and $groupTypeAllocatedStudentsArray[0]['studentId'] != 0) {
			$studentList = UtilityManager::makeCSList($groupTypeAllocatedStudentsArray, 'studentId');
		}
		$rollNoArray = $studentManager->getStudentClassRollNo($classId, $prefix, $suffix,  " AND a.studentId not in ($studentList)", "LIMIT 0, $assignNo", " ORDER BY $ordering ASC ");
	}
	else {//OTHERS
		
		$parentIdArray = $studentManager->getParentId($groupId);
		$parentId = $parentIdArray[0]['parentGroupId'];
		if ($parentId == 0) { //group is at parent level
			$siblingArray = $studentManager->getGroupSiblings($classId, $groupId, $groupType);
			$siblingList = UtilityManager::makeCSList($siblingArray, 'groupId');
			$studentList = 0;
			$siblingAllocatedArray = $studentManager->getSiblingGroupStudents($classId, $siblingList);
			if (count($siblingAllocatedArray) and $siblingAllocatedArray[0]['studentId'] != 0) {
				$studentList = UtilityManager::makeCSList($siblingAllocatedArray, 'studentId');
			}
			$rollNoArray = $studentManager->getStudentClassRollNo($classId, $prefix, $suffix,  " AND a.studentId not in ($studentList)", "LIMIT 0, $assignNo", " ORDER BY $ordering ASC ");
		}
		else {
			$rollNoArray = $studentManager->getPendingGroupAllocation($classId, $groupType, $groupId, "ORDER BY $ordering LIMIT 0, $assignNo");
		}
	}
	$i = 0;
	$str = "";

	$sessionId = $sessionHandler->getSessionVariable('SessionId');
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	foreach($rollNoArray as $rollNoRecord) {
		if ($i == $assignNo) {
			break;
		}
		$studentId = $rollNoRecord['studentId'];
		if (in_array($studentId, $REQUEST_DATA['chk'])) {
			if (!empty($str)) {
				$str .= ",";
			}
			$str .= "($studentId, $classId, $groupId, $instituteId, $sessionId)";
		}
		$i++;
	}
	
	$return = $studentManager->assignGroup($str);
	if ($return) {
		echo GROUP_ASSIGNED_SUCCESSFULLY;
	}
	else {
		echo FAILURE;
	}


   
// for VSS
// $History: initListAssignGroup.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Library/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/20/09    Time: 4:27p
//Updated in $/LeapCC/Library/Student
//fixed issue of student allocation not getting properly.
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:01p
//Updated in $/LeapCC/Library/Student
//added code for selected students.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/22/08   Time: 1:20p
//Updated in $/LeapCC/Library/Student
//added check for attendance/tests already taken
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Library/Student
//updated code for student group assignment.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:33p
//Updated in $/LeapCC/Library/Student
//working on student group allocation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/26/08    Time: 1:14p
//Updated in $/Leap/Source/Library/Student
//done the common messaging
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/18/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Student
//changed buttons, improved page design and fixed bug found during
//self-testing.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:40p
//Updated in $/Leap/Source/Library/Student
//file changed and applied ordering in it
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 11:35a
//Created in $/Leap/Source/Library/Student
//file created for assigning groups to students
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:39p
//Updated in $/Leap/Source/Library/Student
//changed 'prefix' to 'rollNoPrefix'
//

?>
