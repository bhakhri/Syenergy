<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TestWiseMarksConsolidatedReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	$classId = $REQUEST_DATA['class1']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8

	$allDetailsArray = array();

	//fetch class students
	$conditions = '';

	//fetch distinct types of test taken on this class
	$str2 = "";
	if ($subjectId != 'all') {
		$str2 = " AND a.subjectId = $subjectId ";
	}
	$testTypeArray = $studentReportsManager->getClassTestTypes($classId, $str2);

	$allDetailsArray['testTypes'] = $testTypeArray;

	$firstString = '';
	$secondString = '';
	$thirdString = '';
	$fourthString = '';

	//for each type, fetch the different tests undertaken.
	$testArray = array();
	foreach($testTypeArray as $testTypeRecord) {
		$str = "";
		if ($subjectId != 'all') {
			$str .= " AND a.subjectId = $subjectId ";
		}


		$testDetailsArray = $studentReportsManager->getTestDetailsConsolidated($testTypeRecord['testTypeCategoryId'], $str);

		foreach($testDetailsArray as $testRecord) {
			$testName = $testRecord['testName'];
			$testTypeAbbr = $testRecord['testTypeAbbr'];
			$testIndex = $testRecord['testIndex'];
			$testTypeCategoryId = $testRecord['testTypeCategoryId'];

			$firstString .= ",(select IF(CONCAT(ttm.isPresent,ttm.isMemberOfClass)=11,concat(ttm.marksScored,'/',tm.maxMarks),IF(CONCAT(ttm.isPresent,ttm.isMemberOfClass)=01,'A','N/A')) from ".TEST_MARKS_TABLE." ttm, ".TEST_TABLE." tm, test_type_category ttc where ttm.testId = tm.testId and a.studentId = ttm.studentId and tm.testTypeCategoryId = $testTypeCategoryId and tm.classId = $classId and tm.subjectId = $subjectId and tm.testIndex = $testIndex and tm.testTypeCategoryId = ttc.testTypeCategoryId) AS `ms$testName`";
		}
		//foreach student, foreach test fetch the student marks
		$testArray[$testTypeRecord['testTypeCategoryId']] = $testDetailsArray;
	}

	$allDetailsArray['testDetails'] = $testArray;

	$totalRecordArray = $studentReportsManager->countTestWiseMarksResultConsolidated($classId);

	$cnt1 = $totalRecordArray[0]['cnt'];

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$resultDataArray = $studentReportsManager->getTestWiseMarksResultConsolidated($classId, $subjectId, $firstString, $secondString, $limit);
	$cnt = count($resultDataArray);

	$allDetailsArray['totalRecords'] = $cnt1;


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }

	$allDetailsArray['resultData'] = $valueArray;
	echo json_encode($allDetailsArray);



//// $History: initTestWiseMarksConsolidatedReport.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/23/09    Time: 3:54p
//Created in $/LeapCC/Library/StudentReports
//added file for test wise consolidated marks report.
//
//

?>