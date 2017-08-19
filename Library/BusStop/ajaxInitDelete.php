<?php
//-------------------------------------------------------
// Purpose: To delete busstop detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCourse');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['busStopId']) || trim($REQUEST_DATA['busStopId']) == '') {
        $errorMessage = 'Invalid BusStop';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusStopManager.inc.php");
        $busStopManager =  BusStopManager::getInstance();
        //as busstop table is independen no integrity check in done
        if($recordArray[0]['found']==0) {
            if($busStopManager->deleteBusStop($REQUEST_DATA['busStopId']) ) {
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
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BusStop
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:17p
//Updated in $/Leap/Source/Library/BusStop
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:33p
//Updated in $/Leap/Source/Library/BusStop
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:29p
//Updated in $/Leap/Source/Library/BusStop
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:01p
//Created in $/Leap/Source/Library/BusStop
//Initial Checkin
?>

