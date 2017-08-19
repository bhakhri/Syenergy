<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   
if(trim($REQUEST_DATA['busId']) != '' && trim($REQUEST_DATA['serviceType']) != '') {
    require_once(MODEL_PATH . "/VehicleServiceRepairManager.inc.php");
    $foundArray = VehicleServiceRepairManager::getInstance()->getVehicleFreeService(' AND bs.serviceType="'.$REQUEST_DATA['serviceType'].'" AND b.busId="'.$REQUEST_DATA['busId'].'"');
	
    if(is_array($foundArray) && count($foundArray)>0 ) {
		echo json_encode($foundArray);
    }
	else {
		echo 0;
	}
}
// $History: getVehicleService.php $ 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/VehicleService
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:04p
//Created in $/Leap/Source/Library/VehicleService
//new ajax files for vehicle service
//
?>