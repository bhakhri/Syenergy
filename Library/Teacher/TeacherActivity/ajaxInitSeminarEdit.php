<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
// Author : Jaineesh
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
   
   if ($errorMessage == '' && (!isset($REQUEST_DATA['seminarOrganisedBy']) || trim($REQUEST_DATA['seminarOrganisedBy']) == '')) {
        $errorMessage .= ENTER_SEMINAR_ORGANISEDBY."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['seminarTopic']) || trim($REQUEST_DATA['seminarTopic']) == '')) {
        $errorMessage .= ENTER_SEMINAR_TOPIC."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['seminarDescription']) || trim($REQUEST_DATA['seminarDescription']) == '')) {
        $errorMessage .= ENTER_SEMINAR_DESCRIPTION."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['seminarPlace']) || trim($REQUEST_DATA['seminarPlace']) == '')) {
        $errorMessage .= ENTER_SEMINAR_PLACE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['startDate']) || trim($REQUEST_DATA['startDate']) == '')) {
        $errorMessage .= ENTER_SEMINAR_START_DATE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['endDate']) || trim($REQUEST_DATA['endDate']) == '')) {
        $errorMessage .= ENTER_SEMINAR_END_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['startDate']) == '0000-00-00') {
        $errorMessage .= ENTER_SEMINAR_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['startDate']) == '0000-00-00') {
        $errorMessage .= ENTER_SEMINAR_END_DATE."\n";    
    }
    
    if (trim($errorMessage) == '') {
         require_once(MODEL_PATH . "/EmployeeManager.inc.php");
         $seminarManager = EmployeeManager::getInstance();
        

            $returnStatus = $seminarManager->editSeminars(add_slashes($REQUEST_DATA['seminarId']));
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
// $History: ajaxInitSeminarEdit.php $
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
//User: Parveen      Date: 7/13/09    Time: 3:40p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Seminar
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Seminar
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Seminar
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Seminar
//initial checkin 
//
//
 
?>
