<?php
//--------------------------------------------------------
//This file returns the array of groups under which a teacher teaches a subject to a particular class.
//
// Author :Ajinder Singh
// Created on : 27-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$arr=explode('-',$REQUEST_DATA['mixedValue']);

	//fetch the classes for the user selected data
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriod']."'";   
	$classIdArray= $studentReportsManager->getClassId($classFilter);
	$classId = $classIdArray[0]['classId'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$employeeId = $REQUEST_DATA['teacherId'];

	if ($subjectId == 'all') {
		$subjectsArray = $studentReportsManager->getEmployeeGroupList($classId, $employeeId);
	}
	else {
		$subjectsArray = $studentReportsManager->getEmployeeGroupList($classId, $employeeId, " AND a.subjectId = $subjectId ");
	}


	echo json_encode($subjectsArray);

//// $History: initTeacherGetGroups.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:40p
//Updated in $/Leap/Source/Library/StudentReports
//improved function parameter
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/28/08    Time: 3:43p
//Updated in $/Leap/Source/Library/StudentReports
//added code for "all subjects"
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:27p
//Created in $/Leap/Source/Library/StudentReports
//File added for "class performance graph"
//

?>