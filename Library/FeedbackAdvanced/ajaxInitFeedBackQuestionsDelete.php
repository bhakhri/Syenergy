<?php
//-------------------------------------------------------
// Purpose: To delete FeedBack detail
//
// Author : Gurkeerat Sidhu 
// Created on : (14.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Questions');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['feedbackQuestionId']) || trim($REQUEST_DATA['feedbackQuestionId']) == '') {
        $errorMessage = QUESTION_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
        $feedBackQuestionManager =  FeedbackQuestionManager::getInstance();
        $id = trim($REQUEST_DATA['feedbackQuestionId']);
        //$countArr = $feedBackQuestionManager->checkQuestionDependency($id);
        $ret = $feedBackQuestionManager->checkQuestionDependency($id);
        
        if ($ret==1){
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        
        if($feedBackQuestionManager->deleteFeedBackQuestions($id) ) {
            echo DELETE;
            die;
        }
        else {
            echo FAILURE;
            die;
        }
    }
   else {
     echo $errorMessage;
    }
// $History: ajaxInitFeedBackQuestionsDelete.php $    
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