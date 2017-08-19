<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

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

	$allDetailsArray = array();

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
	
	$allDetailsArray['testDetails'] = $testArray;

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


	$allDetailsArray['resultData'] = $valueArray;



	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';
	if ($subjectId != 'all') {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "WHERE subjectId  = $subjectId");
		$subCode = $subCodeArray[0]['subjectCode'];
	}


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Test wise Marks Report');
	$reportManager->setReportInformation("For:  Degree : $className2, Subject : $subCode, Group : $groupCode");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="2%" align=right rowspan="3"', "align='right' ");
	$reportTableHead['rollNo']					=	array('C.Roll No.',	'width=8% align="left" rowspan="3"' , 'align="left"');
	$reportTableHead['universityRollNo']			=	array('U.Roll No.',				'width="8%" rowspan="3" align="left" ', 'align="left"');
	$reportTableHead['studentName']				=	array('Student Name',			'width="20%" rowspan="3" align="left"', 'align="left"');


	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $allDetailsArray);
	$reportManager->showTestWiseMarksReport();

//$History : listTestWiseMarksReportPrint.php $
//
?>