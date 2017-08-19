 <?php
//This file is used as printing version for display Vehicle Insurance
//
// Author :Jaineesh
// Created on : 09.06.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php

    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();

	$busId = $REQUEST_DATA['vehicleNo'];
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';

    $orderBy = " $sortField $sortOrderBy";

	$filter = " AND bi.busId =".$busId." AND bs.vehicleTypeId =".$vehicleTypeId;
	$vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceHistory($filter,$limit,$orderBy);

	$recordCount = count($vehicleInsuranceRecordArray);

	$valueArray = array();

    $csvData ='';
    $csvData="#,Insurance Company,In service,Policy No.,Insurance From,Insurance To,Sum Insured,Premium,NCB";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		$vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['lastInsuranceDate']);
		$vehicleInsuranceRecordArray[$i]['insuranceDueDate'] = UtilityManager::formatDate($vehicleInsuranceRecordArray[$i]['insuranceDueDate']);
		$csvData .= ($i+1).",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['insuringCompanyName'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['isActive'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['policyNo'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['lastInsuranceDate'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['insuranceDueDate'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['valueInsured'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['insurancePremium'].",";
		$csvData .= $vehicleInsuranceRecordArray[$i]['ncb'].",";
		$csvData .= "\n";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleInsuranceReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>