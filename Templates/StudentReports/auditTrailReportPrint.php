<?php
//----------------------------------------------------------------------------------
//This file returns the array of Audit Description, Username based on the aduit Type
//
// Author :Kavish Manjkhola
// Created on : 24-Mar-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------
?>
<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$fromDate = add_slashes($REQUEST_DATA['fromDate']);
	$toDate= add_slashes($REQUEST_DATA['toDate']);
	$auditType =  add_slashes($REQUEST_DATA['auditType']);
	$sortField = add_slashes($REQUEST_DATA['sortField']);
	$sortOrderBy = add_slashes($REQUEST_DATA['sortOrderBy']);
	$conditions = "WHERE (at.auditDateTime BETWEEN '$fromDate' AND '$toDate') AND u.userId = at.userId";
	if ($auditType != '') {
		$conditions .= " ";
		$conditions .= "AND at.auditType = '$auditType'";
	}
	$timeFormat = '12';
	$orderBy =" $sortField $sortOrderBy";

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$auditTrailRecordArray = $studentReportsManager->getAuditTrailDetails($conditions,$orderBy,$limit);
	$cnt1 = count($auditTrailRecordArray);
	$valueArray = array();
	for($i=0; $i<$cnt1; $i++) {
		$auditTrailRecordArray[$i]['auditDateTime'] = UtilityManager::formatDate($auditTrailRecordArray[$i]['auditDateTime'],true,$timeFormat);  
		$auditTrailRecordArray[$i]['auditType'] = $auditTrailArray[$auditTrailRecordArray[$i]['auditType']];
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$auditTrailRecordArray[$i]);
	}
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Audit Trail Report');
	$reportTableHead						=	array();

					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#','width=2% align="left"','align="left"');
	$reportTableHead['auditType']			=	array('Audit Type',	'width=8% align="left"', 'align="left"');
	$reportTableHead['description']			=	array('Audit Description', 'width="22%" align="left" ', 'align="left"');
	$reportTableHead['auditDateTime']		=	array('Audit Date Time', 'width="5%" align="left"', 'align="left"');
	$reportTableHead['userName']			=	array('Audit User', 'width="5%" align="left"','align="left"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
?>