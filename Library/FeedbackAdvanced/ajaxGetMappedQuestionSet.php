<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) != ''  and trim($REQUEST_DATA['catId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
    $foundArray = FeedBackAssignSurveyAdvancedManager::getInstance()->getMappedQuestionSet(' AND ttl.timeTableLabelId="'.add_slashes(trim($REQUEST_DATA['timeTableLabelId'])).'" AND fs.feedbackSurveyId="'.add_slashes(trim($REQUEST_DATA['labelId'])).'" AND fc.feedbackCategoryId="'.add_slashes(trim($REQUEST_DATA['catId'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetMappedQuestionSet.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>