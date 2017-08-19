<?php 
//This file is used as printing version for event list.
//
// Author :Rajeev Aggarwal
// Created on : 20-10-2008
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

// $History: scEventReportPrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:43p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>