<?php 
//This file is used as printing version for notice list for management role.
//
// Author :Rajeev Aggarwal
// Created on : 21-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/Management/ScDashboardManager.inc.php");
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
	$reportTableHead['noticeText']		 =	array('Description','width=59% align="left"', 'align="left"');
	$reportTableHead['visibleFromDate']	 =	array('From','width="9%" align="left" ', 'align="left" valign="top"');
	$reportTableHead['visibleToDate']	 =	array('To','width="9%" align="left"', 'align="left" valign="top"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: scNoticeReportPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/21/08   Time: 2:53p
//Created in $/Leap/Source/Templates/Management
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:43p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>