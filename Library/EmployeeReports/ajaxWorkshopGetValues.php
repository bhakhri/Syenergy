<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Parveen
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
    
//if(trim($REQUEST_DATA['seminarId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $workshopManager = EmployeeManager::getInstance();
        
    $workshopArray = $workshopManager->getWorkshop(' AND workshopId ='.add_slashes($REQUEST_DATA['workshopId']));
    if(is_array($workshopArray) && count($workshopArray)>0 ) {  
        echo json_encode($workshopArray[0]);
		//die();
    }
    else {
        echo 0;
    }
//}
// $History: ajaxWorkshopGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Library/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/15/09    Time: 12:41p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//

?>