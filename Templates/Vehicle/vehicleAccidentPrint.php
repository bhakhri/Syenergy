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
	$accidentStaffId = $REQUEST_DATA['accidentStaffId'];
	$accidentFromDate= $REQUEST_DATA['accidentFromDate'];
	$accidentToDate = $REQUEST_DATA['accidentToDate'];

	$filter = "HAVING accidentDate BETWEEN '$accidentFromDate' AND '$accidentToDate'";
    $vehicleAccidentRecordArray = $vehicleManager->getVehicleAccidentList($vehicleId,$accidentStaffId,$filter);
    $cnt = count($vehicleAccidentRecordArray);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$vehicleAccidentRecordArray[$i]['accidentDate'] = UtilityManager::formatDate($vehicleAccidentRecordArray[$i]['accidentDate']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleAccidentRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Vehicle Accident Report');
    $reportManager->setReportInformation("SearchBy: From - $accidentFromDate, To - $accidentToDate ");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align="left"', "align='left'");
    $reportTableHead['name']			=   array('Staff Name','width=10% align="left"', 'align="left"');
	$reportTableHead['routeName']		=	array('Route Name','width=15% align="left"', 'align="left"');
	$reportTableHead['accidentDate']	=	array('Date','width="8%" align="center" ', 'align="center"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: vehicleAccidentPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>