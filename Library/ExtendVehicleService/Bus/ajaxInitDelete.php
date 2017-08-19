<?php
//-------------------------------------------------------
// Purpose: To delete Bus detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusCourse');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['busId']) || trim($REQUEST_DATA['busId']) == '') {
        $errorMessage = 'Invalid Bus';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusManager.inc.php");
        $busStopManager =  BusManager::getInstance();
        //as busstop table is independen no integrity check in done
        if($recordArray[0]['found']==0) {
            if($busStopManager->deleteBus($REQUEST_DATA['busId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
 
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
//User: Dipanjan     Date: 1/04/09    Time: 15:37
//Created in $/LeapCC/Library/Bus
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/Bus
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Library/Bus
//Created Bus Master Module
?>

