<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Vehicle');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busId'] ) != '') {
	$busId = $REQUEST_DATA['busId'];
    require_once(MODEL_PATH . "/VehicleTaxManager.inc.php");
	$foundArray = VehicleTaxManager::getInstance()->getVehicleTax($busId);
	echo json_encode($foundArray);
}
// $History: getVehicleTaxDetail.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:21p
//Created in $/Leap/Source/Library/VehicleTax
//new ajax files for vehicle tax
//
//
?>