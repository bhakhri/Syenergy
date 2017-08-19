<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelUsageReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busId'] ) != '') {
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
			$foundArray2 = $fuelManager->getAllFuelUsesData($busId, $fromDate, $toDate);
			$cntFound = count($foundArray2);
		    $i = 0;
			$fuelConsumed = 0;
			$totalKm = 0;
			while ($i < $cntFound) {
				 $busId = $foundArray2[$i]['busId'];
				 $busName = $foundArray2[$i]['busName'];
				 $busNo = $foundArray2[$i]['busNo'];
				 $name = $foundArray2[$i]['name'];
				 $staffType = $foundArray2[$i]['staffType'];
				 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
				 $i++;
			}
			$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
			$fuelAvg = round($totalKm / $fuelConsumed,2);
			$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
			if ($fuelAvg < $minAvg) {
				$minAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
				$minAvg = $fuelAvg;

			}
			if ($fuelAvg > $maxAvg) {
				$maxAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
				$maxAvg = $fuelAvg;
			}
		}
		else {
			$getLastRefillArray = $fuelManager->getRefillingDate($busId,$fromDate);
			if(count($getLastRefillArray)>0){
				 $lastRefillDate = $getLastRefillArray[0]['fromDate'];
				 $foundArray2 = $fuelManager->getAllFuelUsesData($busId, $lastRefillDate, $toDate);
				 $i = 0;
				 $cntFound = count($foundArray2);
				 $fuelConsumed = 0;
				 while ($i < $cntFound) {
					 $busId = $foundArray2[$i]['busId'];
					 $busName = $foundArray2[$i]['busName'];
					 $busNo = $foundArray2[$i]['busNo'];
					 $name = $foundArray2[$i]['name'];
					 $staffType = $foundArray2[$i]['staffType'];
					 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
					 $i++;
				 }
				 $totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
				 $fuelAvg = round($totalKm / $fuelConsumed,2);

				 $foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
					if ($fuelAvg < $minAvg) {
						$minAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
						$minAvg = $fuelAvg;
					}
					if ($fuelAvg > $maxAvg) {
						$maxAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
						$maxAvg = $fuelAvg;
					}
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
				while ($i < $cntFound) {
					 $busId = $foundArray2[$i]['busId'];
					 $busName = $foundArray2[$i]['busName'];
					 $busNo = $foundArray2[$i]['busNo'];
					 $name = $foundArray2[$i]['name'];
					 $staffType = $foundArray2[$i]['staffType'];
					 $fuelConsumed += $foundArray2[$i]['fuelConsumed'];
					 $i++;
				}
				$totalKm = $foundArray2[$cntFound - 1]['totalKm'] - $foundArray2[0]['totalKm'];
				$fuelAvg = round($totalKm / $fuelConsumed,2);
				$foundArray[] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
				if ($fuelAvg < $minAvg) {
					$minAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
					$minAvg = $fuelAvg;
				}
				if ($fuelAvg > $maxAvg) {
					$maxAvgArray[0] = array('busId' => $busId, 'busName' => $busName, 'busNo' => $busNo, 'name' => $name, 'staffType' => $staffType, 'fuelConsumed' => $fuelConsumed, 'totalKm' => $totalKm, 'fuelAvg' => $fuelAvg);
					$maxAvg = $fuelAvg;
				}
			}
		}
	}

	$newArray = array();
	foreach ($foundArray as $record) {
		$newArray[] = $record;
	}
	$foundArray = $newArray;


    $busArr=explode(',',$REQUEST_DATA['busId']);
    $busNameArr=explode('~',$REQUEST_DATA['busNames']);
    
    $arr=array();
    $cnt = count($foundArray);
    $cnt1= count($busArr);
    if($cnt>0 and is_array($foundArray)){
		$usageStr =$maxAvgArray[0]['busNo'].'~'.$maxAvgArray[0]['fuelAvg'].'~'.$maxAvgArray[0]['name'].'~'.$transportStaffTypeArr[$maxAvgArray[0]['staffType']];
		$usageStr .='!@@!'.$minAvgArray[0]['busNo'].'~'.$minAvgArray[0]['fuelAvg'].'~'.$minAvgArray[0]['name'].'~'.$transportStaffTypeArr[$minAvgArray[0]['staffType']];
		echo $usageStr;	
    }

    if($getFuelConsumed == 1 AND $getTotalKM == 2 AND $getFuelAverage == 3) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		} 
		$strList .="</series>\n";


		$strList .="<graphs>\n\t<graph gid=\"Fuel consumed (in Ltr.)\" title=\"Fuel consumed (in Ltr.)\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['fuelConsumed']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 

		$strList .="\t<graph gid=\"Total Km\" title=\"Total Km\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['totalKm']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 
		$strList .="\t<graph gid=\"Fuel Average\" title=\"Fuel Average\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' >".$foundArray[$i]['fuelAvg']."</value>";
		} 
		$strList .="\n\t</graph>"; 
		$strList .="\n</graphs>\n</chart>";

		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 0 AND $getTotalKM == 2 AND $getFuelAverage == 3) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		} 
		$strList .="</series>\n";

		$strList .="<graphs>\n\t<graph gid=\"Total Km\" title=\"Total Km\">";
		//$strList .="\t<graph gid=\"Total Km\" title=\"Total Km\">";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['totalKm']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 
		$strList .="\t<graph gid=\"Fuel Average\" title=\"Fuel Average\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' >".$foundArray[$i]['fuelAvg']."</value>";
		} 
		$strList .="\n\t</graph>"; 
		$strList .="\n</graphs>\n</chart>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 0 AND $getTotalKM == 0 AND $getFuelAverage == 3 ) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		} 
		$strList .="</series>\n";

		$strList .="<graphs>\n\t<graph gid=\"Fuel Average\" title=\"Fuel Average\">";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' >".$foundArray[$i]['fuelAvg']."</value>";
		} 
		$strList .="\n\t</graph>";
		 
		$strList .="\n</graphs>\n</chart>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 1 AND $getTotalKM == 0 AND $getFuelAverage == 3) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		} 
		$strList .="</series>\n";


		$strList .="<graphs>\n\t<graph gid=\"Fuel consumed (in Ltr.)\" title=\"Fuel consumed (in Ltr.)\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['fuelConsumed']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 

		$strList .="\t<graph gid=\"Fuel Average\" title=\"Fuel Average\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' >".$foundArray[$i]['fuelAvg']."</value>";
		} 
		$strList .="\n\t</graph>"; 
		$strList .="\n</graphs>\n</chart>";

		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 1 AND $getTotalKM == 0 AND $getFuelAverage == 0) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		}
		$strList .="</series>\n";


		$strList .="<graphs>\n\t<graph gid=\"Fuel consumed (in Ltr.)\" title=\"Fuel consumed (in Ltr.)\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['fuelConsumed']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 

		$strList .="\n</graphs>\n</chart>";

		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	 if($getFuelConsumed == 1 AND $getTotalKM == 2 AND $getFuelAverage == 0) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		} 
		$strList .="</series>\n";


		$strList .="<graphs>\n\t<graph gid=\"Fuel consumed (in Ltr.)\" title=\"Fuel consumed (in Ltr.)\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['fuelConsumed']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 

		$strList .="\t<graph gid=\"Total Km\" title=\"Total Km\">";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['totalKm']."</value>";
		} 
		$strList .="\n\t</graph>\n"; 
		$strList .="\n</graphs>\n</chart>";

		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 0 AND $getTotalKM == 2 AND $getFuelAverage == 0) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\t<value xid='".$foundArray[$i]['busNo']."'>".$foundArray[$i]['busNo']."</value>\n";
		}
		$strList .="</series>\n";

		$strList .="<graphs>\n\t<graph gid=\"Total Km\" title=\"Total Km\">";
		for($i=0;$i<$cnt;$i++) {
			$strList .= "\n\t\t<value xid='".$foundArray[$i]['busNo']."' url='javascript:showData(\"".$foundArray[$i]['busId']."\")'>".$foundArray[$i]['totalKm']."</value>";
		}
		$strList .="\n\t</graph>\n";
		$strList .="\n</graphs>\n</chart>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

	if($getFuelConsumed == 0 AND $getTotalKM == 0 AND $getFuelAverage == 0) {
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n";
		$strList .="\n\t</graph>\n"; 
		$strList .="\n</graphs>\n</chart>";

		
		$xmlFilePath = TEMPLATES_PATH."/Xml/fuelUserData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
	}

}
// $History: ajaxGetFuelUses.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 2/01/10    Time: 7:13p
//Updated in $/Leap/Source/Library/Fuel
//Add new report for insurance due report
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Library/Fuel
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 27/07/09   Time: 19:00
//Updated in $/Leap/Source/Library/Fuel
//Updated fuel usage report by adding "fuel usage average details"
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/15/09    Time: 4:56p
//Updated in $/Leap/Source/Library/Fuel
//Updated fuel usage report replaced busname with bus number
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/Fuel
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:03a
//Updated in $/SnS/Library/Fuel
//Updated with fuel usuage report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 10:54
//Created in $/SnS/Library/Fuel
//Added "Fuel Uses Report" module
?>