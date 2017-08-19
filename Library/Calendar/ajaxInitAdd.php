<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD An event
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['eventTitle']) || trim($REQUEST_DATA['eventTitle']) == '') {
        $errorMessage .= ENTER_EVENT_TITLE."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['shortDescription']) || trim($REQUEST_DATA['shortDescription']) == '')) {
        $errorMessage .= ENTER_SHORT_DESC."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['longDescription']) || trim($REQUEST_DATA['longDescription']) == '')) {
        $errorMessage .= ENTER_LONG_DESC."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roleIds']) || trim($REQUEST_DATA['roleIds']) == '')) {
        $errorMessage .= SELECT_ROLE."\n";   
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CalendarManager.inc.php");
        $foundArray = CalendarManager::getInstance()->getEvent(' AND UCASE(eventTitle)="'.add_slashes(strtoupper($REQUEST_DATA['eventTitle'])).'" AND startDate="'.add_slashes($REQUEST_DATA['startDate']).'"');
        if(trim($foundArray[0]['eventTitle'])=='') {  //DUPLICATE CHECK
            $returnStatus = CalendarManager::getInstance()->addEvent();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo EVENT_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Library/Calendar
//access permission updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Calendar
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:24p
//Updated in $/Leap/Source/Library/Calendar
//Added access rules
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Calendar
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 12:09p
//Updated in $/Leap/Source/Library/Calendar
//Added Standard Messages
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/05/08    Time: 5:15p
//Updated in $/Leap/Source/Library/Calendar
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/05/08    Time: 12:28p
//Updated in $/Leap/Source/Library/Calendar
//Added SessionId in the code 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Calendar
//Created Calendar(event) module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:34p
//Created in $/Leap/Source/Library/Calendar
//Initial Checkin
?>