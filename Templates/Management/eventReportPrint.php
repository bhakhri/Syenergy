<?php 
//This file is used as printing version for event list.
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	$eventMonth = $REQUEST_DATA['eventMonth'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventId';

	$orderBy="$sortField $sortOrderBy"; 

	/* END: search filter */
	$conditions = " AND MONTH(startDate) = $eventMonth";
    $recordArray = $dashboardManager->getMonthEventList($conditions,$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Event List Report for Month '.$monArr[$eventMonth]);
	   
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =	array('#','width="3%"', "align='center' ");
	$reportTableHead['eventTitle']		 =	array('Event Title','width=20% align="left"', 'align="left"');
	$reportTableHead['shortDescription'] =	array('Description','width=59% align="left"', 'align="left"');
	$reportTableHead['startDate']		 =	array('Start Date','width="9%" align="left" ', 'align="left"');
	$reportTableHead['endDate']			 =	array('End Date','width="9%" align="left"', 'align="left"');
	 
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: eventReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>