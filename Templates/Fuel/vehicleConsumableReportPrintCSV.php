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

    require_once(MODEL_PATH . "/FuelManager.inc.php");
	$fuelManager = FuelManager::getInstance();

    $busIdList = $REQUEST_DATA['busId'];
	$busArray = explode(',', $busIdList);
	$fromDate = $REQUEST_DATA['fromDate'];
	$toDate = $REQUEST_DATA['toDate'];
	$foundArray = array();

	foreach ($busArray as $busId) {
		$refillCountArray = $fuelManager->countRefillingOnDate($busId, $fromDate);
		$cnt = $refillCountArray[0]['cnt'];
		if ($cnt > 0) {
			$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $toDate);
			$cntFound = count($foundArray2);
		    $i = 0;
			$fuelConsumed = 0;
			$totalKm = 0;
			$amountSpent = 0;
			while ($i < $cntFound) {
				 $busId = $foundArray2[$i]['busId'];
				 $busName = $foundArray2[$i]['busName'];
				 $busNo = $foundArray2[$i]['busNo'];
				 $name = $foundArray2[$i]['name'];
				 $staffType = $foundArray2[$i]['staffType'];
				 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
				 $amountSpent +=  $foundArray2[$i]['amount'];
				 $i++;
			}
			$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
			$fuelAvg = round($totalKm / $fuelConsumed,2);
			$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg,'amountSpent' =>"$amountSpent");
		}
		else {
			$getLastRefillArray = $fuelManager->getRefillingDate($busId,$fromDate);
			if(count($getLastRefillArray)>0){
				 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
				 $foundArray2 = $fuelManager->getAllFuelUsesData($busId, $lastRefillDate, $toDate);
				 $i = 0;
				 $cntFound = count($foundArray2);
				 $fuelConsumed = 0;
				 $amountSpent = 0;
				 while ($i < $cntFound) {
					 $busId = $foundArray2[$i]['busId'];
					 $busName = $foundArray2[$i]['busName'];
					 $busNo = $foundArray2[$i]['busNo'];
					 $name = $foundArray2[$i]['name'];
					 $staffType = $foundArray2[$i]['staffType'];
					 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
					 $amountSpent +=  $foundArray2[$i]['amount'];
					 $i++;
				 }
				 $totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
				 $fuelAvg = round($totalKm / $fuelConsumed,2);

				 $foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg,'amountSpent' =>"$amountSpent");
			}
			else{
				$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $toDate);
				$cntFound = count($foundArray2);
				if ($cntFound == 0) {
					continue;
				}
				$i = 0;
				$fuelConsumed = 0;
				$totalKm = 0;
				$amountSpent = 0;
				while ($i < $cntFound) {
					 $busId = $foundArray2[$i]['busId'];
					 $busName = $foundArray2[$i]['busName'];
					 $busNo = $foundArray2[$i]['busNo'];
					 $name = $foundArray2[$i]['name'];
					 $staffType = $foundArray2[$i]['staffType'];
					 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
					 $amountSpent +=  $foundArray2[$i]['amount'];
					 $i++;
				}
				$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
				$fuelAvg = round($totalKm / $fuelConsumed,2);
				$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg,'amountSpent' =>"$amountSpent");
			}
		}
	}

	$newArray = array();
	foreach ($foundArray as $record) {
		$newArray[] = $record;
	}
	$foundArray = $newArray;
	$recordCount = count($foundArray);

    $valueArray = array();

    $csvData ='';
    $csvData="#,Vehicle No.,KM Run,Consumption (Ltrs.),Amount Spent (Rs.),Average (Km/Ltr)";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $foundArray[$i]['busNo'].",";
		  $csvData .= $foundArray[$i]['totalKm'].",";
		  $csvData .= $foundArray[$i]['fuelConsumed'].",";
		  $csvData .= $foundArray[$i]['amountSpent'].",";
		  $csvData .= $foundArray[$i]['fuelAvg'].",";
		  $csvData .= "\n";
  }
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'VehicleConsumableReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>