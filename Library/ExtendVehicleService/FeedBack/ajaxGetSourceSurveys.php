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
    
if(trim($REQUEST_DATA['surveyType'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $str1='';$str2='';
    
    $foundArray = FeedBackManager::getInstance()->getSourceFeedBackLabel(' AND ffl.surveyType="'.$REQUEST_DATA['surveyType'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        $str1= json_encode($foundArray);
    }
    else {
        $str1=0;
    }
    /*
    $foundArray2 = FeedBackManager::getInstance()->getCopyFeedBackLabel(' AND ffl.surveyType="'.$REQUEST_DATA['surveyType'].'"');
    if(is_array($foundArray2) && count($foundArray2)>0 ) {  
        $str2= json_encode($foundArray2);
    }
    else {
        $str2=0;
    }
   */ 
    //echo $str1.'~'.$str2;
    echo $str1;
}
else{
    echo 0;
}
// $History: ajaxGetSourceSurveys.php $
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