<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	

	$allDetailsArray = array();

	//fetch class students
	$conditions = '';
	$groupCode = 'All';

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

	$resultDataArray = $studentReportsManager->getTestWiseMarksResultConsolidated($classId, $subjectId, $firstString, $secondString, $limit);

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
	$reportManager->setReportHeading('Test Type Category wise Detailed Report');
	$reportManager->setReportInformation("$className2, Subjects : $subCode, Group : $groupCode <br>Note:<b>&nbsp;&nbsp;TNH: Test Not Held</b>");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="5%" rowspan="2" align="left"', 'align="left"');
	$reportTableHead['rollNo']					=	array('Roll No.',	'width=8% align="left" rowspan="2"' , 'align="left"');
	$reportTableHead['universityRegNo']			=	array('U.Reg No.',				'width="8%" rowspan="2" align="left" ', 'align="left"');
	$reportTableHead['studentName']				=	array('Student Name',			'width="15%" rowspan="2" align="left"', 'align="left"');
	$reportTableHead['groupName']				=	array('Group Name',				'width="10%" rowspan="2" align="left"', 'align="left"');


	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $allDetailsArray);
	$reportManager->showTestWiseMarksConsolidatedReport();

////$History: listTestWiseMarksConsolidatedReportPrint.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 11/25/09   Time: 4:27p
//Updated in $/LeapCC/Templates/StudentReports
//RESOLVED ISSUE 0002125
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/22/09   Time: 10:08a
//Updated in $/LeapCC/Templates/StudentReports
//added code for showing abbreviation meaning.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/23/09    Time: 3:54p
//Created in $/LeapCC/Templates/StudentReports
//added file for test wise consolidated marks report.
//
//
?>