 <?php
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php

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

    $valueArray = array();

    $csvData = '';
	 $csvData .= "Search By, $searchBox \n";
    $csvData .="#,Registration No.,Vehicle Type,Last Insured On,Insurance Due Date, Passenger Tax Valid Till,Road Tax Valid Till,Pollution Check Valid Till,Passing Valid Till ";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
		  $busRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($busRecordArray[$i]['lastInsuranceDate']);
		  $csvData .= ($i+1).",";
		  $csvData .= $busRecordArray[$i]['busNo'].",";
		  $csvData .= $busRecordArray[$i]['vehicleType'].",";
		  $csvData .= $busRecordArray[$i]['lastInsuranceDate'].",";
          $csvData .= $busRecordArray[$i]['insuranceDueDate'].","; 
          $csvData .= $busRecordArray[$i]['passengerTaxValidTill'].","; 
          $csvData .= $busRecordArray[$i]['roadTaxValidTill'].","; 
          $csvData .= $busRecordArray[$i]['pollutionCheckValidTill'].","; 
          $csvData .= $busRecordArray[$i]['passingValidTill'].",";
		 // $csvData .= $busRecordArray[$i]['insuringCompanyName'].",";
		  $csvData .= "\n";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>