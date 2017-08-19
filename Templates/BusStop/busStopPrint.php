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
    require_once(MODEL_PATH . "/BusStopManager.inc.php");
    $busStopManager = BusStopManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' AND (bs.stopName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.stopAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.transportCharges LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bsr.routeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" $sortField $sortOrderBy"; 


	$totalArray = $busStopManager->getTotalBusStop($conditions);
    $recordArray = $busStopManager->getBusStopList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Bus Stop Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', 'align="left"');
    $reportTableHead['stopName']            =   array('Vehicle Stop','width=27% align="left"', 'align="left"');
	$reportTableHead['stopAbbr']			=	array('Abbr.','width=18% align="left"', 'align="left"');
    $reportTableHead['studentCount']        =   array('No. of students','width=18% align="left"', 'align="right"'); 
	$reportTableHead['routeCode']			=	array('Vehicle Route','width="15%" align="left" ', 'align="left"');
    $reportTableHead['scheduleTime']        =   array('Time','width="15%" align="center" ', 'align="center"');
    $reportTableHead['transportCharges']    =   array('Transport Charges','width="18%" align="right" ', 'align="right"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: busStopPrint.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Templates/BusStop
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Templates/BusStop
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/27/09    Time: 2:05p
//Updated in $/LeapCC/Templates/BusStop
//Resolved issue 1298
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 30/06/09   Time: 17:45
//Updated in $/LeapCC/Templates/BusStop
//Corrected look and feel of masters which are detected during user
//documentation preparation
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 13:05
//Updated in $/LeapCC/Templates/BusStop
//Done bug fixing.
//bug ids--Issues[03-june-09].doc(1 to 11)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/BusStop
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:44p
//Created in $/Leap/Source/Templates/BusStop
//Added functionality for bus stop report print
?>