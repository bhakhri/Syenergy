<?php 
//This file is used as printing version for room type.
//
// Author :Jaineesh
// Created on : 16.06.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(MODEL_PATH . "/RoomTypeManager.inc.php");
    $roomTypeManager = RoomTypeManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
	$conditionsArray = array();
	$qryString = "";
    

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' WHERE (roomType LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR abbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomType';

	 
    $orderBy="$sortField $sortOrderBy"; 

    $roomTypeArray = $roomTypeManager->getRoomTypeList($conditions,$orderBy,'');

	$cnt = count($roomTypeArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$roomTypeArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Room Type Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				    =	array('#','width=3% align="left"','align="left"' );
    $reportTableHead['roomType']				=   array('Room Type','width=50% align="left"', 'align="left"');
	$reportTableHead['abbr']					=   array('Abbreviation','width=40% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();


?>