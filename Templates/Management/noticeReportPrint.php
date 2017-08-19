<?php 
//This file is used as printing version for notice list for management role.
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";

	//event month
	$noticeMonth = $REQUEST_DATA['noticeMonth'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'n.noticeId';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = " AND MONTH(visibleFromDate) = $noticeMonth";
    $recordArray = $dashboardManager->getMonthNoticeList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$recordArray[$i]['noticeText']= nl2br($recordArray[$i]['noticeText']); 

		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Notice List Report for Month '.$monArr[$noticeMonth]);
	   
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =	array('#','width="3%"', 'align="center" valign="top"');
	$reportTableHead['noticeSubject']	 =	array('Subject','width=20% align="left" valign="top"', 'align="left" valign="top"');
	$reportTableHead['departmentName']	 =	array('Department','width=20% align="left" valign="top"', 'align="left" valign="top"');
	$reportTableHead['noticeText']		 =	array('Description','width=59% align="left"', 'align="left"');
	$reportTableHead['visibleFromDate']	 =	array('From','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['visibleToDate']	 =	array('To','width="9%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: noticeReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>