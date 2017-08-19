<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBack Label LIST
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateFeedBackLabels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['surveyType'] ) != '' and trim($REQUEST_DATA['sourceSurveyId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $str1='';$str2='';
    
    $foundArray = FeedBackManager::getInstance()->getCopyFeedBackLabel(' AND ffl.surveyType="'.$REQUEST_DATA['surveyType'].'" AND ffl.feedbackSurveyId !='.$REQUEST_DATA['sourceSurveyId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else{
        echo 0;
    }
}
// $History: ajaxGetCopySurveys.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Created in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:44
//Created in $/Leap/Source/Library/FeedBack
//Created "Copy Survey" Module
?>