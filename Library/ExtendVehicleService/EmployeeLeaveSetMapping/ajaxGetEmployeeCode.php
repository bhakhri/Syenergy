<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['val']) != '') {
    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
    if(trim($REQUEST_DATA['id'])==1){
     $foundArray = EmployeeLeaveSetMappingManager::getInstance()->getEmployeeInfo(' AND isActive=1  AND employeeId="'.add_slashes(trim($REQUEST_DATA['val'])).'"');
    }
    else{
        $foundArray = EmployeeLeaveSetMappingManager::getInstance()->getEmployeeInfo(' AND isActive=1  AND employeeCode="'.add_slashes(trim($REQUEST_DATA['val'])).'"');
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>