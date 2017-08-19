<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBack Label LIST
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
    $feedBackManager = FeedbackLabelManager::getInstance();
    $foundArray = $feedBackManager->getFeedBackLabel(' AND ffl.feedbackSurveyId="'.$REQUEST_DATA['labelId'].'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackLabelValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:03
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified "Feedback Label Master(Advanced)" as two new fields are added
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:10p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback advanced label module
//

?>