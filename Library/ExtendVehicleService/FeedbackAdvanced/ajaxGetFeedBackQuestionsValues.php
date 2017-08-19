<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBack Question LIST
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
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feedbackQuestionId'] ) != '') {
    require_once(MODEL_PATH . "/FeedbackQuestionManager.inc.php");
     $feedBackQuestionManager =  FeedbackQuestionManager::getInstance();
   
    $foundArray = $feedBackQuestionManager->getFeedBackQuestions(' WHERE feedbackQuestionId="'.$REQUEST_DATA['feedbackQuestionId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackQuestionsValues.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:38p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under question master in feedback module
//

?>