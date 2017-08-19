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
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
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
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:41p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
?>

