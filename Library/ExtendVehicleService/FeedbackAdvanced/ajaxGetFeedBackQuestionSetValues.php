<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Category Div
// Author : Dipanjan Bhattacharjee
// Created on : (09.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_QuestionSet');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['setId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackQuestionSetAdvancedManager.inc.php");
    $foundArray = FeedBackQuestionSetAdvancedManager::getInstance()->getFeedbackQuestionSet(' AND feedbackQuestionSetId="'.add_slashes(trim($REQUEST_DATA['setId'])).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackQuestionSetValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:30
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created  "Question Set Master"  module
?>