<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMasterLabelWiseReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$userId=trim($REQUEST_DATA['userId']);
$mode=trim($REQUEST_DATA['mode']);
$reason=trim($REQUEST_DATA['reason']);
$labelId=trim($REQUEST_DATA['labelId']);

if($userId==''){
    echo STUDENT_NOT_EXIST;
    die;
}

if($labelId==''){
    die('Please select label');
}

if($reason=='' and $mode==FEEDBACK_STUDENT_FORCED_UNBLOCKED){
    echo 'Enter reason for unblocking';
    die;
}
    
  require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
  $fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();
  require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
  
  $doneByUserId=$sessionHandler->getSessionVariable('UserId');
  
 if(SystemDatabaseManager::getInstance()->startTransaction()) {
    if($mode==FEEDBACK_STUDENT_FORCED_UNBLOCKED){ 
        //unblock student
        $ret=$fbManager->changeStudentStatus($userId,FEEDBACK_STUDENT_FORCED_UNBLOCKED);
        if($ret==false){
         echo FAILURE;
         die;
        }
    
        //insert new row in log table
        $ret=$fbManager->doStatusLogEntry($userId,add_slashes($reason),FEEDBACK_STUDENT_COMPLETE,$doneByUserId);
        if($ret==false){
          echo FAILURE;
          die;
        }
    }
    else if($mode==FEEDBACK_STUDENT_BLOCKED){ //block this user(student)
        //check for his/her status
        $allowedToBlock=1;
        //$conditions .=' AND fs.feedbackSurveyId='.$labelId.' AND s.userId='.$userId;
        //$studentRecordArray = $fbManager->displayStudentFeedbackLabelWiseList($conditions,$labelId,$limit,' t.userId');
        $conditions .=' AND fs.feedbackSurveyId='.$labelId;
        $studentRecordArray = $fbManager->displayStudentFeedbackLabelWiseList($conditions,$userId,$labelId,$limit,' t.userId');
        
        if($studentRecordArray[0]['dateOverFlag']==1){
         if($studentRecordArray[0]['isCompletedFlag']!=1){
            $allowedToBlock=1;
         }
         else{
            $allowedToBlock=-1;
         }  
       }
       else{
          $allowedToBlock=0;
       }
       if($allowedToBlock!=1){
           if($allowedToBlock==-1){
             die('This student can not be blocked as he/she has completed feedback');
           }
           else{
             die('This student can not be blocked as extension date of label is not over yet');  
           }
       }
       else{
           
          $ret=$fbManager->changeStudentStatus($userId,FEEDBACK_STUDENT_BLOCKED);
          if($ret==false){
           echo FAILURE;
           die;
          } 
       }
    }
    else{
        die('Invalid operations');
    }
     
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
     echo SUCCESS;
     die;
    }
   else {
    echo FAILURE;
    die;
   }
 }
 else {
  echo FAILURE;
  die;
 }
?>