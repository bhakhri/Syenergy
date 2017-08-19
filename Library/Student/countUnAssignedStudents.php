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

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$conditions = '';
	if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
		$conditions = " AND	studentId NOT IN (SELECT studentId FROM student_groups WHERE classId = $classId)";
	}

	$studentArray = $studentManager->countUnassignedRollNumbers($classId, $conditions);
	$studentCount = intval($studentArray[0]['count']);
	if ($studentCount > 0) {
		echo $studentCount;
	}
	else {
		if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
			echo 0;//because if the students have not been assigned groups, then attendance can not be taken.
		}
		else {
			$studentArray = $studentManager->countAttendance($classId, $conditions);
			$studentCount = intval($studentArray[0]['count']);
			if ($studentCount > 0) {
				echo -2;
			}
			else {
				$studentArray = $studentManager->countTests($classId);
				$studentCount = intval($studentArray[0]['count']);
				if ($studentCount > 0) {
					echo -3;
				}
				else {
					echo 0;
				}
			}
		}
	}

// for VSS
// $History: countUnAssignedStudents.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Library/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/22/08   Time: 1:19p
//Updated in $/LeapCC/Library/Student
//done the coding for attendance/tests already taken
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/08/08    Time: 3:14p
//Updated in $/Leap/Source/Library/Student
//fixed issue found during self testing.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:39p
//Created in $/Leap/Source/Library/Student
//file added for counting students in selected class to which roll
//numbers have not been assigned
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:19p
//Created in $/Leap/Source/Library/Student
//file added for assign group
//

?>
