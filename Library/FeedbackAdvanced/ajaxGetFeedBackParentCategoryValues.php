<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_CategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackCategoryAdvancedManager.inc.php");
    //$foundArray = FeedBackCategoryAdvancedManager::getInstance()->getParentCategory(' AND fs.feedbackSurveyId="'.add_slashes(trim($REQUEST_DATA['labelId'])).'"');
    $foundArray = FeedBackCategoryAdvancedManager::getInstance()->getParentCategory();
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackParentCategoryValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:47
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Advanced Feedback Category Module"
?>