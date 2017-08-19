<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A DEGREE
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
define('MODULE','VehicleTypeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['vehicleType']) || trim($REQUEST_DATA['vehicleType']) == '') {
        $errorMessage .= ENTER_VEHICLE_TYPE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mainTyres']) || trim($REQUEST_DATA['mainTyres']) == '')) {
        $errorMessage .= ENTER_MAIN_TYRE."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['spareTyres']) || trim($REQUEST_DATA['spareTyres']) == '')) {
        $errorMessage .= ENTER_SPARE_TYRE."\n";
    } 

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
			$foundArray1 = VehicleTypeManager::getInstance()->getVehicleType('WHERE  LCASE(vehicleType)="'.add_slashes(trim(strtolower($REQUEST_DATA['vehicleType']))).'" AND vehicleTypeId!='.$REQUEST_DATA['vehicleTypeId']);
			if(trim($foundArray1[0]['vehicleType'])=='') {  //DUPLICATE CHECK
            $returnStatus = VehicleTypeManager::getInstance()->editVehicleType($REQUEST_DATA['vehicleTypeId']);
			if($returnStatus === false) {
                echo FAILURE;
                die;
            }
            else {
                echo SUCCESS;
                die;
            }
         }
		else {
			if(trim(strtolower($foundArray1[0]['vehicleType']))==trim(strtolower($REQUEST_DATA['vehicleType']))) {
             echo VEHICLE_TYPE_ALREADY_EXIST;
             die;
            }
		}
	}
else {
	echo $errorMessage;
}

// $History: ajaxInitEdit.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/VehicleType
//fixed bug in fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/24/09   Time: 12:01p
//Updated in $/Leap/Source/Library/VehicleType
//fixed bug nos. 0002354, 0002353, 0002351, 0002352, 0002350, 0002347,
//0002348, 0002355, 0002349
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
?>
