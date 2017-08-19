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
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	//UtilityManager::ifStudentNotLoggedIn(true);

	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if (empty($roleId) or $roleId != 4) {
		redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
	}
	$userId = $sessionHandler->getSessionVariable('UserId');
	$studentId = $sessionHandler->getSessionVariable('StudentId');   
	$studentMgr=StudentInformationManager::getInstance();

	$sessionHandler->setSessionVariable('UserIdDisabledForInCompleteFeedback',0);
	$sessionHandler->setSessionVariable('LastDateOfProvidingFeedback','');

 
    
	//Getting information of all feedback assigned to student
	$allFeedbackArray=$studentMgr->checkFeedbackRecordNew();
	
    //Checking the feeback Id exist or not 
	$pendingFeedbackId=array();
   	$j=0;
   	$strQuery='';
    for($i=0;$i<count($allFeedbackArray);$i++){
      $checkFeedbackArray=$studentMgr->checkFeedbackRecordIdNew($allFeedbackArray[$i]['feedbackSurveyId']);
      
      if($checkFeedbackArray[0]['status']==0) {
         $pendingFeedbackId[$j]['feedbackSurveyId']=$allFeedbackArray[$i]['feedbackSurveyId'];
         $pendingFeedbackId[$j]['classId']=$allFeedbackArray[$i]['classId'];
         $pendingFeedbackId[$j]['studentId']=$allFeedbackArray[$i]['studentId'];
         $pendingFeedbackId[$j]['rollNo']=$allFeedbackArray[$i]['rollNo'];
         $pendingFeedbackId[$j]['isStatus']=2;
         $pendingFeedbackId[$j]['status']=1;
         if($strQuery!='') {
           $strQuery .=",";  
         }
         $strQuery .= "(".$pendingFeedbackId[$j]['feedbackSurveyId'].",".
                     $pendingFeedbackId[$j]['classId'].",".
                     $pendingFeedbackId[$j]['studentId'].",".
                     $pendingFeedbackId[$j]['isStatus'].",".
                     $pendingFeedbackId[$j]['status'].")";
         $j++;
       }
    }
           
  	//Inserting values of Pending feedback in table
    if($strQuery!='') {
  	   if(count($pendingFeedbackId)!=0){
  	     if($strQuery!=''){
          if(SystemDatabaseManager::getInstance()->startTransaction()) {   
             $checkstatus1=$studentMgr->insertPendingFeedbackRecord($strQuery);
             if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
                if($checkstatus1 === false) {
                  // echo FAILURE;
                }
             }
          }
   	    }
   	   }                 
    }
    $feedbackArray=$studentMgr->checkFeedbackStudentStatusNew($studentId);
    
    
   /* if(is_array($feedbackArray)>0 and count($feedbackArray)>0) { 
       if(count($feedbackArray) == 1 && $feedbackArray[0]['isStatus'] == 1) {
         //die; 
       }
    }*/
    
    //New Check to check the pending feedback
    $check=0;
    for($k=0;$k<count($feedbackArray);$k++){
        if($feedbackArray[$k]['isStatus']!=1){
          $check++;
        }
    }
  
    if($check==0){
        
    }
    else { 
         $feedbackStudentStatusArray=$studentMgr->checkBlockFeedbackStudentStatus($studentId);
         
         for($i=0;$i<count($feedbackStudentStatusArray);$i++){
           if(is_array($feedbackStudentStatusArray)>0 and count($feedbackStudentStatusArray)>0){
            $feedbackStudentStatus=$feedbackStudentStatusArray[$i]['status'];
            if($feedbackStudentStatus==1){
               //get the total labels applicable to this user
               $labelArray=CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId,1);
               $labelCnt=count($labelArray);
               $labelString='';
               for($j=0;$j<$labelCnt;$j++){
                  if($labelString!=''){
                      $labelString .=',';
                  }
                  $labelString .=$labelArray[$j]['feedbackSurveyId'];
               }
                
               if($labelString!=''){
                   //get the maximum visible date of these labels
                   $maxDateArray=$studentMgr->getMaxDateOfFeedbackLabels($labelString);
                   if(is_array($maxDateArray) and count($maxDateArray)>0){
                       $maxDate   = strtotime($maxDateArray[0]['maxDate']);
                       $logInDate = strtotime(date('Y-m-d'));
                       if($logInDate > $maxDate){
                           //make userId disabled
                           $sessionHandler->setSessionVariable('UserIdDisabledForInCompleteFeedback',2);
                       }
                       else{
                           //show warning only
                           $sessionHandler->setSessionVariable('UserIdDisabledForInCompleteFeedback',1);
                           $sessionHandler->setSessionVariable('LastDateOfProvidingFeedback',UtilityManager::formatDate($maxDateArray[0]['maxDate']));
                       }
                   }
               } 
            } 
            }
        }
    }
  

// $History: ajaxCheckStudentFeedbackStatus.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/02/10    Time: 19:05
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Corrected code
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:19
//Created in $/LeapCC/Library/FeedbackAdvanced
//Added files for feedback modules
?>
