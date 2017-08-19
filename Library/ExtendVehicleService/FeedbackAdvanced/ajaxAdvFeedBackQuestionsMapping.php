<?php
//------------------------------------------------------------------
// THIS FILE IS USED TO Allocate/De-allocate Adv. Feedback Questions
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackQuestionMappingAdvancedManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transactions
$opName = trim($REQUEST_DATA['opName']);
define('MODULE','ADVFB_QuestionMappingMaster');
define('ACCESS',$opName);

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    $fbMgr=FeedBackQuestionMappingAdvancedManager::getInstance();
    
        if ($errorMessage == '' && (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '')) {
            $errorMessage .= SELECT_ADV_LABEL_NAME."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['catId']) || trim($REQUEST_DATA['catId']) == '')) {
            $errorMessage .= SELECT_ADV_CAT_NAME."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['questionSetId']) || trim($REQUEST_DATA['questionSetId']) == '')) {
            $errorMessage .= SELECT_ADV_QUESTION_SET_NAME."\n";  
        }
        
        $labelId           = trim($REQUEST_DATA['labelId']);
        $catId             = trim($REQUEST_DATA['catId']);
        $questionSetId     = trim($REQUEST_DATA['questionSetId']);
        $deallocationFlag  = trim($REQUEST_DATA['deallocationFlag']);
        
        //************************START THE DE-ALLOCATION PROCESS******************************
        if($deallocationFlag==1){
            //fetch mappedQuestionIds related to selected label,cat and question sets
            /*
            $usageArray=$fbMgr->getQuestionUsageListAdvanced(' WHERE feedbackSurveyId='.$labelId.' AND feedbackCategoryId='.$catId.' AND feedbackQuestionSetId='.$questionSetId);
            if(count($usageArray)!=0){ //if questions already used
                echo ADV_QUESTIONS_MAPPING_RESTRICTION;
                die;
            }
            */
            
            /********START TRANSACTION*******/
            if(SystemDatabaseManager::getInstance()->startTransaction()) {
               //de-allocate all questions 
               $ret=$fbMgr->deleteMappedQuestionsAdvanced($labelId,$catId,$questionSetId);
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
             else{
               echo FAILURE;
               die;
             } 
            /********END TRANSACTION*******/
        }
        //************************END THE DE-ALLOCATION PROCESS******************
        
        $mappedQuestionIds = explode(',',trim($REQUEST_DATA['questionList1']));
        $questionIds       = explode(',',trim($REQUEST_DATA['questionList2']));
        $printOrderValues  = explode(',',trim($REQUEST_DATA['printOrderList']));
        
        //input data validation;
        if(trim($errorMessage)!=''){
            echo trim($errorMessage);
            die;
        }
        
        $cnt1=count($mappedQuestionIds);
        $cnt2=count($printOrderValues);
        
        //count of questions and print orders must be same
        if($cnt1!=$cnt2){
           echo MISMATCHED_QUESTIONS_AND_PRINT_ORDER;
           die;    
        }
        
        //validation of print orders
        for($i=0;$i<$cnt2;$i++){
            
            if(trim($printOrderValues[$i])==''){
                echo ENTER_ADV_PRINT_ORDER;
                die;
            }
            
            if(!is_numeric(trim($printOrderValues[$i]))){
                echo ENTER_ADV_PRINT_ORDER_IN_NUMERIC;
                die;
            }
            
            if(intval(trim($printOrderValues[$i]))<=0){
                echo ADV_PRINT_ORDER_GREATER_THAN_ZERO;
                die;
            }
        }
        
        
        
        //check for usage of this mapped questions ids in answer table.
        //normally this will not happen, as we have already filterd out used values in list.php file.
        //this is done to have extra checks
        $mappedQuestionIdsString=implode(',',$mappedQuestionIds);
        $usageArray = $fbMgr->getQuestionUsageList($mappedQuestionIdsString);
        if(is_array($usageArray) and count($usageArray)>0){
            echo ADV_QUESTIONS_MAPPING_RESTRICTION;
            die;
        }
        
        
        
        //***********START TRANSACTION**********
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
          
           //first get the no. of questions allocated with these label,category and question set
           $oldQuestionCount=0;
           $oldQuestionCountArray=$fbMgr->getQuestionCount($labelId,$catId,$questionSetId);
           if(is_array($oldQuestionCountArray) and count($oldQuestionCountArray)>0){
               $oldQuestionCount=$oldQuestionCountArray[0]['cnt'];
           }
            
          //first delete all mapped questions
          $ret=$fbMgr->deleteMappedQuestionsAdvanced($labelId,$catId,$questionSetId);
          if($ret==false){
              echo FAILURE;
              die;
          }
          
          //then make fresh insert
          $insertQuery='';
          for($i=0;$i<$cnt1;$i++){
              if($insertQuery!=''){
                  $insertQuery .= ' , ';
              }
              $insertQuery  .=" ( $labelId,$catId,$questionSetId,$questionIds[$i],'".add_slashes(trim($printOrderValues[$i]))."') ";
          }
          
          if($insertQuery==''){
             echo FAILURE;
             die;    
          }
          
          $ret=$fbMgr->doQuestionsMapping($insertQuery);
          if($ret==false){
            echo FAILURE;
            die;  
          }
          
          //now get the no. of questions allocated with these label,category and question set
           $newQuestionCount=0;
           $newQuestionCountArray=$fbMgr->getQuestionCount($labelId,$catId,$questionSetId);
           if(is_array($newQuestionCountArray) and count($newQuestionCountArray)>0){
               $newQuestionCount=$newQuestionCountArray[0]['cnt'];
           }
           //if new questions are allocated
           if($oldQuestionCount!=$newQuestionCount){
              //then make respective users of student role in status table as blocked
              $ret=$fbMgr->updateFeedbackStudentStatus($labelId,$catId,$questionSetId);
              if($ret==false){
                 echo FAILURE;
                 die;     
              }
              //and insert new row in log table
              $ret=$fbMgr->insertFeedbackStudentStatusLog($labelId,$catId,$questionSetId);
              if($ret==false){
                 echo FAILURE;
                 die;     
              }
           }

         
         //now commit the transaction   
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
        //***********END TRANSACTION**********
    
// $History: ajaxAdvFeedBackQuestionsMapping.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:54
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Feedback Question Mapping (Advanced)"
?>