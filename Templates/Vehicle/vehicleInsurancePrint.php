<?php 
//This file is used as printing version for payment history.
//
// Author :Jaineesh
// Created on : 28-01-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	$insuranceFromDate = $REQUEST_DATA['InsuranceFromDate'];
	$insuranceToDate = $REQUEST_DATA['InsuranceToDate'];

	$filter = "AND bi.lastInsuranceDate BETWEEN '$insuranceFromDate' AND '$insuranceToDate'";
    $vehicleInsuranceRecordArray = $vehicleManager->getVehicleInsuranceList($vehicleId,$filter);
	//print_r($vehicleInsuranceRecordArray);
    $cnt = count($vehicleInsuranceRecordArray);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['lastInsuranceDate']);
		$vehicleInsuranceRecordArray[$i]['insuranceDueDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['insuranceDueDate']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleInsuranceRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Vehicle Insurance Report');
    $reportManager->setReportInformation("SearchBy: From - $insuranceFromDate, To - $insuranceToDate ");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', "align='left'");
    $reportTableHead['lastInsuranceDate']	=   array('Ins. Date','width=8% align="center"', 'align="center"');
	$reportTableHead['insuranceDueDate']	=	array('Ins. Due Date','width=8% align="center"', 'align="center"');
	$reportTableHead['insuringCompanyName']	=	array('Ins. Comp.','width="12%" align="left" ', 'align="left"');
	$reportTableHead['policyNo']			=	array('Policy No.','width="10%" align="left" ', 'align="left"');
	$reportTableHead['valueInsured']		=	array('Value Insured','width="10%" align="right" ', 'align="right"');
	$reportTableHead['insurancePremium']	=	array('Ins. Premium','width="10%" align="right" ', 'align="right"');
	$reportTableHead['ncb']					=	array('NCB','width="10%" align="right" ', 'align="right"');
	$reportTableHead['branchName']			=	array('Branch Name','width="15%" align="left" ', 'align="left"');
	$reportTableHead['agentName']			=	array('Agent Name','width="10%" align="left" ', 'align="left"');

    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: vehicleInsurancePrint.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:54p
//Created in $/Leap/Source/Templates/Vehicle
//new print files for vehicle detail
//
//
?>