<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE HOSTEL VISITOR LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (20.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleServiceRepair');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['serviceRepairId'] ) != '') {

    require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
	$vehicleServiceRepairManager = VehicleServiceRepairManager::getInstance();
	$mainArray = array();

	$vehicleServiceRepairArray = $vehicleServiceRepairManager->getVehicleServiceRepairValues(" AND vsr.serviceRepairId = ".$REQUEST_DATA['serviceRepairId']);

	$mainArray['vehicleServiceDetails'][0]['vehicleTypeId']	=	strip_slashes($vehicleServiceRepairArray[0]['vehicleTypeId']);
	$mainArray['vehicleServiceDetails'][0]['busId']	=	strip_slashes($vehicleServiceRepairArray[0]['busId']);
	$mainArray['vehicleServiceDetails'][0]['serviceType']	=	strip_slashes($vehicleServiceRepairArray[0]['serviceType']);
	$mainArray['vehicleServiceDetails'][0]['serviceDate']	=	strip_slashes($vehicleServiceRepairArray[0]['serviceDate']);
	$mainArray['vehicleServiceDetails'][0]['kmReading']	=	strip_slashes($vehicleServiceRepairArray[0]['kmReading']);
	$mainArray['vehicleServiceDetails'][0]['billNo']	=	strip_slashes($vehicleServiceRepairArray[0]['billNo']);
	$mainArray['vehicleServiceDetails'][0]['servicedAt']	=	strip_slashes($vehicleServiceRepairArray[0]['servicedAt']);
	$mainArray['vehicleServiceDetails'][0]['serviceRepairId']	=	strip_slashes($vehicleServiceRepairArray[0]['serviceRepairId']);
	$mainArray['vehicleServiceDetails'][0]['serviceNo']	=	strip_slashes($vehicleServiceRepairArray[0]['serviceNo']);

	$vehicleServiceOilArr = $vehicleServiceRepairManager->getVehicleServiceOilValues(" WHERE vso.serviceRepairId = ".$REQUEST_DATA['serviceRepairId']);


	$busRepairTypeArr;
	$repairCount = count($busRepairTypeArr);


	$repairServiceDiv = '<table border = "1" cellspacing="2" width="100%" style="border:1px solid #cccccc;border-collapse:collapse;" colspan="2">';
	$repairServiceDiv .= '<tr class="rowheading">
					<td align="left" width="5%"><b>Sr.No.</b></td><td align="left" width="15%"><b>Service Description</b></td><td width="10%"><b>Amount</b></td><td width="15%"><b>Next Change in KM</b></td><td width="15%"><b>Inform Before KM</b></td>
				  </tr>';
	$j=1;
	$key = array_keys($busRepairTypeArr);

	$newArray = array();
	foreach ($vehicleServiceOilArr as $record) {
		$newArray[$record['actionId']]['amount'] = $record['amount'];
		$newArray[$record['actionId']]['kmRun'] = $record['kmRun'];
		$newArray[$record['actionId']]['changeKM'] = $record['changeKM'];
	}

	for($i=0;$i<$repairCount;$i++) {
		//if($key[$i] == $vehicleServiceOilArr[$i]['actionId']) {
		$srNoCounter = $i+1;
		$bg = $bg == "row0" ? "row1" : "row0";
		$repairServiceDiv .= '<tr class="$bg"><td align="left">'.$srNoCounter.'</td>';
		$repairServiceDiv .= '<td width="15%" name="item" id="item" align="left" value="'.$key[$i].'">'.$busRepairTypeArr[$j].'</td>';

		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="6" name="amount_'.$key[$i].'" id="amount_'.$key[$i].'" value="'.$newArray[$srNoCounter]['amount'].'"></td>';
		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="9" name="kmRun_'.$key[$i].'" id="kmRun_'.$key[$i].'" value="'.$newArray[$srNoCounter]['kmRun'].'"></td>';
		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="9" name="kmChangeRun_'.$key[$i].'" id="kmRun_'.$key[$i].'" value = "'.$newArray[$srNoCounter]['changeKM'].'"></td>';
		//}
		$j++;
	}

	$repairServiceDiv .= '</table>';
	$mainArray['repairServiceDiv']		=	$repairServiceDiv;

	$vehicleServiceRepairDetailArr = $vehicleServiceRepairManager->getVehicleServiceRepairDetailValues(" WHERE vsrd.serviceRepairId = ".$REQUEST_DATA['serviceRepairId']);

	/*$mainArray['vehicleServiceRepairDetail'][0]['type'] = strip_slashes($vehicleServiceRepairDetailArr[0]['type']);
	$mainArray['vehicleServiceRepairDetail'][0]['item'] = strip_slashes($vehicleServiceRepairDetailArr[0]['item']);
	$mainArray['vehicleServiceRepairDetail'][0]['amount'] = strip_slashes($vehicleServiceRepairDetailArr[0]['amount']);
	*/
	$mainArray['vehicleServiceRepairDetail'] = $vehicleServiceRepairDetailArr;

	echo json_encode($mainArray);
}
?>

