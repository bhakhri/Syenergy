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
    
if(trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackManager = FeedBackManager::getInstance();
    $foundFeedBackGradeSurveyArray = $feedBackManager->getCheckFeedBackGrade("AND fs.feedbackSurveyId =".$REQUEST_DATA['labelId']);
    if ($foundFeedBackGradeSurveyArray[0]['feedbackSurveyId'] > 0 ) {
        $checkFeedBack = $foundFeedBackGradeSurveyArray[0]['feedbackSurveyId'];
    }
    
    $foundArray = $feedBackManager->getFeedBackLabel(' AND feedbackSurveyId="'.$REQUEST_DATA['labelId'].'"');

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        $json_feedback = json_encode($foundArray[0]);
        echo '{"checkFeedBack":"'.$checkFeedBack.'","feedBackInfo":['.$json_feedback.']}';
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackLabelValues.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Library/FeedBack
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified in feedback label & role wise graph
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>