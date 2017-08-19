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
define('MODULE','VehicleTyreMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['tyreId'] ) != '') {
    require_once(MODEL_PATH . "/VehicleTyreManager.inc.php");
	$vehicleTyreManager = VehicleTyreManager::getInstance();
    $foundArray = $vehicleTyreManager->getVehicleTyre(' AND tm.tyreId="'.$REQUEST_DATA['tyreId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Library/VehicleTyre
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:31p
//Created in $/Leap/Source/Library/VehicleTyre
//new ajax files for vehicle tyre
//
//
?>