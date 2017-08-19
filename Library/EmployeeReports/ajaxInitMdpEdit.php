<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
// Author : Gagan Gill
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
   $errorMessage ='';

    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpName']) || trim($REQUEST_DATA['mdpName']) == '')) {
        $errorMessage .= ENTER_MDP_NAME."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpstartDate']) || trim($REQUEST_DATA['mdpstartDate']) == '')) {
        $errorMessage .= ENTER_MDP_START_DATE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpendDate']) || trim($REQUEST_DATA['mdpendDate']) == '')) {
        $errorMessage .= ENTER_MDP_END_DATE ."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSelectId']) || trim($REQUEST_DATA['mdpSelectId']) == '')) {
        $errorMessage .= SELECT_MDP."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpSessionAttended']) || trim($REQUEST_DATA['mdpSessionAttended']) == '')) {
        $errorMessage .= ENTER_MDP_SESSION_ATTENDED."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpHours']) || trim($REQUEST_DATA['mdpHours']) == '')) {
        $errorMessage .= ENTER_MDP_HOURS."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpVenue']) || trim($REQUEST_DATA['mdpVenue']) == '')) {
        $errorMessage .= ENTER_MDP_VENUE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpType']) || trim($REQUEST_DATA['mdpType']) == '')) {
        $errorMessage .= SELECT_MDP_TYPE_ID."\n";    
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['mdpDescription']) || trim($REQUEST_DATA['mdpDescription']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION ."\n";    
    }
    
    if (trim($REQUEST_DATA['mdpstartDate']) == '0000-00-00') {
        $errorMessage .= ENTER_MDP_START_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['mdpendDate']) == '0000-00-00') {
        $errorMessage .= ENTER_MDP_END_DATE."\n";    
    }
	
	if (trim($errorMessage) == '') {
         require_once(MODEL_PATH . "/EmployeeManager.inc.php");
         $empManager = EmployeeManager::getInstance();
        

            $returnStatus = $empManager->editMdp(add_slashes($REQUEST_DATA['mdpId']));
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