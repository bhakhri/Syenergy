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
    require_once(MODEL_PATH . "/BusRouteManagerNew.inc.php");
    $busRouteManager = BusRouteManagerNew::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if ($search != '') {
        $conditions = ' WHERE (br.routeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          br.routeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          b.busNo  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="br.$sortField $sortOrderBy"; 


	$totalArray = $busRouteManager->getTotalBusRoute($conditions);
    $recordArray = $busRouteManager->getBusRouteList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Bus Route Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', 'align="left"');   
	$reportTableHead['routeName']           =   array('Vehicle Route Name','width=15% align="left"', 'align="left"');
	$reportTableHead['routeCode']			=	array('Vehicle Route Code','width=15% align="left"', 'align="left"');
	$reportTableHead['busNo']               =   array('Bus No.','width=20% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: busRoutePrint.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Templates/BusRoute
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/27/09    Time: 2:08p
//Updated in $/LeapCC/Templates/BusRoute
//Gurkeerat: resolved issue 1296
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/BusRoute
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:54p
//Created in $/Leap/Source/Templates/BusRoute
//Added functionality for bus route report print
?>
