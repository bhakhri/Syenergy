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
    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $tranportManager = FuelManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = trim($REQUEST_DATA['searchbox']);
    $conditions = ''; 
    if (!empty($search)) {
        $conditions = ' AND  ( trs.name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.busNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.lastMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.currentMilege LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  f.litres LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.amount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';

    $orderBy=" $sortField $sortOrderBy"; 

    $recordArray = $tranportManager->getFuelList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['dated']=UtilityManager::formatDate($recordArray[$i]['dated']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Fuel  Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%"', "align='center' ");
    $reportTableHead['name']            =   array('Staff','width=10% align="left"', 'align="left"');
	$reportTableHead['busNo']			=	array('Registration No.','width=15% align="left"', 'align="left"');
	$reportTableHead['dated']			=	array('Date','width="8%" align="center" ', 'align="center"');
    $reportTableHead['lastMilege']      =   array('Last Mileage','width="8%" align="right" ', 'align="right"');
    $reportTableHead['currentMilege']   =   array('Current Mileage','width="10%" align="right" ', 'align="right"');
    $reportTableHead['litres']          =   array('Litres','width="7%" align="left" ', 'align="left"');
    $reportTableHead['amount']          =   array('Amount','width="5%" align="right" ', 'align="right"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: fuelPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/08/09    Time: 15:50
//Created in $/Leap/Source/Templates/Fuel
//Done bug fixing.
//bug ids---
//0000817 to 0000821
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/07/09   Time: 13:34
//Created in $/Leap/Source/Templates/BusRepair
//Done bug fixing.
//Bug ids---
//0000793,0000792,0000791,0000790,0000789,0000788,
//0000787,0000786
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 30/07/09   Time: 10:58
//Updated in $/Leap/Source/Templates/BusStop
//Done bug fixing.
//bug ids---
//0000759 to 0000770,0000781,0000782
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/Leap/Source/Templates/BusStop
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/06/09   Time: 11:15
//Updated in $/Leap/Source/Templates/BusStop
//Done bug fixing.
//bug ids---0000063,0000082,0000083,0000085,0000087,0000090,0000092,
//0000095
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/10/09    Time: 12:51p
//Created in $/Leap/Source/Templates/BusStop
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:44p
//Created in $/Leap/Source/Templates/BusStop
//Added functionality for bus stop report print
?>