<?php 
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
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
	echo UtilityManager::includeCSS("css.css");

	$imageName = "subjectWiseConsolidatedGraph.jpg";


	$arr = explode('-',$REQUEST_DATA['degree']);
	$universityId = $arr[0];
	$degreeId = $arr[1];
	$branchId = $arr[2];
	$studyPeriodId = $REQUEST_DATA['studyPeriodId'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$sortField = $REQUEST_DATA['sortField'];

	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$studyPeriodId."'";   
	$classIdArray = $studentReportsManager->getClassId($classFilter);
	$classId = $classIdArray[0]['classId'];
	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';
	if ($subjectId == 'all') {
		$i = 1;
		$XRangeArray = array();
		$dataArray2 = array();
		$subjectsArray = $studentReportsManager->getClassConsolidatedSubjects($classId);
		$valueArray['subjects'] = $subjectsArray;

		foreach($subjectsArray as $subjectRecord) {
			$subjectId = $subjectRecord['subjectId'];
			$subjectCode = $subjectRecord['subjectCode'];
			$queryPart .= ", 
							(
									SELECT 
											COUNT(b.studentId) AS cnt 
									FROM	student b 
									WHERE	b.studentId 
									IN (
										SELECT 
													c.studentId 
										FROM		".TEST_TRANSFERRED_MARKS_TABLE." c 
										WHERE		c.classId = $classId AND 
													c.subjectId = $subjectId 
										GROUP BY	CONCAT(c.studentId, c.classId, c.subjectId) 
										HAVING		ROUND(SUM(c.marksScored)/SUM(c.maxMarks)*100) 
										BETWEEN		a.rangeFrom AND a.rangeTo
									   )
							) AS $subjectCode
						";
		}
		$dataArray = $studentReportsManager->getAllSubjectConsolidatedMarks($queryPart);
		$cnt = count($dataArray);
		for($i=0;$i<$cnt;$i++) {
			$XRangeArray[] = $dataArray[$i]['rangeLabel'];
		}
		$maxStudents = 0;
		foreach($subjectsArray as $subjectRecord) {
			$subjectCode = $subjectRecord['subjectCode'];
			foreach($dataArray as $dataRecord) {
				$resultDataArray[$subjectCode][] =  $dataRecord[$subjectCode];
				$studentsArray[] = $dataRecord[$subjectCode];
			}
		}

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

	}
	else {

		$i = 1;
		$XRangeArray = array();
		$dataArray2 = array();

		$dataArray = $studentReportsManager->getSubjectWiseConsolidatedMarks($classId, $subjectId);
		$cnt = count($dataArray);
		for($i=0;$i<$cnt;$i++) {
			$XRangeArray[] = $dataArray[$i]['rangeLabel'];
			$dataArray2[] = $dataArray[$i]['studentCount'];
		}
	   $subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
	   $subCode = $subCodeArray[0]['subjectCode'];

	  $maxStudents = max($dataArray2);
	   if ($maxStudents < 10) {
		   $yRangeArray = range(0, 10);
	   }
	   else if($maxStudents <= 100){
		   $step = ($maxStudents/10) + 1;
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
	   
	   $resultDataArray = array($dataArray2);
	}

	$barGraphManager->setXLabel("Percentage");
	$barGraphManager->setYLabel("No. of Students");
	$barGraphManager->setXLabelsArray($XRangeArray);
	$barGraphManager->setYLabelsArray($yRangeArray);
	$barGraphManager->setDataArray($resultDataArray);
	$barGraphManager->makeGraph(IMG_PATH . "/$imageName");

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Subjectwise Consolidated Report');
	$reportManager->setReportInformation("$className2 Subject: $subCode");
	$reportManager->showGraph(IMG_HTTP_PATH . "/$imageName");

//$History: listSubjectWiseConsolidatedGraphPrint.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:19p
//Updated in $/LeapCC/Templates/StudentReports
//applied multiple table defines.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/27/08    Time: 6:48p
//Updated in $/Leap/Source/Templates/StudentReports
//corrected image path
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:36p
//Updated in $/Leap/Source/Templates/StudentReports
//added code for "all subjects"
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/21/08    Time: 5:12p
//Updated in $/Leap/Source/Templates/StudentReports
//changed file as per changes made in BarGraphManager.inc.php
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/21/08    Time: 11:28a
//Updated in $/Leap/Source/Templates/StudentReports
//removed following functions:
//1. getRangeValues()
//2. getStudentsInRange()
//and added following function:
//1. getSubjectWiseConsolidatedMarks()
//for subjectwise consolidated report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:39p
//Created in $/Leap/Source/Templates/StudentReports
//file added for subject wise consolidated report
//


?>
