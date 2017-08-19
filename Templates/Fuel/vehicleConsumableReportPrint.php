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

	//$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($foundArray) ) {
		for($i=0; $i<$recordCount; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1)),$foundArray[$i]);
		}
	}
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fuel Consumable Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']				=    array('#',              'width="4%" align="left"', "align='left'");
    $reportTableHead['busNo']				=    array('Vehicle No.',    ' width=10% align="left" ','align="left" ');
	$reportTableHead['totalKm']				=    array('KM Run',         ' width=8% align="right" ','align="right" ');
    $reportTableHead['fuelConsumed']		=    array('Consumption (Ltrs.)',  ' width="10%" align="right" ','align="right"');
	$reportTableHead['amountSpent']			=    array('Amount Spent (Rs.)',  ' width="10%" align="right" ','align="right"');
	$reportTableHead['fuelAvg']				=    array('Average (Km/Ltr)',        ' width="12%" align="right" ','align="right"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>
