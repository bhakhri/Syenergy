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
	define('MODULE','AssignGroupsToStudents');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	$classId = $REQUEST_DATA['degree'];
	$groupType = $REQUEST_DATA['groupType'];
	$groupId = $REQUEST_DATA['groupId'];
	$assignNo = $REQUEST_DATA['assignNo'];
	$pendingStudents = $REQUEST_DATA['pendingStudents'];
	$groupAssignment = $REQUEST_DATA['groupAssignment'];

	$ordering = $groupAssignment == "rollNo" ? "numericRollNo" : "firstName, lastName";


    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$prefixArray = $studentManager->getClassPrefixSuffix($classId);
	$prefix = $prefixArray[0]['rollNoPrefix'];
	$suffix = $prefixArray[0]['rollNoSuffix'];

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	if ($pendingStudents == 0) {
		$childGroupArray = $studentManager->getGroupChildren($classId, " where parentGroupId = $groupId ");
		$allGroups[] = $groupId;
		if (count($childGroupArray)) {
			foreach($childGroupArray as $key => $value) {
				$allGroups[] = intval($value);
			}
		}
		$str = '';
		foreach($allGroups as $key => $value) {
			if (!empty($str)) {
				$str .= ',';
			}
			$str .= $value;
		}
		if ($str) {
			$studentCountArray = $studentManager->countGroupAttendance($classId,$str);
			$studentCount = $studentCountArray[0]['cnt'];
			if ($studentCount > 0) {
				echo ATTENDANCE_ENTERED_FOR_GROUP;
				exit;
			}
			$studentCountArray = $studentManager->countGroupTests($classId,$str);
			$studentCount = $studentCountArray[0]['cnt'];
			if ($studentCount > 0) {
				echo TEST_ENTERED_FOR_GROUP;
				exit;
			}
			$studentManager->removeGroupStudents($classId, $str);
		}
	}


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

	$resultArray = array();
	$i = 1;
	foreach($rollNoArray as $rollNoRecord) {
		$resultArray[] = array('srNo'=>$i, 'select'=>'<input type="checkbox" name="chk[]" value="'.$rollNoRecord['studentId'].'">', 'regNo'=>$rollNoRecord['regNo'], 'firstName' => $rollNoRecord['firstName'], 'lastName' => $rollNoRecord['lastName'], 'rollNo' => $rollNoRecord['rollNo']);
		$i++;
	}

	echo json_encode($resultArray);

   
// for VSS
// $History: showGroupAssignment.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Library/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/25/09    Time: 12:21p
//Updated in $/LeapCC/Library/Student
//records were not coming in order. this was fixed.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:02p
//Updated in $/LeapCC/Library/Student
//added checkbox in json
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/31/09    Time: 5:06p
//Updated in $/LeapCC/Library/Student
//made the coding, if tutorial groups are not mandatory
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Library/Student
//updated code for student group assignment.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:34p
//Updated in $/LeapCC/Library/Student
//working on student group allocation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:37p
//Updated in $/Leap/Source/Library/Student
//changed file and applied checks for if no student exists in class
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 11:34a
//Created in $/Leap/Source/Library/Student
//file created for showing group assignment to students
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:39p
//Updated in $/Leap/Source/Library/Student
//changed 'prefix' to 'rollNoPrefix'
//

?>
