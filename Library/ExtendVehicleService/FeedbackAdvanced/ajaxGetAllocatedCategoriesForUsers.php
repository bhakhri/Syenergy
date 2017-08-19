<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
 require_once(MODEL_PATH . "/FeedBackProvideAdvancedManager.inc.php");
define('MODULE','ADVFB_ProvideFeedBack');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$userId=$sessionHandler->getSessionVariable('UserId');
$studentId=$sessionHandler->getSessionVariable('StudentId');

if($studentId=='') {
  $studentId=0;  
}

if($userId=='') {
  $userId=0;  
}

if($roleId==2){//for teacher
  UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==3){//for parent
 //not implemented till now
 redirectBrowser(UI_HTTP_PATH.'/Parent/index.php');
}
else if($roleId==4){ //for student
  UtilityManager::ifStudentNotLoggedIn();
}
else{
  redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
UtilityManager::headerNoCache();                           
                             
    $surveyId=$REQUEST_DATA['surveyId'];
    if($surveyId=='') {
      $surveyId='0';  
    }
    
    $condition="studentId='$studentId' And surveyId='$surveyId'";
    $blockfoundArray  = FeedBackProvideAdvancedManager::getInstance()->getblockStudent($condition);
	if($blockfoundArray[0]['status']!='' && $blockfoundArray[0]['status']==0 ){
	  echo 'Feedback Blocked!';
	  die;
	}
	
if($surveyId != '' ) {
     $foundArray  = FeedBackProvideAdvancedManager::getInstance()->getMappedCategoriesForUsers($surveyId,$roleId,$userId);
     //fetch user's attempt status for this label
     $foundArray2 = FeedBackProvideAdvancedManager::getInstance()->getUserAttemptsStatus($surveyId,$userId);
     if(is_array($foundArray) and count($foundArray)>0){ 
       if(is_array($foundArray2) and count($foundArray2)>0){
        if($foundArray2[0]['noOfAttempts']>0){//we will not show 0 as we decided that 0 means,means no restriction
         echo json_encode($foundArray).'!~!~!~! Number of attempts for "'.trim($foundArray2[0]['feedbackSurveyLabel']).'" is '.trim($foundArray2[0]['noOfAttempts']).' , You have attempted '.trim($foundArray2[0]['attempts']).' times.';
         die;
        }
        else{
            echo json_encode($foundArray);;
            die;
        }
       }
       else{
           echo json_encode($foundArray);
           die;
       }
     }
     else{
         echo 0;
         die;
     }
    }
    else {
        echo 0;
        die;
    }
// $History: ajaxGetAllocatedCategoriesForUsers.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:16
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Provide Feedback" Module
?>
