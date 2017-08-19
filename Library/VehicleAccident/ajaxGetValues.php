<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE INSURANCE LIST
//
// Author : Jaineesh
// Created on : (26.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleAccident');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['accidentId'] ) != '') {
    require_once(MODEL_PATH . "/VehicleAccidentManager.inc.php");
	$vehicleAccidentManager = VehicleAccidentManager::getInstance();
    $foundArray = $vehicleAccidentManager->getVehicleAccident(' AND ba.accidentId="'.$REQUEST_DATA['accidentId'].'"');

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
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/VehicleAccident
//fixed bug in fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:03p
//Created in $/Leap/Source/Library/VehicleAccident
//new ajax files for add, edit & delete for vehicle accident
//
//
?>