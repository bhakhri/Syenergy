 <?php 
//This file is used as printing version for vehicle tyre
//
// Author :Jaineesh
// Created on : 03-Jan-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();
	
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];
	$vehicleNo = $REQUEST_DATA['vehicleNo'];
	
	/// Search filter /////  
    $search = $REQUEST_DATA['searchbox'];
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (departmentName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
    $orderBy = " $sortField $sortOrderBy"; 

	 $filter .= "AND b.vehicleTypeId = $vehicleTypeId AND b.busId = $vehicleNo";
    //$totalArray = $vehicleTyreManager->getTotalVehicleTyre($filter);
    $vehicleTyreRecordArray = $vehicleTyreManager->getVehicleTyreList($filter,'',$orderBy);
	$vehicleType = $vehicleTyreRecordArray[0]['vehicleType'];
	$busNo = $vehicleTyreRecordArray[0]['busNo'];

    $cnt = count($vehicleTyreRecordArray);
				
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$vehicleTyreRecordArray[$i]['purchaseDate'] = UtilityManager::formatDate($vehicleTyreRecordArray[$i]['purchaseDate']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$vehicleTyreRecordArray[$i]);
     }
     
     //print_r($recordArray);
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Purchase/Replace Tyre Report');
    $reportManager->setReportInformation("Search By: $vehicleType, Vehicle No. : $busNo");

    $reportTableHead								=    array();
    $reportTableHead['srNo']						=    array('#', 'width="4%" align="left"', "align='left'");
    $reportTableHead['tyreNumber']					=    array('Tyre Number',         ' width=12% align="left" ','align="left" ');
    $reportTableHead['busReadingOnInstallation']	=    array('Reading',        ' width="10%" align="right" ','align="right"');
	$reportTableHead['manufacturer']				=    array('Manufacturer',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['modelNumber']					=    array('Model Number',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['purchaseDate']				=    array('Purchase Date',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['usedAsMainTyre']				=    array('Used as Main/Spare',        ' width="8%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
