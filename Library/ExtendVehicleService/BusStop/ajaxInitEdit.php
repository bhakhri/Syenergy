<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSSTOP
//
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
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['stopName']) || trim($REQUEST_DATA['stopName']) == '') {
        $errorMessage .= ENTER_STOP_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stopAbbr']) || trim($REQUEST_DATA['stopAbbr']) == '')) {
        $errorMessage .= ENTER_STOP_ABBR."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['routeCode']) || trim($REQUEST_DATA['routeCode']) == '')) {
        $errorMessage .= ENTER_ROUTE_CODE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['scheduleTime']) || trim($REQUEST_DATA['scheduleTime']) == '')) {
        $errorMessage .= ENTER_SCHEDULE_TIME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['transportCharges']) || trim($REQUEST_DATA['transportCharges']) == '')) {
        $errorMessage .= ENTER_TRANSPORT_CHARGES."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusStopManager.inc.php");
        $foundArray = BusStopManager::getInstance()->getBusStop(' WHERE ( UCASE(stopAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['stopAbbr'])).'" AND busRouteId='.$REQUEST_DATA['routeCode'].') AND busStopId!='.$REQUEST_DATA['busStopId']);
        if(trim($foundArray[0]['stopAbbr'])=='') {  //DUPLICATE CHECK
            $returnStatus = BusStopManager::getInstance()->editBusStop($REQUEST_DATA['busStopId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo STOP_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/07/09   Time: 16:41
//Updated in $/LeapCC/Library/BusStop
//Make combination of  busstop name and route code unique
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BusStop
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:17p
//Updated in $/Leap/Source/Library/BusStop
//Added access rules
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/24/08    Time: 10:21a
//Updated in $/Leap/Source/Library/BusStop
//Added functionilty for busRouteId in bus stop master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/20/08    Time: 3:14p
//Updated in $/Leap/Source/Library/BusStop
//Added Standard Messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/05/08    Time: 1:00p
//Updated in $/Leap/Source/Library/BusStop
//Modifies" instituId"  insertion so that it comes from session variable
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
