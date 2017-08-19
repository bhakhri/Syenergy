<?php 
//This file is used as printing version for attendance report.
//
// Author :Rajeev Aggarwal
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

 	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$classId = $REQUEST_DATA['classId']; 
	$interval = $REQUEST_DATA['interval']; 
	$subjectCode = $REQUEST_DATA['subjectCode']; 
	$subjectType = $REQUEST_DATA['subjectType']; 
	$className  = $REQUEST_DATA['className']; 
	$subjectName  = $REQUEST_DATA['subjectName']; 

	if (!empty($classId)) {
		$conditions .= " AND ttm.classId= '$classId' ";
	}
	if (!empty($subjectCode)) {
		$conditions .= " AND ttm.subjectId = $subjectCode";
	}
	if (!empty($subjectType)) {
		$conditions .= " AND s.subjectTypeId = $subjectType";
	}
	$intervalArr = explode("-", $interval);
 


	$studentRecordArray = $studentReportsManager->getAllTransferredDetails($conditions,$intervalArr[0],$intervalArr[1],"stu.firstName");

    $cnt = count($studentRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {

		$valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
    }

	$reportManager->setReportWidth(500);
	$reportManager->setReportHeading('Student Internal Marks Consolitated Report');
	$reportManager->setReportInformation("For <B>Class:</B>".$className." <B>Subject:</B>".$subjectName);

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align=right', "align='right' ");
	$reportTableHead['studentName']		=	array('Student Name','width=25% align="left"', 'align="left"');
	$reportTableHead['rollNo']			=	array('Roll No','width="10%" align="left" ', 'align="left"');
	$reportTableHead['universityRollNo']=	array('University No','width="12%" align="left" ', 'align="left"');
	$reportTableHead['tmarksScored']	=	array('Marks Scored','width="10%" align="right"', 'align="right"');
	 
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksNotEnteredReportPrint.php $
//
?>