<?php
//This file is used for sending all data for student performance report
//
// Author :Ajinder Singh
// Created on : 29-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

	$rollNo = $REQUEST_DATA['rollNo'];

	$dataArray = array();

	// fetch student id and student class
	$studentIdClassArray = $studentReportsManager->getStudentIdClass($rollNo);
	$studentId = $studentIdClassArray[0]['studentId'];
	$classId = $studentIdClassArray[0]['classId'];

	$dataArray['studentDetails'] = $studentIdClassArray;

	//fetch subject types
	$subTypeArray = $studentReportsManager->getTestSubjectTypes($classId);

	$subjectList = '';

	$dataArray['subjectTypes'] = $subTypeArray;

	//foreach subject type
	foreach($subTypeArray as $subjectTypeRecord) {

		$subjectTypeId = $subjectTypeRecord['subjectTypeId'];
		$subjectTypeName = $subjectTypeRecord['subjectTypeName'];

		//fetch subjects for that class and that subject type
		$subjectsArray = $studentReportsManager->getSubjectTypeSubjects($subjectTypeId, $classId);

		$dataArray[$subjectTypeName.'#subjects'] = $subjectsArray;



		foreach($subjectsArray as $subjectRecord) {
			if (!empty($subjectList)) {
				$subjectList .= ',';
			}
			//make comma separated list of subjects
			$subjectList .= $subjectRecord['subjectId'];
		}

		$testTypeList = '';
		//fetch test types for class, and for subject list
		$testTypesArray = $studentReportsManager->getTestTypes($classId, $subjectList);

		$dataArray[$subjectTypeName.'#testTypes'] = $testTypesArray;

		foreach($testTypesArray as $testTypeRecord) {
			if (!empty($testTypeList)) {
				$testTypeList .= ',';
			}
			//make comma separated list of test types
			$testTypeList .= $testTypeRecord['testTypeId'];
		}

		//fetch tests for test types and subjects
		$testArray = $studentReportsManager->getTests($testTypeList, $subjectList, $classId, $studentId);

		$studentAttArray = array();

		$testTypeSubjectTests = array();

		//foreach test type
		foreach($testTypesArray as $testTypeRecord) {
			$testTypeId = $testTypeRecord['testTypeId'];
			$testTypeName = $testTypeRecord['testTypeName'];
			$evaluationCriteriaId = $testTypeRecord['evaluationCriteriaId'];
			//count tests for this test type and all subjects in this subject type
			$testTypeSubjectTestCountArray = $studentReportsManager->countTestTypeSubjectListTest($testTypeId, $subjectList, $classId);
			$testTypeSubjectTestCount = $testTypeSubjectTestCountArray[0]['cnt'];
			$dataArray[$testTypeName.'#testCount'] = $testTypeSubjectTestCount;

			//fetch tests for this test type and all subjects in this subject type
			$testTypeSubjectTestArray = $studentReportsManager->getTests($testTypeId, $subjectList, $classId, $studentId);
			$dataArray[$testTypeName.'#tests'] = $testTypeSubjectTestArray;

			foreach($subjectsArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				$subjectCode = $subjectRecord['subjectCode'];

				//if test type is of attendance
				if ($evaluationCriteriaId == 5 or $evaluationCriteriaId == 6) {
					//fetch attendance marks - parts
					$subAttArray = $studentReportsManager->getAttendanceMarks($classId, $studentId, $subjectId);
					if (empty($subAttArray[0]['lectureDelivered'])) {
						$subAttArray[0]['lectureDelivered'] = 0;
					}
					if (empty($subAttArray[0]['lectureAttended'])) {
						$subAttArray[0]['lectureAttended'] = 0;
					}
					//fetch attendance marks - total
					$subAttMainArray = $studentReportsManager->getTotalMarks($classId, $subjectId, $studentId, $testTypeId);
					$dataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName] = $subAttMainArray;
					$dataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeParts#'.$testTypeName] = $subAttArray;
				}
				else {
					//the test type is other than attendance
					//fetch total marks for that test type
					$subMarksMainArray = $studentReportsManager->getTotalMarks($classId, $subjectId, $studentId, $testTypeId);
					if (!count($subMarksMainArray)) {
						$subMarksMainArray = array(array('marksScored'=>0));
					}
					$dataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName] = $subMarksMainArray;

					foreach($testArray as $testRecord) {
						$testId = $testRecord['testId'];
						$testName = $testRecord['testName'];
						//fetch marks - parts
						$subMarksArray = $studentReportsManager->getMarks($classId, $studentId, $subjectId, $testId);
						if (!count($subMarksArray)) {
							$subMarksArray = array(array('marksScored'=>0));

						}
						$dataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeParts#'.$testTypeName.'#Test#'.$testName] = $subMarksArray;
					}
				}
			}
		}
	}
/*
echo '<pre>';
print_r($dataArray);
echo '</pre>';
*/
//die;
echo json_encode($dataArray);

////$History: initStudentPerformanceReport.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 10:27a
//Created in $/LeapCC/Library/StudentReports
//file added.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:00a
//Updated in $/Leap/Source/Library/StudentReports
//file modified and fixed bugs found during self testing
//

?>