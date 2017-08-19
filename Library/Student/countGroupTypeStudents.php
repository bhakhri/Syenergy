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
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	//This will work fine for theory and tutorial, as tut will be at bottom level and theory will be a level above tut.
	if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
		if ($groupType == 3) {
			$totalAssignedStudents = 0;//no student will be here. because we have reached here when showing pending students only
		}
		elseif ($groupType == 1) {
			$totalAssignedStudents = 0;//no student will be here. because we have reached here when showing pending students only
		}
	}
	elseif ($groupType == 1 or $groupType == 3) { //Tutorial and Theory
		$totalAssignedStudentsArray = $studentManager->countGroupAssignedStudents($classId,$groupType);
		$totalAssignedStudents = $totalAssignedStudentsArray[0]['cnt'];
	}
	else { //for all others
		//fetch assigned students at top most level
		$totalAssignedStudentsArray = $studentManager->countGroupAssignedStudentsRoot($classId,$groupType);
		$totalAssignedStudents = $totalAssignedStudentsArray[0]['cnt'];
	}
	echo $totalAssignedStudents;

   
// for VSS
// $History: countGroupTypeStudents.php $
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
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Library/Student
//updated code for student group assignment.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:36p
//Created in $/LeapCC/Library/Student
//file added for student group allocation
//


?>
