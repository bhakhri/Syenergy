<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE VEHICLE TYRE LIST
//
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','VehicleServiceRepair');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
		

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();

	require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
	$vehicleServiceRepairManager = VehicleServiceRepairManager::getInstance();

	$busRepairTypeArr;
	$repairCount = count($busRepairTypeArr);
	

	$repairServiceDiv = '<table border = "1" cellspacing="2" width="100%" style="border:1px solid #cccccc;  border-collapse:collapse;" colspan="2">';
	$repairServiceDiv .= '<tr class="rowheading">
					<td align="left" width="5%"><b>Sr.No.</b></td><td align="left" width="15%"><b>Service Description</b></td><td width="10%"><b>Amount</b></td><td width="15%"><b>Next Change in KM</b></td><td width="15%"><b>Inform Before KM</b></td>
				  </tr>';
	$j=1;
	$key = array_keys($busRepairTypeArr);
	for($i=0;$i<$repairCount;$i++) {
		$srNoCounter = $i+1;
		$bg = $bg == "row0" ? "row1" : "row0";
		$repairServiceDiv .= '<tr class="$bg"><td align="left">'.$srNoCounter.'</td>';
		$repairServiceDiv .= '<td width="15%" name="item" id="item" align="left" value="'.$key[$i].'">'.$busRepairTypeArr[$j].'</td>';
		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="6" name="amount_'.$key[$i].'" id="amount_'.$key[$i].'"></td>';
		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="9" name="kmRun_'.$key[$i].'" id="kmRun_'.$key[$i].'"></td>';
		$repairServiceDiv .= '<td width="15%" align="left"><input type="text" class="inputbox1" maxlength="9" name="kmChangeRun_'.$key[$i].'" id="kmRun_'.$key[$i].'"></td>';
		$j++;
	}

	$repairServiceDiv .= '</table>';

	echo($repairServiceDiv);


// $History: $
//
//
?>