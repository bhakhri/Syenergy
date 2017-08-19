<?php
//This file is used as printing version for test marks updation report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

	$testArray = $studentManager->getDeleteStudentTestMarksReportDetail($studentId,$orderBy);
	/*echo '<pre>';
	print_r($testArray);
	die;*/
	$cnt = count($testArray);
	$studentName = $testArray[0]['studentName'];
	$rollNo = $testArray[0]['rollNo'];

	$valueArray = array();

	for($i=0;$i<$cnt;$i++) {
		$testArray[$i]['testDate'] = UtilityManager::formatDate($testArray[$i]['testDate']);
		$marksObtained1="0.00";

		if ($testArray[$i]['obtained'] >0 && $testArray[$i]['totalMarks'] >0) {
			$testArray[$i]['marksObtained'] = "".ROUND((($testArray[$i]['obtained']/$testArray[$i]['totalMarks'])*100),2)."";
			$marksObtained1 = $testArray[$i]['marksObtained'];
		}

		if ($testArray[$i]['obtained']=='Not MOC'){
			$marksObtained1="-";
		}
		if ($testArray[$i]['obtained']=='A'){
			$marksObtained1="-";
		}

		$valueArray[] = array(	'srNo' => $i+1 ,
								'className' => $testArray[$i]['className'],
								'subjectName' => $testArray[$i]['subjectName'],
								'subjectCode' => $testArray[$i]['subjectCode'],
								'testType' => $testArray[$i]['testType'],
								'totalMarks' => $testArray[$i]['totalMarks'],
								'obtained' => $testArray[$i]['obtained'],
								'percentage' => $marksObtained1
							);
	}

	//$rollNo = $offenseArray[0]['rollNo'];
	//$studentName = $offenseArray[0]['studentName'];
	//$className = $offenseArray[0]['className'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Test Marks Report');
	$reportManager->setReportInformation("Roll No : $rollNo, Student Name : $studentName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',				'width="4%" align="left"', "align='left' ");
	$reportTableHead['className']			=	array('Class Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['subjectName']			=	array('Subject Name',	'width=15% align="left"', 'align="left"');
	$reportTableHead['subjectCode']			=	array('Subject Code',	'width=10% align="left"', 'align="left"');
	$reportTableHead['testType']			=	array('Test Type',		'width="8%" align="left"', 'align="left"');
	$reportTableHead['totalMarks']			=	array('Max Marks',		'width="8%" align="right"', 'align="right"');
	$reportTableHead['obtained']			=	array('Marks Scored',	'width="8%" align="right"', 'align="right"');
	$reportTableHead['percentage']			=	array('%age',		'width="8%" align="right"', 'align="right"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>