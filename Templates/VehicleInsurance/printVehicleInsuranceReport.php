 <?php 
//This file is used as printing version for vehicle Insurance.
//
// Author :Jaineesh
// Created on : 09.06.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	global $FE;
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();
	
	define('MODULE','VehicleInsuranceReport');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(); 

    

	$busId = $REQUEST_DATA['vehicleNo'];
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busName';
    
    $orderBy = "$sortField $sortOrderBy";
	$filter = " AND bi.busId = '".$busId."' AND bs.vehicleTypeId = '".$vehicleTypeId."'";
	$vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceHistory($filter,$limit,$orderBy);

	$recordCount = count($vehicleInsuranceRecordArray);

	$vehicleInsuranceRecordArray[] =  Array();
	
	if($recordCount >0 && is_array($vehicleInsuranceRecordArray) ) { 
		for($i=0; $i<$recordCount; $i++ ) {
			$vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['lastInsuranceDate']);
			$vehicleInsuranceRecordArray[$i]['insuranceDueDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['insuranceDueDate']);
			 $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleInsuranceRecordArray[$i]);
		}
	}
    
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Insurance Report');
	$reportManager->setReportInformation("Search By :".$vehicleInsuranceRecordArray[0]['busNo']);
    
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['insuringCompanyName']	=    array('Insurance Company',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['isActive']			=    array('In service',        ' width="8%" align="left" ','align="left"');
	$reportTableHead['policyNo']			=    array('Policy No.',        ' width="8%" align="left" ','align="left"');
	$reportTableHead['lastInsuranceDate']	=    array('Insurance From',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['insuranceDueDate']	=    array('Insurance To',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['valueInsured']		=    array('Sum Insured',        ' width="10%" align="right" ','align="right"');
	$reportTableHead['insurancePremium']	=    array('Premium',        ' width="10%" align="right" ','align="right"');
	$reportTableHead['ncb']					=    array('NCB',        ' width="10%" align="right" ','align="right"');

   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
