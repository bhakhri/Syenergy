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

	$finalResultArray = $studentManager->getDeleteStudentFinalReportDetail($studentId, $orderBy);
	$cnt = count($finalResultArray);
	$studentName = $finalResultArray[0]['studentName'];
	$rollNo = $finalResultArray[0]['rollNo'];

	$valueArray = array();

	for($i=0;$i<$cnt;$i++) {

		$valueArray[] = array(	'srNo' => $i+1 ,
								'className' => $finalResultArray[$i]['className'],
								'subjectName' => $finalResultArray[$i]['subjectName'],
								'subjectCode' => $finalResultArray[$i]['subjectCode'],
								'conductingAuthority' => $finalResultArray[$i]['conductingAuthority'],
								'maxMarks' => "".ROUND($finalResultArray[$i]['maxMarks'],4)."",
								'marksScored' => "".ROUND($finalResultArray[$i]['marksScored'],4).""
							);
	}

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Fianl Result Report');
	$reportManager->setReportInformation("Roll No : $rollNo, Student Name : $studentName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',				'width="4%" align="left"', "align='left' ");
	$reportTableHead['className']			=	array('Class Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['subjectName']			=	array('Subject Name',	'width=15% align="left"', 'align="left"');
	$reportTableHead['subjectCode']			=	array('Subject Code',	'width=10% align="left"', 'align="left"');
	$reportTableHead['conductingAuthority']	=	array('Conducting Authority',		'width="12%" align="left"', 'align="left"');
	$reportTableHead['maxMarks']			=	array('Max Marks',		'width="8%" align="right"', 'align="right"');
	$reportTableHead['marksScored']			=	array('Marks Scored',	'width="8%" align="right"', 'align="right"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>