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
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree'];
	$conditions = '';
	if (isset($REQUEST_DATA['pendingStudents']) and $REQUEST_DATA['pendingStudents'] == 'on') {
		$conditions = " AND	studentId NOT IN (SELECT studentId FROM student_groups WHERE classId = $classId)";
	}
	$degreeStudentsArray = $studentManager->countDegreeStudents($classId, $conditions);
	$degreeStudents = $degreeStudentsArray[0]['cnt'];
	echo $degreeStudents;

   
// for VSS
// $History: countDegreeStudents.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Library/Student
//added code for assigning groups to pending students only.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:35p
//Created in $/LeapCC/Library/Student
//file added for student group allocation
//

//

?>
