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
    
if(trim($REQUEST_DATA['mappingId'] ) != '') {
    require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php"); 
    $foundArray = ApplyLeaveManager::getInstance()->getEmployeeLeavesList(' AND l.leaveSessionId = '.$leaveSessionId.'  AND l.leaveId="'.trim($REQUEST_DATA['mappingId']).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        $foundArray2 = ApplyLeaveManager::getInstance()->getEmployeeLeavesComments(' AND l.leaveId="'.trim($REQUEST_DATA['mappingId']).'" AND lc.employeeId='.$foundArray[0]['employeeId']);
        $foundArray[0]['reason']=$foundArray2[0]['reason'];
       
        $taken=number_format($foundArray[0]['taken'],2);
        $balance=number_format($foundArray[0]['allowed'],2);
        $foundArray[0]['leaveRecord']= $taken." leave(s) taken out of allocated ".$balance." leaves.";
        
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>