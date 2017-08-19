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

    require_once(MODEL_PATH . "/VehicleManager.inc.php");
    $vehicleManager = VehicleManager::getInstance();

    $conditions = ''; 
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	  $searchBox = add_slashes(trim($REQUEST_DATA['searchbox']));
        $filter = " AND bs.busNo LIKE '$searchBox%' OR DATE_FORMAT(bi.insuranceDueDate,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%'  OR DATE_FORMAT(bi.lastInsuranceDate,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pvno.passengerTaxValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR 
	  DATE_FORMAT(rtno.roadTaxValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pcno.pollutionCheckValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR DATE_FORMAT(pano.passingValidTill,'%d-%b-%y') LIKE '%".add_slashes(trim($REQUEST_DATA['searchbox']))."%' OR vt.vehicleType LIKE '$searchBox%'";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";

	$busRecordArray = $vehicleManager->getVehicleList($filter,'',$orderBy);
    $cnt = count($busRecordArray);
                            
	$designationPrintArray[] =  Array();
	if($cnt >0 && is_array($busRecordArray) ) { 

	for($i=0; $i<$cnt; $i++ ) {
		
		$bg = $bg =='row0' ? 'row1' : 'row0';
	   $busRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($busRecordArray[$i]['lastInsuranceDate']);
	   $valueArray[] = array_merge(array('srNo' => ($i+1) ),$busRecordArray[$i]);

	}
	}
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Report');
	$reportManager->setReportInformation("SearchBy: $searchBox");
    
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['busNo']		=    array('Registration No.',  ' width=15% align="left" ','align="left" ');
    $reportTableHead['vehicleType']		=    array('Vehicle Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['lastInsuranceDate']		=    array('Last Insured on',        ' width="10%" align="center" ','align="center"');
    $reportTableHead['insuranceDueDate']        =    array('Insurance Due Date',        ' width="10%" align="center" ','align="center"'); 
    $reportTableHead['passengerTaxValidTill']   =    array('Passenger Tax Valid Till',        ' width="10%" align="center" ','align="center"'); 
    $reportTableHead['roadTaxValidTill']        =    array('Road Tax Valid Till',        ' width="10%" align="center" ','align="center"'); 
    $reportTableHead['pollutionCheckValidTill']  =    array('Pollution Check Valid Till',        ' width="10%" align="center" ','align="center"');
    $reportTableHead['passingValidTill']  =    array('Passing Valid Till',        ' width="10%" align="center" ','align="center"'); 
//	$reportTableHead['insuringCompanyName']		=    array('First Insurance Company',        ' width="20%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
