<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBack Question LIST
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackQuestionsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feedbackQuestionId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
     $feedBackQuestionManager =  FeedBackManager::getInstance();
    $feedBackGeneralQuestion = $feedBackQuestionManager->checkGeneralFeedBackQuestion("AND fq.feedbackQuestionId=".$REQUEST_DATA['feedbackQuestionId']);
        if ($feedBackGeneralQuestion[0]['feedbackQuestionId'] > 0 ) {
            echo "Data could not be edited due to records existing in linked tables";
            die();
        }
        $feedBackTeacherQuestion = $feedBackQuestionManager->checkTeacherFeedBackQuestion("AND fq.feedbackQuestionId=".$REQUEST_DATA['feedbackQuestionId']);
        if ($feedBackTeacherQuestion[0]['feedbackQuestionId'] > 0 ) {
            echo "Data could not be edited due to records existing in linked tables";        
            die();
        }
    if ($feedBackGeneralQuestion[0]['feedbackQuestionId'] == 0 && $feedBackTeacherQuestion[0]['feedbackQuestionId'] == 0) {
    $foundArray = FeedBackManager::getInstance()->getFeedBackQuestions(' WHERE feedbackQuestionId="'.$REQUEST_DATA['feedbackQuestionId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
    }
}
// $History: ajaxGetFeedBackQuestionsValues.php $
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