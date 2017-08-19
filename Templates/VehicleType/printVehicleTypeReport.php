 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
    $vehicleTypeManager = VehicleTypeManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
         $filter = ' WHERE (vehicleType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR mainTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR spareTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'vehicleType';
    
     $orderBy = " $sortField $sortOrderBy";

	$vehicleTypeRecordArray = $vehicleTypeManager->getVehicleTypeList($filter,$limit,$orderBy);
    $cnt = count($vehicleTypeRecordArray);

	if($cnt >0 && is_array($vehicleTypeRecordArray)) {
		for($i=0; $i<$cnt; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$vehicleTypeRecordArray[$i]);
		}
	}
    $reportManager->setReportInformation("SearchBy :".trim($REQUEST_DATA['searchbox']));
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Type Report');
    
    $reportTableHead							=    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']					=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['vehicleType']				=    array('Vehicle Type',         ' width=20% align="left" ','align="left" ');
	$reportTableHead['mainTyres']				=    array('Main Tyres',         ' width=10% align="right" ','align="right" ');
	$reportTableHead['spareTyres']				=    array('Spare Tyres',         ' width=10% align="right" ','align="right" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
