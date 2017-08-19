<?php 
//This file is used as printing version for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	define('MODULE','BusStopRouteMapping');
        define('ACCESS','view');
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/BusStopManager.inc.php");
	$busStopManager = BusStopManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	
	$timeTablelabelId = trim($REQUEST_DATA['labelId']);
        $routeId = trim($REQUEST_DATA['routeId']);
     
	$timeTablelabelName = trim($REQUEST_DATA['labelName']);
        $routeName = trim($REQUEST_DATA['routeName']);
	

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
        $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';
        $orderBy = " $sortField $sortOrderBy";  
	
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$recordArray = array();

	$condition   =" AND IFNULL(rsm.mappingId,'-1') != '-1' ";
	$busStopRecordArray = $busStopManager->getBusRoutMappingList($timeTablelabelId,$routeId,$orderBy,$limit,$condition);

	$reportManager->setReportInformation("For Route ". $routeName." As On $formattedDate ");

	$cnt = count($busStopRecordArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$busStopRecordArray[$i]);
   }

	$reportManager->setReportWidth(600);
	$reportManager->setReportHeading('Bus Route Stop Mapping');
	
	$reportTableHead				=	array();
	//associated key				        col.label, col. width,	data align		
	$reportTableHead['srNo']			=       array('#','width="3%"  align="left" ', "align='left' ");
	$reportTableHead['stopName']			=	array('Stop Name','width="8%" align="left" ','align="left"');
	$reportTableHead['stopAbbr']			=	array('Stop Abbr','width=25% align="left"','align="left"');
	$reportTableHead['transportCharges']		=	array('Transport Charges','width="8%" align="left"','align="left"');
	
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
