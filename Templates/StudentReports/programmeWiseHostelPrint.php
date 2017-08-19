<?php 
//This file is used as printing version for attendance report.
//
// Author :Rajeev Aggarwal
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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

	$hostelId = $REQUEST_DATA['hostelId']; 
	$degreeId = $REQUEST_DATA['degreeId']; 
	$branchId = $REQUEST_DATA['branchId']; 
	$studentGender = $REQUEST_DATA['studentGender']; 
	 
 	 
	if (!empty($hostelId)) {
		$conditions .= " AND st.hostelId = $hostelId";
	}
	if (!empty($studentGender)) {
		$conditions .= " AND st.studentGender = '$studentGender'";
	}
	if (!empty($degreeId)) {
		$conditions .= " AND dg.degreeId = '$degreeId'";
	}
	if (!empty($branchId)) {
		$conditions .= " AND br.branchId = '$branchId'";
	}
	 
	$studentRecordArray = $studentReportsManager->getAllHostelDetails($conditions,"studentName");

    $cnt = count($studentRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {

		$valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
    }

	$reportManager->setReportWidth(550);
	$reportManager->setReportHeading('Hostel Details Report');
	$reportManager->setReportInformation("For Programme: ".$studentRecordArray[0]['degreeCode'].'-'.$studentRecordArray[0]['branchCode']);

	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align=right', "align='right' ");
	$reportTableHead['studentName']		=	array('Student Name','width=15% align="left"', 'align="left"');
	$reportTableHead['rollNo']			=	array('Roll No','width="10%" align="left" ', 'align="left"');
	$reportTableHead['studentMobileNo']=	array('Mobile','width="6%" align="left" ', 'align="left"');
	$reportTableHead['hostelName']	=	array('Hostel','width="6%" align="left"', 'align="left"');
	$reportTableHead['roomName']	=	array('Room','width="5%" align="left"', 'align="left"');
	$reportTableHead['studentEmail']	=	array('Email','width="10%" align="left"', 'align="left"');
	$reportTableHead['className']	=	array('Class','width="15%" align="left"', 'align="left"');
	 
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksNotEnteredReportPrint.php $
//
?>