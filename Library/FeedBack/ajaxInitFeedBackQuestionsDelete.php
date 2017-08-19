<?php
//-------------------------------------------------------
// Purpose: To delete FeedBack detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackQuestionsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['feedbackQuestionId']) || trim($REQUEST_DATA['feedbackQuestionId']) == '') {
        $errorMessage = QUESTION_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        $feedBackQuestionManager =  FeedBackManager::getInstance();
        $feedBackGeneralQuestion = $feedBackQuestionManager->checkGeneralFeedBackQuestion("AND fq.feedbackQuestionId=".$REQUEST_DATA['feedbackQuestionId']);
        if ($feedBackGeneralQuestion[0]['feedbackQuestionId'] > 0 ) {
            echo DEPENDENCY_CONSTRAINT;
            exit(0);
        }
        $feedBackTeacherQuestion = $feedBackQuestionManager->checkTeacherFeedBackQuestion("AND fq.feedbackQuestionId=".$REQUEST_DATA['feedbackQuestionId']);
        if ($feedBackTeacherQuestion[0]['feedbackQuestionId'] > 0 ) {
            echo DEPENDENCY_CONSTRAINT;        
            exit(0);
        }
        if ($feedBackGeneralQuestion[0]['feedbackQuestionId'] == 0 && $feedBackTeacherQuestion[0]['feedbackQuestionId'] == 0) {
         if($feedBackQuestionManager->deleteFeedBackQuestions($REQUEST_DATA['feedbackQuestionId']) ) {
                echo DELETE;
          }
         else {
                echo DEPENDENCY_CONSTRAINT;
         }
        }
    }
   else {
     echo $errorMessage;
    }
// $History: ajaxInitFeedBackQuestionsDelete.php $    
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/07/09    Time: 1:28p
//Updated in $/Leap/Source/Library/FeedBack
//modified to check constraint
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>