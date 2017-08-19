<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Jaineesh
// Created on : (16.12.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

require_once(MODEL_PATH . "/ExtendVehicleServiceManager.inc.php");
$extendVehicleServiceManager = ExtendVehicleServiceManager::getInstance();

if(trim($REQUEST_DATA['extendedServiceValue'] ) != '') {
	$extendedServiceValue = $REQUEST_DATA['extendedServiceValue'];
	$busId = $REQUEST_DATA['busId'];
	$vehicleServiceArray = $extendVehicleServiceManager->getVehicleFreeService($busId);
	 $totalService = $vehicleServiceArray[0]['countRecords'];
	$x = $totalService;
	if($x > 0 ) {
		if ($extendedServiceValue > 0 ) {
			$serviceDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			$serviceDiv .= '<tr><td width="10%"></td><td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Due Date</b></td><td width="15%">&nbsp;&nbsp;&nbsp;&nbsp;<b>KM Run</b></td></tr>';
			for($i=0;$i<$extendedServiceValue;$i++) {
				 $x = $x+1;
				 $serviceDiv .= '<tr><td width="20%" class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Free service no.'.$x.'</b></td>
								 <td width="10%">&nbsp;&nbsp;<b>:&nbsp;</b>'.$htmlFunctions->datePicker2('serviceDate_'.$x,date('Y-m-d')).'</td>';
				 $serviceDiv .= '<td width="15%">&nbsp;<input class="inputbox" type="text" maxlength = "9" name="kmRun_'.$x.'" /></td></tr>';
			 }
				$serviceDiv .= '</table>';
			}
	}

	echo ($serviceDiv);
    
}
// $History: getVehicleService.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:52p
//Created in $/Leap/Source/Library/ExtendVehicleService
//new files to add extend vehicle service
//
//
?>