 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

    require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
    $vehicleBatteryManager = VehicleBatteryManager::getInstance();
    
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

    $csvData ='';
    $csvData="Sr No.,Registration No.,Battery No.,Make,Warranty Date,Replacement Date";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {
		if($vehicleBatteryRecordArray[$i]['warrantyDate'] != '' OR $vehicleBatteryRecordArray[$i]['warrantyDate'] != '0000-00-00') {
			$vehicleBatteryRecordArray[$i]['warrantyDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['warrantyDate']);
		}
		if($vehicleBatteryRecordArray[$i]['replacementDate'] != '---' ) {
			$vehicleBatteryRecordArray[$i]['replacementDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['replacementDate']);
		}
		  $csvData .= ($i+1).",";
		  $csvData .= $vehicleBatteryRecordArray[$i]['busNo'].",";
		  $csvData .= $vehicleBatteryRecordArray[$i]['batteryNo'].",";
		  $csvData .= $vehicleBatteryRecordArray[$i]['batteryMake'].",";
		  $csvData .= $vehicleBatteryRecordArray[$i]['warrantyDate'].",";
		  $csvData .= $vehicleBatteryRecordArray[$i]['replacementDate'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleBatteryReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>