<?php
//This file sends the data, creates the image on runtime
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


$universityId = $arr[0];
$degreeId = $arr[1];
$branchId = $arr[2];

$studyPeriodId = $REQUEST_DATA['studyPeriod'];
$subjectId = $REQUEST_DATA['subjectId'];
$employeeId = $REQUEST_DATA['teacherId'];
$groupId = $REQUEST_DATA['groupId'];

$testId = $REQUEST_DATA['testId'];

$valueArray = array();


$totalStudentsArray = $studentReportsManager->getTestTotalStudents($classId, $testId);
$totalStudents = $totalStudentsArray[0]['cnt'];
$valueArray['totalStudents'] = array($totalStudents,'');
if ($totalStudents == 0) {
	$valueArray['data'] = array();
	echo json_encode($valueArray);
	die;
}

$presentStudentsArray = $studentReportsManager->getTestPresentStudents($classId, $testId);
$presentStudents = $presentStudentsArray[0]['cnt'];
$presentPer = round($presentStudents / $totalStudents * 100,2);
$valueArray['presentStudents'] = array($presentStudents, $presentPer);

$absentStudents = $totalStudents - $presentStudents;
$absentPer = round($absentStudents / $totalStudents * 100,2);
$valueArray['absentStudents'] = array($absentStudents, $absentPer);

$dataArray = $studentReportsManager->getClassWiseConsolidatedMarks($classId, $testId);
$testNameArray = $studentReportsManager->getSingleField('test', 'testAbbr', " WHERE testId = $testId");
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
$XRangeArray = array();

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

//Graph Coding Starts

require_once(BL_PATH . "/BarGraphManager.inc.php");
$barGraphManager = BarGraphManager::getInstance();

$barGraphManager->setXLabel("Total Score");
$barGraphManager->setYLabel("No. of Students");
$barGraphManager->setXLabelsArray($XRangeArray);
$barGraphManager->setYLabelsArray($yRangeArray);
$barGraphManager->setDataArray($mainGraphArray);

$imageName = "classWiseConsolidatedGraph.jpg";
$barGraphManager->makeGraph(IMG_PATH . "/$imageName");

$valueArray['imagePath'] = IMG_HTTP_PATH . "/$imageName";

echo json_encode($valueArray);


//$History: initClassConsolidatedReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/22/08    Time: 12:03p
//Updated in $/Leap/Source/Library/StudentReports
//added code for "no record found"
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:39p
//Updated in $/Leap/Source/Library/StudentReports
//fixed bug found during self testing
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