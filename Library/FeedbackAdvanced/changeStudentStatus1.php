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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ADVFB_AssignSurveyMasterLabelWiseReport');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager1.inc.php");
  $fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();
  
  require_once(MODEL_PATH . "/StudentManager.inc.php");
  $studentManager = StudentManager::getInstance(); 
  
  global $sessionHandler;   
  $doneByUserId=$sessionHandler->getSessionVariable('UserId');
  

  $studentId=trim($REQUEST_DATA['userId']);
  $mode=trim($REQUEST_DATA['mode']);
  $reason=trim($REQUEST_DATA['reason']);
  $labelId=trim($REQUEST_DATA['labelId']);
  
  if($studentId=='') {
    $studentId='0';  
  }

  $userId='0';
  $foundArray = $studentManager->getSingleField('student', 'userId', "WHERE studentId  = '$studentId'"); 
  if(is_array($foundArray) && count($foundArray)>0 ) {  
    $userId=$foundArray[0]['userId'];   
  }
  
  
    if($studentId==''){
        echo STUDENT_NOT_EXIST;
        die;
    }

    if($labelId==''){
        die('Please select label');
    }

    if($reason=='' and $mode==2){
        echo 'Enter reason for unblocking';
        die;
    }
    
    // Mode: 0 => Unblock Student, 1=> Block Student, 2=> Unblock By Admin       
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if($mode==2){ 
            //unblock student
            $condition =  " studentId IN ($studentId) AND surveyId = $labelId "; 
            $returnStatus=$fbManager->changeStudentStatus1($condition,2);
            if($returnStatus === false) {
                echo FAILURE;
                 die;
            } 
        
            //insert new row in log table
            $returnStatus=$fbManager->doStatusLogEntry1($studentId,add_slashes($reason),FEEDBACK_STUDENT_COMPLETE,$doneByUserId);
            if($returnStatus === false) {
                echo FAILURE;
                 die;
            } 
        }
        else if($mode==1){   // block this user(student)
            //check for his/her status
            $allowedToBlock=1;
            //$conditions .=' AND fs.feedbackSurveyId='.$labelId.' AND s.userId='.$userId;
            //$studentRecordArray = $fbManager->displayStudentFeedbackLabelWiseList($conditions,$labelId,$limit,' t.userId');
            $conditions .=' AND fs.feedbackSurveyId='.$labelId;
            $studentRecordArray = $fbManager->displayStudentFeedbackLabelWiseList($conditions,$userId,$labelId,$limit,' t.userId');
            
           if($studentRecordArray[0]['dateOverFlag']==1) {
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
              $condition =  " studentId IN ($studentId) AND surveyId = $labelId ";    
              $ret=$fbManager->changeStudentStatus1($condition,1);
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
