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
	
	$vehicleDetailsArray							=	$vehicleManager->getVehicleDetails($vehicleId);
	$vehicleDetailsArray[0]['purchaseDate']			= UtilityManager::formatDate($vehicleDetailsArray[0]['purchaseDate']);
	$vehicleDetailsArray[0]['chasisPurchaseDate']	= UtilityManager::formatDate($vehicleDetailsArray[0]['chasisPurchaseDate']);
	$vehicleDetailsArray[0]['putOnRoad']			= UtilityManager::formatDate($vehicleDetailsArray[0]['putOnRoad']);
	$busNo = $vehicleDetailsArray[0]['busNo'];

	$valueArray = array();
    /*for($i=0;$i<$cnt;$i++) {
		$vehicleFuelRecordArray[$i]['dated'] = UtilityManager::formatDate($vehicleFuelRecordArray[$i]['dated']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleFuelRecordArray[$i]);
   }*/

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Vehicle Fuel Report');
    $reportManager->setReportInformation("SearchBy: $busNo ");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=	array('#','width="3%" align="left"', "align='left'");
    $reportTableHead['modelNumber']		=   array('Model No.','width=10% align="left"', 'align="left"');
	$reportTableHead['purchaseDate']		=	array('Purchase Date','width=10% align="center"', 'align="center"');
	$reportTableHead['yearOfManufacturing']	=	array('Manufacturing Year','width="8%" align="left" ', 'align="left"');
	$reportTableHead['seatingCapacity']			=	array('Seating Capacity','width="8%" align="right" ', 'align="right"');
	$reportTableHead['fuelCapacity']			=	array('Fuel Capacity','width="8%" align="right" ', 'align="right"');
	$reportTableHead['engineNo']			=	array('Engine No.','width="8%" align="left" ', 'align="left"');
	$reportTableHead['chasisNo']			=	array('Chassis No.','width="10%" align="left" ', 'align="left"');
	$reportTableHead['bodyMaker']			=	array('Body Maker','width="10%" align="left" ', 'align="left"');
	$reportTableHead['chasisCost']			=	array('Chassis Cost','width="10%" align="left" ', 'align="left"');
	$reportTableHead['chasisPurchaseDate']			=	array('Chassis Purchase Date','width="10%" align="center" ', 'align="center"');
	$reportTableHead['bodyCost']			=	array('Body Cost','width="10%" align="center" ', 'align="center"');
	$reportTableHead['putOnRoad']			=	array('Put on Road','width="10%" align="center" ', 'align="center"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $vehicleDetailsArray);
	$reportManager->showReport();

// $History: vehicleDetailPrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>