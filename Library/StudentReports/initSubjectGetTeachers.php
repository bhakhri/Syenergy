<?php
//--------------------------------------------------------
//This file returns the array of employess based on time table for a class and a subject
//
// Author :Ajinder Singh
// Created on : 27-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$arr=explode('-',$REQUEST_DATA['mixedValue']);

	//fetch the classes for the user selected data
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriod']."'";   
	$classIdArray = $studentReportsManager->getClassId($classFilter);
	$classId = $classIdArray[0]['classId'];

	$subjectId = $REQUEST_DATA['subjectId'];
	if ($subjectId == 'all') {
		$employeesArray = $studentReportsManager->getEmployeeList($classId);
	}
	else {
		$employeesArray = $studentReportsManager->getEmployeeList($classId, " AND a.subjectId = $subjectId ");
	}

	echo json_encode($employeesArray);

//// $History: initSubjectGetTeachers.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/09/08    Time: 1:02p
//Updated in $/Leap/Source/Library/StudentReports
//improved code readibility
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:27p
//Created in $/Leap/Source/Library/StudentReports
//File added for "class performance graph"
//

?>