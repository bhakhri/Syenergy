<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FeedBack Question
// Author : Gurkeerat Sidhu
// Created on : (14.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Questions');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['questionSet']) || trim($REQUEST_DATA['questionSet']) == '') {
        $errorMessage .=  ADV_SELECT_FEEDBACK_LABEL."\n"; 
    }
    $questionSet = $REQUEST_DATA['questionSet'];
    $answer = $REQUEST_DATA['answerSets'];
    $question = $REQUEST_DATA['questionTxts'];
    $answerArr = explode(',',$answer);
    $questionArr = explode('!@~!~!@~!@~!',$question);
    $answerCount = count($answerArr);
    $questionCount = count($questionArr);
    $questionUniqueCount = count(array_unique($questionArr));
    if($questionCount != $questionUniqueCount){
       echo "You can not enter same question under one question set ";
       die; 
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
        require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
        $feedBackQuestionManager =  FeedbackQuestionManager::getInstance();
        $quesValue='';
            for ($i=0;$i<$questionCount;$i++){
              if($quesValue!=''){
                  $quesValue .=' , ';
              }  
               $quesValue .='"'.add_slashes(trim($questionArr[$i])).'"';
             }  
        $foundArray = $feedBackQuestionManager->getFeedBackQuestions(' WHERE feedbackQuestionSetId='.$REQUEST_DATA['questionSet'].' AND  UCASE(feedbackQuestion) IN ('.$quesValue.')');
        if(trim($foundArray[0]['feedbackQuestion'])=='') {  //DUPLICATE CHECK
            
            $value='';
            for ($i=0;$i<$answerCount;$i++){
              if($value!=''){
                  $value .=' , ';
              }  
               $value .='( '.$questionSet.','. $answerArr[$i].',"'.add_slashes(trim($questionArr[$i])).'" )';
             }
             
            $returnStatus = $feedBackQuestionManager->addFeedBackQuestions($value);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            } 
        }
        else {
           echo ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackQuestionsAdd.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/02/10    Time: 5:31p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated code to add multiple questions at the same time
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:38p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under question master in feedback module
//

?>