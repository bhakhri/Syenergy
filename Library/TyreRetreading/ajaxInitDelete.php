<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['retreadingId']) || trim($REQUEST_DATA['retreadingId']) == '') {
        $errorMessage = INVALID_TYRE_RETREADING;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
		$tyreRetreadingManager = TyreRetreadingManager::getInstance();

            if($tyreRetreadingManager->deleteTyreRetreading($REQUEST_DATA['retreadingId']) ) {
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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
?>

