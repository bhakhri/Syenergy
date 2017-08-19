<?php 
//This file is used as printing version for payment history.
//
// Author :Jaineesh
// Created on : 28-01-2010
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
	$fuelStaffId = $REQUEST_DATA['fuelStaffId'];
	$fuelFromDate = $REQUEST_DATA['fuelFromDate'];
	$fuelToDate = $REQUEST_DATA['fuelToDate'];

	$filter = "AND dated BETWEEN '$fuelFromDate' AND '$fuelToDate'";
    $vehicleFuelRecordArray = $vehicleManager->getVehicleFuelList($vehicleId,$fuelStaffId,$filter);
    $cnt = count($vehicleFuelRecordArray);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$vehicleFuelRecordArray[$i]['dated'] = UtilityManager::formatDate($vehicleFuelRecordArray[$i]['dated']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleFuelRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Vehicle Fuel Report');
    $reportManager->setReportInformation("SearchBy: From - $fuelFromDate, To - $fuelToDate ");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align="left"', "align='left'");
    $reportTableHead['name']			=   array('Staff Name','width=10% align="left"', 'align="left"');
	$reportTableHead['lastMilege']		=	array('Last Mileage','width=15% align="right"', 'align="right"');
	$reportTableHead['currentMilege']	=	array('Current Mileage','width="8%" align="right" ', 'align="right"');
	$reportTableHead['amount']			=	array('Amount','width="8%" align="right" ', 'align="right"');
	$reportTableHead['litres']			=	array('Litres','width="8%" align="right" ', 'align="right"');
	$reportTableHead['dated']			=	array('Date','width="8%" align="center" ', 'align="center"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: vehicleFuelPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>