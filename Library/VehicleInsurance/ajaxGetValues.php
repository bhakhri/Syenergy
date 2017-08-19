<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE INSURANCE LIST
//
// Author : Jaineesh
// Created on : (26.11.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleInsuranceMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['insuringCompanyId'] ) != '') {
    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $foundArray = VehicleInsuranceManager::getInstance()->getVehicleInsurance(' WHERE insuringCompanyId="'.$REQUEST_DATA['insuringCompanyId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
  }

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Library/VehicleInsurance
//new file for insurance
//
//
?>