<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Parveen
// Created on : (28.02.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','delete');
UtilityManager::ifTeacherNotLoggedIn(true); 
UtilityManager::headerNoCache();
    
    if (!isset($REQUEST_DATA['workshopId']) || trim($REQUEST_DATA['workshopId']) == '') {
        $errorMessage = 'Invalid Workshop Id';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
        $workshopManager = EmployeeManager::getInstance();
        
        if($workshopManager->deleteWorkshop($REQUEST_DATA['workshopId'])) {
           echo DELETE;
        }
        else {
           echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
   
   
    
// $History: ajaxInitWorkshopDelete.php $    
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

