<?php 
//This file is used as printing version for payment history.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/BusRouteManager.inc.php");
    $busRouteManager = BusRouteManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    

	$busRouteId = trim($REQUEST_DATA['busRouteId']);


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if ($search != '') {
        $conditions =' WHERE (br.routeName LIKE "'.add_slashes(trim($search)).'%" OR br.routeCode LIKE "'.add_slashes(trim($search)).'%" OR br.routeCharges LIKE "'.add_slashes(trim($search)).'%")';
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeCode';

    $orderBy = " $sortField $sortOrderBy";

	$routeConditions = "WHERE br.busRouteId = ".$busRouteId;
	$busRouteArray = $busRouteManager->getBusRouteList($routeConditions,'',$orderBy);
	$routeCode = $busRouteArray[0]['routeCode'];

    $conditions = "AND bp.busRouteId = ".$busRouteId;
	$recordArray = $busRouteManager->getPassengerRouteList($conditions);

	$cnt = count($recordArray);
	

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
	}


	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Bus Route Passenger Report');
    $reportManager->setReportInformation("SearchBy: Route Code - $routeCode");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="5%" align="left"', 'align="left"');
    $reportTableHead['roleName']			=   array('Role','width=15% align="left"', 'align="left"');
	$reportTableHead['studentName']			=	array('Name','width=20% align="left"', 'align="left"');
	$reportTableHead['rollNo']				=	array('Roll No./Employee Code','width=10% align="left"', 'align="left"');
	$reportTableHead['instituteName']		=	array('Institute Name','width=20% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: $
//
?>