 <?php 
//This file is used as printing version for tyre retreading
//
// Author :Jaineesh
// Created on : 05-Jan-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleTaxManager.inc.php");
    $vehicleTaxManager = VehicleTaxManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " $sortField $sortOrderBy";

	$vehicleTaxRecordArray = $vehicleTaxManager->getVehicleTaxList($filter,$limit,$orderBy);
    $cnt = count($vehicleTaxRecordArray);

				
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$vehicleTaxRecordArray[$i]['busNoValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['busNoValidTill']);
		$vehicleTaxRecordArray[$i]['passengerTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passengerTaxValidTill']);
		$vehicleTaxRecordArray[$i]['roadTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['roadTaxValidTill']);
		$vehicleTaxRecordArray[$i]['pollutionCheckValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['pollutionCheckValidTill']);
		$vehicleTaxRecordArray[$i]['passingValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passingValidTill']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleTaxRecordArray[$i]);
     }
     
     //print_r($recordArray);
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Tax Report');
    $reportManager->setReportInformation("Search By: $search");

    $reportTableHead								=    array();
    $reportTableHead['srNo']						=    array('#', 'width="4%" align="left"', "align='left'");
    $reportTableHead['busNo']						=    array('Registration No.',         ' width=12% align="left" ','align="left" ');
    $reportTableHead['busNoValidTill']				=    array('Registration No. Valid Till',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['passengerTaxValidTill']		=    array('Passenger Tax Valid Till',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['roadTaxValidTill']			=    array('Road Tax Valid Till',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['pollutionCheckValidTill']			=    array('Pollution Tax Valid Till',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['passingValidTill']			=    array('Passing Valid Till',        ' width="12%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
