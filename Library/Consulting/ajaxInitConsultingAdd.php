<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A PUBLISHING
//
//
// Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

   if ($errorMessage == '' && (!isset($REQUEST_DATA['projectName']) || trim($REQUEST_DATA['projectName']) == '')) {
        $errorMessage .= ENTER_COUNSULTING_PROJECTNAME."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['sponsorName']) || trim($REQUEST_DATA['sponsorName']) == '')) {
        $errorMessage .= ENTER_COUNSULTING_SPONSOR."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['startDate']) || trim($REQUEST_DATA['startDate']) == '')) {
        $errorMessage .= ENTER_COUNSULTING_START_DATE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['endDate']) || trim($REQUEST_DATA['endDate']) == '')) {
        $errorMessage .= ENTER_COUNSULTING_END_DATE."\n";    
    }
    
    //if ($errorMessage == '' && (!isset($REQUEST_DATA['consultingAmountFunding']) || trim($REQUEST_DATA['consultingAmountFunding']) == '')) {
   //      $errorMessage .= ENTER_SEMINAR_START_DATE."\n";    
    //}
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['remarks']) || trim($REQUEST_DATA['remarks']) == '')) {
        $errorMessage .= ENTER_COUNSULTING_REMARKS."\n";    
    }
    
    if (trim($REQUEST_DATA['startDate']) == '0000-00-00') {
        $errorMessage .= ENTER_COUNSULTING_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['endtDate']) == '0000-00-00') {
        $errorMessage .= ENTER_COUNSULTING_END_DATE."\n";    
    }    
    
	 
    $employeeId =  $REQUEST_DATA['employeeId'];
     
    if (trim($errorMessage) == '') {
          require_once(MODEL_PATH . "/ConsultingManager.inc.php");
          $consultManager = ConsultingManager::getInstance();
		
           $returnStatus = $consultManager->addConsulting($employeeId);
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
// $History: ajaxInitConsultingAdd.php $ajaxInitPublishingAdd.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Consulting
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/18/09    Time: 3:04p
//Updated in $/Leap/Source/Library/Consulting
//Condition update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Consulting
//initial checkin 
//
//
 
?>