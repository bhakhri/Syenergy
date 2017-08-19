<?php
//This file is used as printing version for test marks updation report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = "$sortField $sortOrderBy";

	$studentId = $REQUEST_DATA['studentId'];

	$attendanceArray = $studentManager->getDeleteStudentAttendanceReportDetail($studentId,$orderBy);
	$cnt = count($attendanceArray);
	$studentName = $attendanceArray[0]['studentName'];
	$rollNo = $attendanceArray[0]['rollNo'];

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$attendanceArray[$i]['Percentage'] = "0.00";

		if($attendanceArray[$i]['attended'] > 0 && $attendanceArray[$i]['delivered'] > 0 ) {
			$attendanceArray[$i]['Percentage']="".ROUND((($attendanceArray[$i]['attended'] / $attendanceArray[$i]['delivered'])*100),2)."";

		}

		$valueArray[] = array(	'srNo' => $i+1 ,
								'className' => $attendanceArray[$i]['className'],
								'subjectName' => $attendanceArray[$i]['subjectName'],
								'subjectCode' => $attendanceArray[$i]['subjectCode'],
								'delivered' => $attendanceArray[$i]['delivered'],
								'attended' => $attendanceArray[$i]['attended'],
								'percentage' => $attendanceArray[$i]['Percentage']
							);
	}

	//$rollNo = $offenseArray[0]['rollNo'];
	//$studentName = $offenseArray[0]['studentName'];
	//$className = $offenseArray[0]['className'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Attendance Report');
	$reportManager->setReportInformation("Roll No : $rollNo, Student Name : $studentName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',				'width="4%" align="left" ', "align='left'");
	$reportTableHead['className']			=	array('Class Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['subjectName']			=	array('Subject Name',	'width=15% align="left"', 'align="left"');
	$reportTableHead['subjectCode']			=	array('Subject Code',	'width=10% align="left"', 'align="left"');
	$reportTableHead['delivered']			=	array('Lecture Delivered',		'width="10%" align="right"', 'align="right"');
	$reportTableHead['attended']			=	array('Lecture Attended',			'width="10%" align="right"', 'align="right"');
	$reportTableHead['percentage']			=	array('%age',		'width="10%" align="right"', 'align="right"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>