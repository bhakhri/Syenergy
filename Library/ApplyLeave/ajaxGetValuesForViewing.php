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

   global $sessionHandler;
   $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS'); 
   
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
        //find comments from 1st and 2nd approving authority
        $foundArray3 = ApplyLeaveManager::getInstance()->getEmployeeLeavesCommentsFromAuthorizers(' AND lc1.leaveId="'.trim($REQUEST_DATA['mappingId']).'"',' AND lc2.leaveId="'.trim($REQUEST_DATA['mappingId']).'"',$leaveSessionId);
        
        if($foundArray3[0]['reason1']==''){
            $foundArray3[0]['reason1']=NOT_APPLICABLE_STRING;
        }
        if($foundArray3[0]['reason2']==''){
            $foundArray3[0]['reason2']=NOT_APPLICABLE_STRING;
        }
        $foundArray[0]['reason1']=$foundArray3[0]['reason1'];
        $foundArray[0]['reason2']=$foundArray3[0]['reason2'];
        $foundArray[0]['leaveFromDate']=UtilityManager::formatDate($foundArray[0]['leaveFromDate']);
        $foundArray[0]['leaveToDate']=UtilityManager::formatDate($foundArray[0]['leaveToDate']);
        $foundArray[0]['applicateDate']=UtilityManager::formatDate($foundArray[0]['applicateDate']);
        $empStatus=$leaveStatusArray[ $foundArray[0]['leaveStatus']];
        
        $taken=number_format($foundArray[0]['taken'],2);
	
        $balance=number_format($foundArray[0]['allowed'],2);
       
        $foundArray[0]['leaveRecord']= $taken." leave(s) taken out of allocated ".$balance." leaves.";
       
        if($foundArray[0]['leaveStatus']==1) {
           if($leaveAuthorizersId==2) {
             $empStatus= $leaveStatusArray[$foundArray[0]['leaveStatus']]." By ".$foundArray[0]['firstEmployee']; 
           }
           else {
             $empStatus= "Approved By ".$foundArray[0]['firstEmployee']; 
           }
        }
        else if($foundArray[0]['leaveStatus']==2) {  
           if($leaveAuthorizersId==2) {
             $empStatus= $leaveStatusArray[$foundArray[0]['leaveStatus']]." By ".$foundArray[0]['secondEmployee'];   
           }
		   
        } 
        else if($foundArray[0]['leaveStatus']==3) {  
           if($foundArray[0]['secondApprovingEmployeeId']==""){
              $empStatus= $leaveStatusArray[$foundArray[0]['leaveStatus']]." By ".$foundArray[0]['firstEmployee'];   
           }
           else {
              $empStatus= $leaveStatusArray[$foundArray[0]['leaveStatus']]." By ".$foundArray[0]['secondEmployee']; 
           }
        }  
        
        $foundArray[0]['leaveStatus']=$empStatus;

        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
?>
