<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

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
    
    
    
    // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }  
    
    if($leaveSessionId=='') {
      $leaveSessionId=0;  
    }   
    
    
if(trim($REQUEST_DATA['val']) != '') {
    require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");  
    $condition = ' AND e.employeeId="'.add_slashes(trim($REQUEST_DATA['val'])).'" AND lse.leaveSessionId='.$leaveSessionId;
    $foundArray = ApplyLeaveManager::getInstance()->getEmployeeLeaveTypes($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>