 <?php 
//This file is used as printing version for vehicle tyre
//
// Author :Jaineesh
// Created on : 03-Jan-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
    $vehicleBatteryManager = VehicleBatteryManager::getInstance();
	
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];
	$vehicleNo = $REQUEST_DATA['vehicleNo'];
	
	$search = $REQUEST_DATA['searchbox'];
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (b.busNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bb.batteryNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bb.batteryMake LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
    $orderBy = " $sortField $sortOrderBy";

	$vehicleBatteryRecordArray = $vehicleBatteryManager->getVehicleBatteryList($filter,'',$orderBy);
	$cnt = count($vehicleBatteryRecordArray);

				
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		if($vehicleBatteryRecordArray[$i]['warrantyDate'] != '' OR $vehicleBatteryRecordArray[$i]['warrantyDate'] != '0000-00-00') {
			$vehicleBatteryRecordArray[$i]['warrantyDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['warrantyDate']);
		}
		if($vehicleBatteryRecordArray[$i]['replacementDate'] != '---' ) {
			$vehicleBatteryRecordArray[$i]['replacementDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['replacementDate']);
		}
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleBatteryRecordArray[$i]);
     }
     
     //print_r($recordArray);
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Battery Report');
    $reportManager->setReportInformation("Search By: $search");

    $reportTableHead						=    array();
    $reportTableHead['srNo']				=    array('#', 'width="4%" align="left"', "align='left'");
    $reportTableHead['busNo']				=    array('Registration No.',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['batteryNo']			=    array('Battery No.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['batteryMake']			=    array('Make',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['warrantyDate']		=    array('Warranty Date',        ' width="12%" align="center" ','align="center"');
	$reportTableHead['replacementDate']		=    array('Replacement Date',        ' width="12%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
