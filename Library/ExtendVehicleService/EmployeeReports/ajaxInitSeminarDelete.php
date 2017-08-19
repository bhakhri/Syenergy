<?php
//-------------------------------------------------------
// Purpose: To delete document detail
//
// Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    if (!isset($REQUEST_DATA['seminarId']) || trim($REQUEST_DATA['seminarId']) == '') {
        $errorMessage = 'Invalid Seminar';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
        $seminarManager = EmployeeManager::getInstance();
        
		if($seminarManager->deleteSeminars(add_slashes($REQUEST_DATA['seminarId']))) {
		   echo DELETE;
		}
		else {
		   echo DEPENDENCY_CONSTRAINT;
	    }
	}
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitSeminarDelete.php $    
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/13/09    Time: 12:34p
//Created in $/LeapCC/Library/EmployeeReports
//file added
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

