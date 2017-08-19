 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
         $filter = ' WHERE (insuringCompanyName  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR insuringCompanyDetails LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'insuringCompanyName';
    
     $orderBy = " $sortField $sortOrderBy";

	$vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceList($filter,'',$orderBy);
    $cnt = count($vehicleInsuranceRecordArray);

	if($cnt >0 && is_array($vehicleInsuranceRecordArray)) {
		for($i=0; $i<$cnt; $i++ ) {
            if($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']=='') {
              $vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']= NOT_APPLICABLE_STRING;
            }
            else {
               $vehicleInsuranceRecordArray[$i]['insuringCompanyDetails']= chunk_split($vehicleInsuranceRecordArray[$i]['insuringCompanyDetails'],80);  
            }
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$vehicleInsuranceRecordArray[$i]);
		}
	}
    $reportManager->setReportInformation("SearchBy :".trim($REQUEST_DATA['searchbox']));
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Insurance Report');
    
    $reportTableHead							=    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']					=    array('#',             ' width="2%" align="left"', "align='left'");
    $reportTableHead['insuringCompanyName']		=    array('Company Name',  ' width=30% align="left" ','align="left" ');
	$reportTableHead['insuringCompanyDetails']	=    array('Detail',        ' width=68% align="left" ','align="left" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
