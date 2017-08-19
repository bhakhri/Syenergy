<?php
//-------------------------------------------------------
// Purpose: To delete TransportStuff detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStaffMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['staffId']) || trim($REQUEST_DATA['staffId']) == '') {
        $errorMessage = 'Invalid Staff Record';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
        $transportManager =  TransportStaffManager::getInstance();
		$vehicleAccidentStaffArray = $transportManager->getVehicleAccidentStaffDetail($REQUEST_DATA['staffId']);
		if($vehicleAccidentStaffArray[0]['totalRecords'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}
		
		$vehicleRepairStaffArray = $transportManager->getVehicleRepairStaffDetail($REQUEST_DATA['staffId']);
		if($vehicleRepairStaffArray[0]['totalRecords'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

		$vehicleBusRouteStaffArray = $transportManager->getVehicleBusRouteStaffDetail($REQUEST_DATA['staffId']);
		if($vehicleBusRouteStaffArray[0]['totalRecords'] > 0 ) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

        if($transportManager->deleteTransportStaff($REQUEST_DATA['staffId']) ) {
              echo DELETE;
        }
        else {
               echo DEPENDENCY_CONSTRAINT;
        }
     }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Library/TransportStaff
//fixed bug in fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Library/TransportStaff
//add new fields and upload image
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>

