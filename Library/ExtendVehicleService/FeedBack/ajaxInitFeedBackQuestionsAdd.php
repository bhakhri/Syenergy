<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FeedBack Question
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackQuestionsMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '') {
        $errorMessage .=  SELECT_FEEDBACK_LABEL."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryId']) || trim($REQUEST_DATA['categoryId']) == '')) {
        $errorMessage .= SELECT_FEEDBACK_CATEGORY."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['questionTxt']) || trim($REQUEST_DATA['questionTxt']) == '')) {
        $errorMessage .= ENTER_FEEDBACK_QUESTION."\n";  
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        require_once(DA_PATH . '/SystemDatabaseManager.inc.php');  
        $foundArray = FeedBackManager::getInstance()->getFeedBackQuestions(' WHERE feedbackSurveyId='.$REQUEST_DATA['labelId'].' AND  UCASE(feedbackQuestion)="'.add_slashes(strtoupper($REQUEST_DATA['questionTxt'])).'"');
        if(trim($foundArray[0]['feedbackQuestion'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedBackManager::getInstance()->addFeedBackQuestions();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
           echo FEEDBACK_QUESTIONS_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackQuestionsAdd.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/04/09   Time: 11:31
//Updated in $/Leap/Source/Library/FeedBack
//Bug fixing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>