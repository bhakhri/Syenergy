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
define('MODULE','ApplyEmployeeLeave');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
 UtilityManager::ifNotLoggedIn(true);
}
else if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==5){
  UtilityManager::ifManagementNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);  
}
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['val']) != '') {
    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");  
    $foundArray = EmployeeLeaveSetMappingManager::getInstance()->getEmployeeInfo(' AND isActive=1  AND employeeCode="'.add_slashes(trim($REQUEST_DATA['val'])).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>