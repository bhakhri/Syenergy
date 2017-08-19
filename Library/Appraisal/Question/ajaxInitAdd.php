<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalQuestionMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     
     if (!isset($REQUEST_DATA['question']) || trim($REQUEST_DATA['question']) == '') {
        $errorMessage .=  ENTER_APPRAISAL_QUESTION."\n"; 
     }
     if (!isset($REQUEST_DATA['weightage']) || trim($REQUEST_DATA['weightage']) == '') {
        $errorMessage .=  ENTER_APPRAISAL_QUESTION_WEIGHTAGE."\n"; 
     }
     if (!isset($REQUEST_DATA['titleId']) || trim($REQUEST_DATA['titleId']) == '') {
        $errorMessage .=  SELECT_APPRAISAL_TITLE."\n"; 
     }
     if (!isset($REQUEST_DATA['tabId']) || trim($REQUEST_DATA['tabId']) == '') {
        $errorMessage .=  SELECT_APPRAISAL_TAB."\n"; 
     }
     
     if(trim($REQUEST_DATA['isProof'])==1){
         if(trim($REQUEST_DATA['proofId'])==''){
           $errorMessage .=  SELECT_APPRAISAL_PROOF_FORM."\n";   
         }
     }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/QuestionManager.inc.php");

        //check for duplicate value
        $foundArray = QuestionManager::getInstance()->getQuestion(' WHERE ( UCASE(TRIM(appraisalText))="'.add_slashes(strtoupper(trim($REQUEST_DATA['question']))).'" )');
        if(trim($foundArray[0]['appraisalText'])!='') {  //DUPLICATE CHECK
          echo APPRAISAL_QUESTION_ALREADY_EXIST;
          die;
        }
        
        //check for usage of proof form
        if(trim($REQUEST_DATA['isProof'])==1){
          $foundArray = QuestionManager::getInstance()->getQuestion(' WHERE ( appraisalProofId ="'.add_slashes(strtoupper(trim($REQUEST_DATA['proofId']))).'" )');
          if(trim($foundArray[0]['appraisalProofId'])!='') {  //DUPLICATE CHECK
           echo APPRAISAL_PROOF_USED;
           die;
          }
        }
        
        //add new question
        $returnStatus = QuestionManager::getInstance()->addQuestion();
        if($returnStatus == false) {
            echo FAILURE;
        }
        else {
            echo SUCCESS;           
        }
        
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>