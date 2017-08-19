<?php
//-------------------------------------------------------
// Purpose: To delete BusRepair detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRepairCourse');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['repairId']) || trim($REQUEST_DATA['repairId']) == '') {
        $errorMessage = 'Invalid Bus Repair Record';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusRepairManager.inc.php");
        $busRepairManager =  BusRepairManager::getInstance();
        if($busRepairManager->deleteBusRepair($REQUEST_DATA['repairId']) ) {
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Library/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/BusRepair
//Updated fleet mgmt file in Leap 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Library/BusRepair
//Created Bus Repair Module
?>

