<?php 
//This file is used as printing part for class performance report.
//
// Author :Ajinder Singh
// Created on : 27-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . "/BarGraphManager.inc.php");
	$barGraphManager = BarGraphManager::getInstance();

	$arr = explode('-',$REQUEST_DATA['mixedValue']);

	//fetch the classes for the user selected data
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['studyPeriod']."'";   
	$classIdArray = $studentReportsManager->getClassId($classFilter);
	$classId = $classIdArray[0]['classId'];

	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$universityId = $arr[0];
	$degreeId = $arr[1];
	$branchId = $arr[2];

	$studyPeriodId = $REQUEST_DATA['studyPeriod'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$employeeId = $REQUEST_DATA['teacherId'];
	$groupId = $REQUEST_DATA['groupId'];

	$testId = $REQUEST_DATA['testId'];

	$valueArray = array();

	$testNameArray = $studentReportsManager->getSingleField(TEST_TABLE, 'testAbbr', " WHERE testId = $testId");
	$testName = $testNameArray[0]['testAbbr'];
	$valueArray['testName'] = $testName;

	$degreeCodeArray = $studentReportsManager->getSingleField('degree', 'degreeCode', " WHERE degreeId 	 = $degreeId");
	$degreeCode = $degreeCodeArray[0]['degreeCode'];
	$valueArray['degreeCode'] = $degreeCode;

	$branchCodeArray = $studentReportsManager->getSingleField('branch', 'branchCode', " WHERE branchId 	 = $branchId");
	$branchCode = $branchCodeArray[0]['branchCode'];
	$valueArray['branchCode'] = $branchCode;

	$spArray = $studentReportsManager->getSingleField('study_period', 'periodName', " WHERE studyPeriodId = $studyPeriodId");
	$spName = $spArray[0]['periodName'];
	$valueArray['studyPeriod'] = $spName;

	if ($subjectId != 'all') {
		$subArray = $studentReportsManager->getSingleField('subject', 'subjectCode, subjectAbbreviation', " WHERE subjectId  = $subjectId");
		$subName = $subArray[0]['subjectCode'] . '-'. $subArray[0]['subjectAbbreviation'];
	}
	else {
		$subName = 'All';
	}
	$valueArray['subject'] = $subName;

	if ($employeeId == 'all') {
		$empName = 'All';
		$empCode = '';
		$groupShort = 'All';
		$valueArray['employee'] = $empCode . $empName;
		$valueArray['group'] = $groupShort;
	}
	else {
		$empArray = $studentReportsManager->getSingleField('employee', 'employeeCode, employeeName', " WHERE employeeId = $employeeId");
		$empCode = $empArray[0]['employeeCode'];
		$empName = '- '.$empArray[0]['employeeName'];
		$valueArray['employee'] = $empCode . $empName;

		if ($groupId == 'all') {
			$groupShort = 'All';
		}
		else {
			$groupArray = $studentReportsManager->getSingleField('`group`', 'groupShort', " WHERE groupId = $groupId");
			$groupShort = $groupArray[0]['groupShort'];
		}
		$valueArray['group'] = $groupShort;
	}

	$totalStudentsArray = $studentReportsManager->getTestTotalStudents($classId, $testId);
	$totalStudents = $totalStudentsArray[0]['cnt'];
	if ($totalStudents == 0) {
		$valueArray['data'] = array();
		$reportManager->setReportWidth(800);
		$reportManager->setReportHeading('Class Performance Graph');
		$reportManager->setReportInformation("<b>Class:</b> $className2, <b>Subject:</b> $subName, <b>Teacher:</b> $empCode $empName, <b>Group:</b> $groupShort, <b>Test:</b> $testName");
		$reportManager->setReportData(array(), $valueArray);
		$reportManager->showReport(true);
		die;	//to stop the processing here
	}

	$valueArray['totalStudents'] = array($totalStudents,'');

	$presentStudentsArray = $studentReportsManager->getTestPresentStudents($classId, $testId);
	$presentStudents = $presentStudentsArray[0]['cnt'];
	$presentPer = round($presentStudents / $totalStudents * 100,2);
	$valueArray['presentStudents'] = array($presentStudents, $presentPer);

	$absentStudents = $totalStudents - $presentStudents;
	$absentPer = round($absentStudents / $totalStudents * 100,2);
	$valueArray['absentStudents'] = array($absentStudents, $absentPer);

	$dataArray = $studentReportsManager->getClassWiseConsolidatedMarks($classId, $testId);



	$XRangeArray = array();



	$resultArray = array();
	$graphArray = array();
	foreach($dataArray as $dataRecord) {
		$per =  round(($dataRecord['cnt'] / $totalStudents) * 100,2);
		$resultArray[] = array('rangeLabel'=>$dataRecord['rangeLabel'], 'cnt'=>$dataRecord['cnt'], 'per'=>$per);
		$XRangeArray[] = $dataRecord['rangeLabel'];
		$studentsArray[] = $dataRecord['cnt'];
		$graphArray[] = $dataRecord['cnt'];
	}

	$mainGraphArray = array($subArray[0]['subjectCode'] => $studentsArray);


	$maxStudents = max($studentsArray);

	if ($maxStudents < 10) {
	   $yRangeArray = range(0, 10);
	}
	else if($maxStudents <= 100){
	   $step = round(($maxStudents/10)) + 1;
	   $maxValue = $step * 10;
	   $start = 0;
	   $yRangeArray = array();
	   while($start <= $maxValue) {
		   $yRangeArray[] = $start;
		   $start+=$step;
	   }
	}
	else {
	   $step = ($maxStudents/100) + 1;
	   $maxValue = $step * 100;
	   $start = 0;
	   $yRangeArray = array();
	   while($start <= $maxValue) {
		   $yRangeArray[] = $start;
		   $start+=$step;
	   }
	}

	$valueArray['data'] = $resultArray;

	$barGraphManager->setXLabel("Total Score");
	$barGraphManager->setYLabel("No. of Students");
	$barGraphManager->setXLabelsArray($XRangeArray);
	$barGraphManager->setYLabelsArray($yRangeArray);
	$barGraphManager->setDataArray($mainGraphArray);

	$imageName = "classWiseConsolidatedGraph.jpg";
	$barGraphManager->makeGraph(IMG_PATH . "/$imageName");


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Class Performance Graph');
	$reportManager->setReportInformation("<b>Class:</b> $className2, <b>Subject:</b> $subName, <b>Teacher:</b> $empCode $empName, <b>Group:</b> $groupShort, <b>Test:</b> $testName");

	$reportManager->setReportData(array(), $valueArray);
	$reportManager->showClassWiseConsolidatedReport(IMG_HTTP_PATH . "/$imageName");

//$History: listClassWiseConsolidatedReportPrint.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Templates/StudentReports
//added code for multiple tables.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/22/08    Time: 12:03p
//Updated in $/Leap/Source/Templates/StudentReports
//added code for "no record found"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:41p
//Updated in $/Leap/Source/Templates/StudentReports
//fixed bug found during self testing
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:26p
//Created in $/Leap/Source/Templates/StudentReports
//File added for "class performance graph"
//

?>

