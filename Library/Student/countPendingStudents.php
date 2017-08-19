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

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$conditions = '';
	$showPendingStudents = false;
	if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
		$thisGroupAllocation = 0;
		$siblingGroupAllocation = 0;
		$conditions = " AND	studentId NOT IN (SELECT studentId FROM student_groups WHERE classId = $classId)";
		$showPendingStudents = true;
	}
	else {
		$thisGroupAllocationArray = $studentManager->getThisGroupAllocation($classId, $groupId);
		$thisGroupAllocation = $thisGroupAllocationArray[0]['cnt'];

		$siblingGroupAllocationArray = $studentManager->getSiblingGroupAllocation($classId, $groupId,$groupType);
		$siblingGroupAllocation = $siblingGroupAllocationArray[0]['cnt'];
	}

	if ($groupType == 3) { //Theory
		$degreeStudentsArray = $studentManager->countDegreeStudents($classId, $conditions);
		$degreeStudents = $degreeStudentsArray[0]['cnt'];
		$totalPendingAllocation = $degreeStudents - $thisGroupAllocation - $siblingGroupAllocation;
	}
	else if ($groupType == 1) { //Tutorial
		$totalPendingAllocationArray = $studentManager->countPendingGroupAllocation($classId, $groupType, $groupId);
		$totalPendingAllocation = $totalPendingAllocationArray[0]['cnt'];
		if (is_null($totalPendingAllocation)) {
			$totalPendingAllocation = 0;
		}
	}
	else {	//Practical
		$parentIdArray = $studentManager->getParentId($groupId);
		$parentId = $parentIdArray[0]['parentGroupId'];
		if ($parentId == 0) { //group is at parent level
			$degreeStudentsArray = $studentManager->countDegreeStudents($classId, $conditions);
			$degreeStudents = $degreeStudentsArray[0]['cnt'];
			$totalPendingAllocation = $degreeStudents - $thisGroupAllocation - $siblingGroupAllocation;
		}
		else {
			$parentAllocationArray = $studentManager->countGroupAllocation($parentId, $classId);
			$parentStudents = $parentAllocationArray[0]['cnt'];
			$totalPendingAllocation = $parentStudents - $thisGroupAllocation - $siblingGroupAllocation;
		}
	}
	$resultArray = array(
							'thisGroupAllocation' => $thisGroupAllocation, 
							'siblingGroupAllocation' => $siblingGroupAllocation, 
							'pendingGroupAllocation' => $totalPendingAllocation
						);


	echo json_encode($resultArray);

   
// for VSS
// $History: countPendingStudents.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Library/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
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
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:19p
//Created in $/Leap/Source/Library/Student
//file added for assign group
//

?>
