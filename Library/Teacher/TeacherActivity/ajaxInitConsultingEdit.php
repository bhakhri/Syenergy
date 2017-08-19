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
define('MODULE','EmployeeInformation');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);  
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
    
     
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
        $consultManager = EmployeeManager::getInstance();

        $returnStatus = $consultManager->editConsulting(add_slashes($REQUEST_DATA['consultId']));
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
// $History: ajaxInitConsultingEdit.php $
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
//User: Parveen      Date: 7/13/09    Time: 3:39p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//initial checkin
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
