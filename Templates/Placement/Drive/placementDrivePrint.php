<?php 
// This file is used as printing version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
    $driveManager = DriveManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $filter = ''; 
    if (!empty($search)) {
     $filter = ' AND ( c.companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR d.placementDriveCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'placementDriveCode';
    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $driveManager->getPlacementDriveList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $time=explode(':',$recordArray[$i]['startTime']);
        $recordArray[$i]['startTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['startTimeAmPm'];
        
        $time=explode(':',$recordArray[$i]['endTime']);
        $recordArray[$i]['endTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['endTimeAmPm'];
       
       
       $recordArray[$i]['startDate']=UtilityManager::formatDate($recordArray[$i]['startDate']);
       $recordArray[$i]['endDate']=UtilityManager::formatDate($recordArray[$i]['endDate']);
       
 	  $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Placement Drive Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead				       =   array();
	$reportTableHead['srNo']		       =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['placementDriveCode'] =   array('Placement Drive Code','width=20% align="left"', 'align="left"');
	$reportTableHead['companyCode']	       =   array('Company','width=15% align="left"', 'align="left"');
	$reportTableHead['startDate']	       =   array('From','width="5%" align="center" ', 'align="center"');
    $reportTableHead['startTime']          =   array('Time','width="5%" align="center" ', 'align="center"');
    $reportTableHead['endDate']            =   array('To','width="5%" align="center" ', 'align="center"');
    $reportTableHead['endTime']            =   array('Time','width="5%" align="center" ', 'align="center"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>