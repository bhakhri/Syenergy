 <?php 
//This file is used as printing version for display Tyre Retreading
//
// Author :Jaineesh
// Created on : 05-Jan-10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

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

    $csvData ='';
    $csvData="Sr No.,Registration No.,Registration No. Valid Till,Passenger Tax Valid Till,Road Tax Valid Till,Pollution Tax Valid Till,Passing Valid Till";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		$vehicleTaxRecordArray[$i]['busNoValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['busNoValidTill']);
		$vehicleTaxRecordArray[$i]['passengerTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passengerTaxValidTill']);
		$vehicleTaxRecordArray[$i]['roadTaxValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['roadTaxValidTill']);
		$vehicleTaxRecordArray[$i]['pollutionCheckValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['pollutionCheckValidTill']);
		$vehicleTaxRecordArray[$i]['passingValidTill'] = UtilityManager::formatDate($vehicleTaxRecordArray[$i]['passingValidTill']);
		  $csvData .= ($i+1).",";
		  $csvData .= $vehicleTaxRecordArray[$i]['busNo'].",";
		  $csvData .= $vehicleTaxRecordArray[$i]['busNoValidTill'].",";
		  $csvData .= $vehicleTaxRecordArray[$i]['passengerTaxValidTill'].",";
		  $csvData .= $vehicleTaxRecordArray[$i]['roadTaxValidTill'].",";
		  $csvData .= $vehicleTaxRecordArray[$i]['pollutionCheckValidTill'].",";
		  $csvData .= $vehicleTaxRecordArray[$i]['passingValidTill'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleTaxReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>