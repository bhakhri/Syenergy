<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
die('This report is closed now.Please go to Feedback Label Wise Survey Report (Advanced)');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMasterReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$userId=trim($REQUEST_DATA['id']);
$reason=trim($REQUEST_DATA['reason']);
if($userId==''){
    echo STUDENT_NOT_EXIST;
    die;
}

if($reason==''){
    echo 'Enter reason for unblocking';
    die;
}
    
  require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
  $fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();
  require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
  
  $doneByUserId=$sessionHandler->getSessionVariable('UserId');
  
 if(SystemDatabaseManager::getInstance()->startTransaction()) {
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
    

// $History: doStudentUblock.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:05
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created the repoort for showing student status for feedbacks
?>