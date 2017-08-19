<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Jaineesh
// Created on : (24.06.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelConsumableReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['busId'] ) != '') {

	/*$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;*/

    require_once(MODEL_PATH . "/FuelManager.inc.php");
	$fuelManager = FuelManager::getInstance();
    $strList ="";
	$busIdList = $REQUEST_DATA['busId'];
	$busArray = explode(',', $busIdList);
	$fromDate = $REQUEST_DATA['fromDate'];
	$toDate = $REQUEST_DATA['toDate'];
	$foundArray = array();
	$minAvg = 10000;
	$maxAvg = 0;
	$minAvgArray = array();
	$maxAvgArray = array();
	$getFuelConsumed = $REQUEST_DATA['fuelConsumed'];
	$getTotalKM = $REQUEST_DATA['totalKM'];
	$getFuelAverage = $REQUEST_DATA['fuelAverage'];

	foreach ($busArray as $busId) {
		$refillCountArray = $fuelManager->countRefillingOnDate($busId, $fromDate);
		$cnt = $refillCountArray[0]['cnt'];
		if ($cnt > 0) {
			//$foundCountArray2 = $fuelManager->getCountFuelUsesData($busId, $fromDate, $toDate);
			//$count = count($foundCountArray2);

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
			$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => "$fuelConsumed", 'totalKm' => "$totalKm", 'fuelAvg' => "$fuelAvg",'amountSpent' =>"$amountSpent");
		}
		else {
			$getLastRefillArray = $fuelManager->getRefillingDate($busId,$fromDate);
			if(count($getLastRefillArray)>0){
				 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
				 //$foundCountArray2 = $fuelManager->getCountFuelUsesData($busId, $lastRefillDate, $toDate);
				 //$count = count($foundCountArray2);

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

				 $foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => "$fuelConsumed", 'totalKm' => "$totalKm", 'fuelAvg' => "$fuelAvg",'amountSpent' =>"$amountSpent");
			}
			else{
				//$foundCountArray2 = $fuelManager->getCountFuelUsesData($busId, $lastRefillDate, $toDate);
				//$count = count($foundCountArray2);
				//this function is used to get all fuel data
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
				$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => "$fuelConsumed", 'totalKm' => "$totalKm", 'fuelAvg' => "$fuelAvg",'amountSpent' =>"$amountSpent");
			}
		}
	}

	$newArray = array();
	foreach ($foundArray as $record) {
		$newArray[] = $record;
	}
	$foundArray = $newArray;
	$count = count($foundArray);

	for($i=0;$i<$count;$i++) {
		$valueArray = array_merge(array('srNo' => ($i+1)),$foundArray[$i]);

		if(trim($json_val)=='') {
			$json_val = json_encode($valueArray);
		}
		else {
			$json_val .= ','.json_encode($valueArray);
		}
	}

	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';

	//echo json_encode('{"info" : ['.$foundArray.']}');

}
// $History: $
//

?>