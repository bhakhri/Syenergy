<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A FeedBack Question
// Author : Gurkeerat Sidhu 
// Created on : (14.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    if ($errorMessage == '' && (!isset($REQUEST_DATA['questionTxt']) || trim($REQUEST_DATA['questionTxt']) == '')) {
        $errorMessage .= ADV_ENTER_FEEDBACK_QUESTION."\n";  
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['answerSet']) || trim($REQUEST_DATA['answerSet']) == '')) {
        $errorMessage .= ADV_SELECT_ANSWER_SET."\n";  
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
        require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); 
        $feedBackQuestionManager =  FeedbackQuestionManager::getInstance(); 
        $id = $REQUEST_DATA['feedbackQuestionId'];
        $countArr = $feedBackQuestionManager->checkQuestionDependency($id);
        if ($countArr[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT_EDIT;
            die;
            }
        $foundArray = $feedBackQuestionManager->getFeedBackQuestions(' WHERE feedbackQuestionSetId='.$REQUEST_DATA['questionSet'].' AND UCASE(feedbackQuestion)="'.add_slashes(strtoupper($REQUEST_DATA['questionTxt'])).'" AND feedbackQuestionId !='.$REQUEST_DATA['feedbackQuestionId']);
        if(trim($foundArray[0]['feedbackQuestion'])=='') {  //DUPLICATE CHECK
            $returnStatus = $feedBackQuestionManager->editFeedBackQuestions($REQUEST_DATA['feedbackQuestionId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                    }
                else{
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
// $History: ajaxInitFeedBackQuestionsEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:38p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under question master in feedback module
//

?>