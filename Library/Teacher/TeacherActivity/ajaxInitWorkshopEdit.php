<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
// Author : Parveen
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true); 
UtilityManager::headerNoCache();
   $errorMessage ='';
   
    if ($errorMessage == '' && (!isset($REQUEST_DATA['topic']) || trim($REQUEST_DATA['topic']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_TOPIC."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['startDate']) || trim($REQUEST_DATA['startDate']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_START_DATE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['endDate']) || trim($REQUEST_DATA['endDate']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_END_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['startDate']) == '0000-00-00') {
        $errorMessage .= ENTER_WORKSHOP_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['endtDate']) == '0000-00-00') {
        $errorMessage .= ENTER_WORKSHOP_END_DATE."\n";    
    }     
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['sponsored']) || trim($REQUEST_DATA['sponsored']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_SPONSORED."\n";    
    }
    
    if ($errorMessage == '' && (trim($REQUEST_DATA['sponsored']) == 'Y')) {
        if ($errorMessage == '' && (!isset($REQUEST_DATA['sponsoredDetail']) || trim($REQUEST_DATA['sponsoredDetail']) == '')) {
            $errorMessage .= ENTER_WORKSHOP_SPONSOREDDETAIL."\n";    
        }
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['location']) || trim($REQUEST_DATA['location']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_LOCATION."\n";    
    }    
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['otherSpeakers']) || trim($REQUEST_DATA['otherSpeakers']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_OTHERSPEAKERS."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['audience']) || trim($REQUEST_DATA['audience']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_AUDIENCE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['attendees']) || trim($REQUEST_DATA['attendees']) == '')) {
        $errorMessage .= ENTER_WORKSHOP_ATTENDEES."\n";    
    }
     
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
        $workshopManager = EmployeeManager::getInstance();

        $returnStatus = $workshopManager->editWorkshop($REQUEST_DATA['workshopId']);
        if($returnStatus === false) {
            echo FAILURE;
        }
        else {
            echo SUCCESS;           
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitWorkshopEdit.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 5:26p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//role permission, alignment, new enhancements added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:42p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:41p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
?>