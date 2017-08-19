<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Jaineesh
// Created on : (30.06.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelConsumableTimePeriodReport');
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
	$busId = $REQUEST_DATA['busId'];
	//$busArray = explode(',', $busIdList);
	$reportType = $REQUEST_DATA['reportType'];
	$fromDate = $REQUEST_DATA['fromDate'];
	$toDate = $REQUEST_DATA['toDate'];
	$fromDate =	explode('-',$fromDate);
	$fromYear = $fromDate[0];
	$fromMonth = $fromDate[1];
	$fromDay = $fromDate[2];

	$toDate = explode('-',$toDate);
	$toYear = $toDate[0];
	$toMonth = $toDate[1];
	$toDay = $toDate[2];
	$s=0;


	$foundArray = array();

	$newMonthArray = array();

	$newYearArray = array();
	$a = 0;

	if($fromYear < $toYear) {
		while ($fromYear < $toYear) {
			while($fromMonth <= 12) {
				$newMonthArray[] = intval($fromMonth).'-'.$fromYear;
				$fromMonth++;
			}
			$fromYear++;

			$fromMonth = 1;
			while ($fromMonth <= $toMonth) {
				$newMonthArray[] = intval($fromMonth).'-'.$fromYear;
				$fromMonth++;
			}
		}
	}
	else {
		$fromMonth = $fromMonth;
			while ($fromMonth <= $toMonth) {
				$newMonthArray[] = intval($fromMonth).'-'.$fromYear;
				$fromMonth++;
			}
	}


	if($reportType == 1) {
		foreach($newMonthArray as $record) {
			list($fromMonth,$fromYear) = explode('-', $record);
			$s = $fromMonth;
			$fuelMonth = $monArr[$s];
			//if($h != $s) {

			$h = $s;
			if(strlen($s) == 1) {
				$h = '0'.$s;
			}


			$fuelMonth = $monArr[$h].'-'.$fromYear;
			//}

			$daysOfMonth = date("t",mktime(0,0,0,$h,1,$fromYear));

			$fromMonthDate = $fromYear.'-'.$h.'-'.'01';
			$toMonthDate = $fromYear.'-'.$h.'-'.$daysOfMonth;
			$busArray = $fuelManager->getBusName($busId);
			$busNo = $busArray[0]['busNo'];

			$refillCountArray = $fuelManager->countRefillingOnDate($busId, $fromMonthDate);
			$cnt = $refillCountArray[0]['cnt'];
			if ($cnt > 0) {
				$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromMonthDate, $toMonthDate);
			}
			else {
				$getLastRefillArray = $fuelManager->getRefillingDate($busId,$fromMonthDate);
				if(count($getLastRefillArray)>0) {
					 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
					 $foundArray2 = $fuelManager->getAllFuelUsesData($busId, $lastRefillDate, $toMonthDate);
				}
				else {
					$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromMonthDate, $toMonthDate);
				}
			}
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
			if ($cntFound == 1) {
				$totalKm = $foundArray2[$cntFound - 1]['totalKm'];
			}
			$fuelAvg = 0;
			if ($fuelConsumed > 0) {
				$fuelAvg = round($totalKm / $fuelConsumed,2);
			}
			$foundArray[] = array('fuelMonth' => $fuelMonth, 'busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => "$fuelConsumed", 'totalKm' => "$totalKm", 'fuelAvg' => "$fuelAvg",'amountSpent' =>"$amountSpent");
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

		echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
	}

	if($reportType == 2) {
		if($fromYear <= $toYear) {
			$fromDate = $REQUEST_DATA['fromDate'];
			$toDate = $REQUEST_DATA['toDate'];
			$fromYearDate = explode('-',$fromDate);
			$fromYYear = $fromYearDate[0];
			$fromYMonth = $fromYearDate[1];

			$toYearDate = explode('-',$toDate);
			$toYYear = $toYearDate[0];
			$toYMonth = $toYearDate[1];

			if($fromYMonth <= 12) {
				$fromYearlyDate = $fromYYear.'-'.'12-31';

				/*$refillCountArray = $fuelManager->countRefillingOnDate($busId, $fromDate);
				$cnt = $refillCountArray[0]['cnt'];
				if ($cnt > 0) {
					$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $fromYearlyDate);
				}
				else {
					$getLastRefillArray = $fuelManager->getRefillingDate($busId,$fromDate);
					if(count($getLastRefillArray)>0) {
						 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
						 $foundArray2 = $fuelManager->getAllFuelUsesData($busId, $lastRefillDate, $fromYearlyDate);
					}
					else {
						$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $fromYearlyDate);
					}
				}*/

				$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $fromYearlyDate);

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
				//$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
				$totalKm = $foundArray2[0]['totalKm'];
				if ($cntFound == 1) {
					//$totalKm = $foundArray2[$cntFound - 1]['totalKm'];
					$totalKm = $foundArray2[0]['totalKm'];
				}
				$fuelAvg = 0;
				if ($fuelConsumed > 0) {
					$fuelAvg = round($totalKm / $fuelConsumed,2);
				}

				$foundArray[] = array('fuelYear' => $fromYYear, 'busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg,'amountSpent' =>"$amountSpent");
			}


			if($toYMonth <= 12) {


				$toYearlyDate = $toYYear.'-'.'01-01';
				$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $toYearlyDate, $toDate);
				//print_r($foundArray2);
				//die;


				//$refillCountArray = $fuelManager->countRefillingOnDate($busId, $toDate);

				/*
				$cnt = $refillCountArray[0]['cnt'];
				if ($cnt > 0) {
					$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $toYearlyDate, $toDate);
				}
				else {
					$getLastRefillArray = $fuelManager->getRefillingDate($busId,$toDate);
					print_r($getLastRefillArray);
					die;

					if(count($getLastRefillArray)>0) {
						 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
						 $foundArray2 = $fuelManager->getAllFuelUsesData($busId, $lastRefillDate, $toDate);
					}
					else {
						$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $toYearlyDate, $toDate);
					}
				} */
			//}

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
				 $totalKm = $foundArray2[$i]['totalKm'];
				 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
				 $amountSpent +=  $foundArray2[$i]['amount'];
				 $i++;
			}
			//$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
			//$totalKm = $foundArray2[0]['totalKm'];
			/*if ($cntFound == 1) {
				//$totalKm = $foundArray2[$cntFound - 1]['totalKm'];
				$totalKm = $foundArray2[0]['totalKm'];
			}*/
			$fuelAvg = 0;
			if ($fuelConsumed > 0) {
				$fuelAvg = round($totalKm / $fuelConsumed,2);
			}
			$foundArray[] = array('fuelYear' => $toYYear, 'busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg,'amountSpent' =>"$amountSpent");

		}


		/*echo '<pre>';
		print_r($foundArray);
		die;*/


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

			echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
	}

	//echo json_encode('{"info" : ['.$foundArray.']}');
}
}
// $History: $
//

?>