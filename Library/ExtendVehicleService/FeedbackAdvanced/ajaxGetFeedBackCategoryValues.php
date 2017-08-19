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
define('MODULE','ADVFB_CategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['catId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackCategoryAdvancedManager.inc.php");
    $foundArray = FeedBackCategoryAdvancedManager::getInstance()->getFeedbackCategory(' WHERE feedbackCategoryId="'.add_slashes(trim($REQUEST_DATA['catId'])).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        if($foundArray[0]['parentFeedbackCategoryId']==''){
            $foundArray[0]['parentFeedbackCategoryId']=-1;
        }
        if($foundArray[0]['subjectTypeId']==''){
            $foundArray[0]['subjectTypeId']=-1;
        }
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackCategoryValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:47
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created module "Advanced Feedback Category Module"
?>