<?php 
//This file is used as printing version for branch wise employee for management role.
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/Management/StudentAdmissionManager.inc.php");
	$studentAdmissionManager = StudentAdmissionManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";

	//branch Code
	$batchCode = $REQUEST_DATA['branchCode'];

	//selected Year
	$selectedYear = $REQUEST_DATA['selectedYear'];
	
	/* END: search filter */
	$conditions = "  AND bat.batchName ='$batchCode' AND YEAR(dateOfAdmission)= $selectedYear";
    $recordArray = $studentAdmissionManager->getBatchList($conditions,'',' cls.className DESC');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		
		$recordArray[$i]['fatherName']= $titleResults[$recordArray[$i]['fatherTitle']].' '.$recordArray[$i]['fatherName'];

		$recordArray[$i]['studentName']= $recordArray[$i]['firstName'].' '.$recordArray[$i]['lastName'];

		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Student List for '.$batchCode.' Batch for '.$selectedYear.' Year');
	   
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['studentName']		=	array('Student','width=20% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentGender']	=	array('G','width=2% align="left"', 'align="left" valign="top"');
	$reportTableHead['studentEmail']	=	array('Email','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['studentMobileNo']	=	array('Mobile','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['dateOfBirth']		=	array('DOB','width="9%" align="left"', 'align="left" valign="top"');
	$reportTableHead['className']		=	array('Class','width="25%" align="left"', 'align="left" valign="top"');
	$reportTableHead['fatherName']		=	array('Father Name','width="22%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: studentBatchYearReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/27/08   Time: 5:34p
//Created in $/LeapCC/Templates/Management
//intial checkin
?>