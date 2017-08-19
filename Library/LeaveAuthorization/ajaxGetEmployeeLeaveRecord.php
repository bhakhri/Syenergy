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
define('MODULE','AuthorizeEmployeeLeave');
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
        $cond = ' AND l.leaveId="'.trim($REQUEST_DATA['mappingId']).'" AND lc.employeeId='.$foundArray[0]['employeeId'].' AND lc.leaveSessionId='.$leaveSessionId;
        $foundArray2 = ApplyLeaveManager::getInstance()->getEmployeeLeavesComments($cond);
        $foundArray[0]['reason']= trim(chunk_split($foundArray2[0]['reason'],63,"<br>"));
        
        $leaveType=$foundArray[0]['leaveTypeId'];
        $sourceEmployeeId=$foundArray[0]['employeeId'];
        
        require_once(MODEL_PATH . "/AuthorizeLeaveManager.inc.php");
        $authorizeLeaveManager = AuthorizeLeaveManager::getInstance();
        //fetch employeeId of logged in user
        $empArray=$authorizeLeaveManager->getEmployeeInformation($sessionHandler->getSessionVariable('UserId'));
        $employeeId=$empArray[0]['employeeId'];
        if($employeeId==''){
         echo EMPLOYEE_LEAVE_NOT_EXIST; 
         die; 
        }
        
        //check for first/second authorization
        $authorizeArray= $authorizeLeaveManager->checkAuthorizationData($sourceEmployeeId,$leaveType,$leaveSessionId);
        $firstAuthorizer=$authorizeArray[0]['firstApprovingEmployeeId'];
        if($authorizeArray[0]['firstApprovingEmployeeId']==$employeeId){
           $foundArray[0]['authorizer']=1; 
        }
        else if($authorizeArray[0]['secondApprovingEmployeeId']==$employeeId){
           $foundArray[0]['authorizer']=2; 
        }
        else{
           echo 'Invalid mapping of authorization with employees';
           die;
        }
        
        //now fetch comments of the authorizer
        $foundArray[0]['reason1']='';
        if($firstAuthorizer!=''){
          $commentsArray=$authorizeLeaveManager->checkAuthorizationComments(trim($REQUEST_DATA['mappingId']),$firstAuthorizer,$leaveSessionId);
          if($commentsArray[0]['reason']==''){
             $commentsArray[0]['reason']="";
          }
          $foundArray[0]['reason1']=$commentsArray[0]['reason'];
        }
        
        //now find leaves taken/leaves value of this employee in this calendar year for this type of leave
        $currYear=date('Y');
        $leaveArray=$authorizeLeaveManager->checkLeaveRecords($sourceEmployeeId,$leaveType,$currYear,$leaveSessionId);
        $leavesTakenSofar=$leaveArray[0]['leavesTaken'];
        
        //check leave type limit for this employee
        //$leaveLimitArray =$authorizeLeaveManager->checkLeaveLimit($sourceEmployeeId,$leaveType);
        //$leaveLimit=$leaveLimitArray[0]['leaveValue'];

        //calculating total no. of leaves applied 
        $fromDate=explode('-',$foundArray[0]['leaveFromDate']);
        $toDate=explode('-',$foundArray[0]['leaveToDate']);
        $fromDate=gregoriantojd($fromDate[1],$fromDate[2],$fromDate[0]);
        $toDate=gregoriantojd($toDate[1],$toDate[2],$toDate[0]);
        $totalDays=($toDate-$fromDate)+1;
        
        $foundArray[0]['leaveFromDate']=UtilityManager::formatDate($foundArray[0]['leaveFromDate']);
        $foundArray[0]['leaveToDate']=UtilityManager::formatDate($foundArray[0]['leaveToDate']);
        $foundArray[0]['leaveAppliedFor']=$totalDays.' day(s)'; 
        $foundArray[0]['applicateDate']=UtilityManager::formatDate($foundArray[0]['applicateDate']);
        $foundArray[0]['leaveStatusName']=$leaveStatusArray[ $foundArray[0]['leaveStatus']];
        $taken=number_format($foundArray[0]['taken'],2);
        $balance=number_format($foundArray[0]['allowed'],2);
        $foundArray[0]['leaveRecord']= $taken." leave(s) taken out of allocated ".$balance." leaves.";
        echo json_encode($foundArray[0]);
    }
    else {
        echo EMPLOYEE_LEAVE_NOT_EXIST;
        die;
    }
}
// $History: ajaxGetValues.php $
?>
