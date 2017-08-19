<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$reportManager = StudentReportsManager::getInstance();

	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
//$classId = $REQUEST_DATA['degree'];
	
	if (isset($REQUEST_DATA['labelId'])) {
		$timeTable = $REQUEST_DATA['labelId'];
	}
	elseif (isset($REQUEST_DATA['timeTable'])) {
		$timeTable = $REQUEST_DATA['timeTable'];
	}


	//fetching subject data only if any one class is selected
	/*if ($classId == 'all' || $classId == '') {
		$timeTableArray = $reportManager->getTimeTableClass("AND ttl.timeTableLabelId =".$timeTable);
	}
	
	$classIdList = UtilityManager::makeCSList($timeTableArray, 'classId');*/
	
	if ($classId != 'all') {
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if ($roleId != 2) {
			$subjectsArray = $reportManager->getSubjectList($classId);
			/*
			foreach($subjectsArray as $subjectRecord) {
				$hasParentCategory = $subjectRecord['hasParentCategory'];
				if ($hasParentCategory == '1' or $hasParentCategory == 1) {
						$subjectsArray = $reportManager->getClassSubjectsWithOtherSubjects($classId);
				}
			}
			*/
			echo json_encode($subjectsArray);
		}
		/*else {
			$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
			$subjectsArray = $reportManager->getTeacherSubjectList($classId, $timeTable, $employeeId);
			echo json_encode($subjectsArray);
		}*/
	}
	/*
	else {

		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if ($roleId != 2) {
			$subjectsArray = $reportManager->getSubjectList($classIdList);
			/*
			foreach($subjectsArray as $subjectRecord) {
				$hasParentCategory = $subjectRecord['hasParentCategory'];
				if ($hasParentCategory == '1' or $hasParentCategory == 1) {
						$subjectsArray = $reportManager->getClassSubjectsWithOtherSubjects($classId);
				}
			}
			echo json_encode($subjectsArray);
		}
	
	}
	*/


// $History: initClassAttendanceSubjects.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/20/09   Time: 12:47p
//Updated in $/LeapCC/Library/StudentReports
//fixed bug no.0002026
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/09   Time: 12:29p
//Created in $/LeapCC/Library/StudentReports
//new ajax file for class not attendance report
//
?>