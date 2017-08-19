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
    
	require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    

	$vehicleId = $REQUEST_DATA['vehicleNo'];
	$busServiceId = $REQUEST_DATA['busService'];
	$serviceFromDate= $REQUEST_DATA['serviceFromDate'];
	$serviceToDate = $REQUEST_DATA['serviceToDate'];

	if($busServiceId == 1) {
		$busServiceType = 'Free';
	}
	else {
		$busServiceType = 'Paid';
	}

    $filter = "AND bs.doneOnDate BETWEEN '$serviceFromDate' AND '$serviceToDate'";
    $vehicleServiceRecordArray = $vehicleManager->getVehicleServiceList($vehicleId,$busServiceId,$filter);
	$cnt = count($vehicleServiceRecordArray);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		if($busServiceId == 2) {
			$vehicleServiceRecordArray[$i]['serviceNo'] = NOT_APPLICABLE_STRING;
			$vehicleServiceRecordArray[$i]['serviceDueDate'] = NOT_APPLICABLE_STRING;
			$vehicleServiceRecordArray[$i]['serviceDueKM'] = NOT_APPLICABLE_STRING;
			$vehicleServiceRecordArray[$i]['doneOnDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['doneOnDate']);
		}
		else {
			$vehicleServiceRecordArray[$i]['serviceNo'] = $vehicleServiceRecordArray[$i]['serviceNo'];
			$vehicleServiceRecordArray[$i]['serviceDueDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['serviceDueDate']);
			$vehicleServiceRecordArray[$i]['doneOnDate'] = UtilityManager::formatDate($vehicleServiceRecordArray[$i]['doneOnDate']);
		}
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleServiceRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Vehicle Service Report');
    $reportManager->setReportInformation("SearchBy: Service Type - $busServiceType, From - $serviceFromDate, To - $serviceToDate ");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align="left"', "align='left'");
    $reportTableHead['serviceNo']       =   array('Service No.','width=10% align="left"', 'align="left"');
	$reportTableHead['serviceDueDate']	=	array('Service Due Date','width=15% align="center"', 'align="center"');
	$reportTableHead['serviceDueKM']	=	array('Service Due KM','width="8%" align="right" ', 'align="right"');
    $reportTableHead['doneOnDate']      =   array('Done On Date','width="10%" align="center" ', 'align="center"');
    $reportTableHead['doneOnKM']        =   array('Done On KM','width="5%" align="right" ', 'align="right"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: vehicleServicePrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>