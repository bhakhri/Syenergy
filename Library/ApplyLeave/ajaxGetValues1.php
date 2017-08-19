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

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

define('MODULE','COMMON');
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
    
    // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    $leaveSessionDate='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      $leaveSessionId=0;  
    }
    
    $employeeId = $REQUEST_DATA['employeeId'];
    $leaveType  = $REQUEST_DATA['leaveType'];     
   
    if($leaveType=='') {
      $leaveType=0;  
    }
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    

    require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php"); 
    $condition = ' AND l.leaveSessionId = '.$leaveSessionId.' AND l.employeeId="'.$employeeId.'" AND l.leaveTypeId="'.$leaveType.'"';
    $foundArray = ApplyLeaveManager::getInstance()->getEmployeeLeavesList($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       $taken=number_format($foundArray[0]['taken'],2);
       $balance=number_format($foundArray[0]['allowed'],2);
       $foundArray[0]['leaveRecord']= $taken." leave(s) taken out of allocated ".$balance." leaves.";
    }
    else {
       $foundArray[0]['leaveRecord']=NOT_APPLICABLE_STRING;  
    }
    
    echo json_encode($foundArray[0]);

// $History: ajaxGetValues.php $
?>