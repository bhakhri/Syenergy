<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A PUBLISHING
//
//
// Author : Parveen
// Created on : (05.03.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
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
	 
    $employeeId =  add_slashes($REQUEST_DATA['employeeId']);
     
    if (trim($errorMessage) == '') {
          require_once(MODEL_PATH . "/EmployeeManager.inc.php");
          $workshopManager = EmployeeManager::getInstance();
		
           $returnStatus = $workshopManager->addWorkshop($employeeId);
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
// $History:
//
 
?>