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

    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
    $vehicleTyreManager = VehicleTyreManager::getInstance();
    
    $vehicleTypeId = $REQUEST_DATA['vehicleType'];
	$vehicleNo = $REQUEST_DATA['vehicleNo'];

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
    $orderBy = " $sortField $sortOrderBy";

	$filter .= "AND b.vehicleTypeId = $vehicleTypeId AND b.busId = $vehicleNo";
    $vehicleTyreRecordArray = $vehicleTyreManager->getVehicleTyreList($filter,'',$orderBy);
	$vehicleType = $vehicleTyreRecordArray[0]['vehicleType'];
	$busNo = $vehicleTyreRecordArray[0]['busNo'];

    $vehicleTyreRecordArray = $vehicleTyreManager->getVehicleTyreList($filter,'',$orderBy);
    $recordCount = count($vehicleTyreRecordArray);

	$valueArray = array();

    $csvData ='';
	$csvData .='Search By : '.$vehicleType.', Vehicle No. : '.$busNo;
	$csvData .="\n";
    $csvData .= "#,Tyre Number,Reading,Manufacturer,Model Number,Purchase Date,Used as Main/Spare";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
			$vehicleTyreRecordArray[$i]['purchaseDate'] = UtilityManager::formatDate($vehicleTyreRecordArray[$i]['purchaseDate']);
		  $csvData .= ($i+1).",";
		  $csvData .= $vehicleTyreRecordArray[$i]['tyreNumber'].",";
		  $csvData .= $vehicleTyreRecordArray[$i]['busReadingOnInstallation'].",";
		  $csvData .= $vehicleTyreRecordArray[$i]['manufacturer'].",";
		  $csvData .= $vehicleTyreRecordArray[$i]['modelNumber'].",";
		  $csvData .= $vehicleTyreRecordArray[$i]['purchaseDate'].",";
		  $csvData .= $vehicleTyreRecordArray[$i]['usedAsMainTyre'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'Purchase/ReplaceTyreReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>