<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['publishId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $publishingManager = EmployeeManager::getInstance();
    
    $publishingArray = $publishingManager->getPublishing(' AND publishId="'.add_slashes($REQUEST_DATA['publishId']).'"');
    if(is_array($publishingArray) && count($publishingArray)>0 ) {  
        echo json_encode($publishingArray[0]);
		//die();
    }
    else {
        echo 0;
    }
}
// $History: ajaxPublishingGetValues.php $
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
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
//
?>