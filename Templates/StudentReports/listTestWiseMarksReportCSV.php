<?php 
//-------------------------------------------------------
//  This File outputs the search student to the CSV
//
// Author :Ajinder Singh
// Created on : 24-03-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$classId = $REQUEST_DATA['class1']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3


	//fetch class students
	$conditions = '';
	$groupCode = 'All';
	if ($groupId != 'all') {
		$conditions = "";
		$groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = $groupId");
		$groupCode = $groupCodeArray[0]['groupShort'];
	}

	//fetch distinct types of test taken on this class
	$str2 = "";
	if ($subjectId != 'all') {
		$str2 = " AND a.subjectId = $subjectId ";
	}
	if ($groupId != 'all') {
		$str2 .= " AND a.groupId = $groupId ";
	}
	$testTypeArray = $studentReportsManager->getClassTestTypes($classId, $str2);

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
		if ($groupId != 'all') {
			$str .= " AND a.groupId = $groupId ";
		}

		$testDetailsArray = $studentReportsManager->getTestDetails($testTypeRecord['testTypeCategoryId'], $str);

		foreach($testDetailsArray as $testRecord) {
			$testId = $testRecord['testId'];
			$firstString .= ",(select IF(CONCAT(tm$testId.isPresent,tm$testId.isMemberOfClass)=11,tm$testId.marksScored,IF(CONCAT(tm$testId.isPresent,tm$testId.isMemberOfClass)=01,'A','N/A')) from ".TEST_MARKS_TABLE." tm$testId, ".TEST_TABLE." tm where tm.testId = tm$testId.testId and tm.groupId = sg.groupId  and a.studentId = tm$testId.studentId and tm$testId.testId = $testId) AS ms$testId";
			$secondString = " and		sg.groupId = $groupId ";
		}
		//foreach student, foreach test fetch the student marks
		$testArray[$testTypeRecord['testTypeCategoryId']] = $testDetailsArray;
	}

	$mmSubjectArray = $studentReportsManager->checkSubjectMM($classId, $subjectId);
	$mmSubjectCount = $mmSubjectArray[0]['cnt'];

	$tableName = 'student_groups';
	if ($mmSubjectCount > 0) {
		$tableName = 'student_optional_subject';
	}
	
	$sortBy = '';

	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'cRollNo') {
		$sortBy = ' length(a.rollNo)+0,a.rollNo ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' length(a.universityRollNo)+0,a.universityRollNo ';
	}
	elseif ($sorting == 'name') {
		$sortBy = ' studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];

	$resultDataArray = $studentReportsManager->getTestWiseMarksResult($classId, $firstString, $secondString, '',$tableName, $sortBy);

	$cnt = count($resultDataArray);


	$valueArray = array();


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }
	$csvData = '';
	$csvData .= "#, C.RollNo, U.Roll No., Student Name";
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeName = $testTypeRecord['testTypeName'];
		$csvData .= ", $testTypeName";
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		$testTypeTests = count($testArray[$testTypeCategoryId]);
		$testCounter = 1;
		while ($testCounter < $testTypeTests) {
			$csvData .= ",";
			$testCounter++;
		}
	}
	$csvData .= "\n";
	$csvData .= ",,,";
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		foreach($testArray[$testTypeCategoryId] as $testRecord) {
			$testName = $testRecord['testName'];
			$csvData .= ", $testName";
		}
	}
	$csvData .= "\n";
	$csvData .= ",,,";
	$testIdArray = array();
	foreach($testTypeArray as $testTypeRecord) {
		$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
		foreach($testArray[$testTypeCategoryId] as $testRecord) {
			$maxMarks = $testRecord['maxMarks'];
			$csvData .= ", $maxMarks";
			$testIdArray[] = 'ms'.$testRecord['testId'];
		}
	}
	$csvData .= "\n";
	foreach($valueArray as $valueRecord) {
		$srNo = $valueRecord['srNo'];
		$rollNo = $valueRecord['rollNo'];
		$universityRollNo = $valueRecord['universityRollNo'];
		$studentName = $valueRecord['studentName'];
		$csvData .= "$srNo, ".parseCSVComments($rollNo).", ".parseCSVComments($universityRollNo).", $studentName";
		foreach($testIdArray as $testId) {
			$testMarks = $valueRecord[$testId];
			$csvData .= ",$testMarks";
		}
		$csvData .= "\n";
	}

	 UtilityManager::makeCSV($csvData,'TestWiseMarksReport.csv');
	 die;


//$History :  $
//


?>