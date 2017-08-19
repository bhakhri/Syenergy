<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
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
         require_once(MODEL_PATH . "/SeminarsManager.inc.php");
         $seminarManager = SeminarsManager::getInstance();
        

            $returnStatus = $seminarManager->editSeminars($REQUEST_DATA['seminarId']);
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
