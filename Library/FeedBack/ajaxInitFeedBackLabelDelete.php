<?php
//-------------------------------------------------------
// Purpose: To delete FeedBack detail
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
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '') {
        $errorMessage = LABEL_NOT_EXIST;
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        $feedBackManager =  FeedBackManager::getInstance();
        $foundFeedBackGradeSurveyArray = $feedBackManager->getCheckFeedBackGrade("AND fs.feedbackSurveyId =".$REQUEST_DATA['labelId']);
        if ($foundFeedBackGradeSurveyArray[0]['feedbackSurveyId'] > 0) {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }

        $foundFeedBackSurveyArray = $feedBackManager->getCheckFeedBackSurveyId("AND fs.feedbackSurveyId =".$REQUEST_DATA['labelId']);
        if ($foundFeedBackSurveyArray[0]['feedbackSurveyId'] > 0) {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        else {
            if ($feedBackManager->deleteFeedBackLabel($REQUEST_DATA['labelId'])){
                echo DELETE;
                die;
                }
            }
    }
   else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackLabelDelete.php $    
//
//*****************  Version 3  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Library/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified in feedback label & role wise graph
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/09    Time: 5:21p
//Updated in $/Leap/Source/Library/FeedBack
//modified code accordingly new table feedback_survey_answer
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/09    Time: 5:55p
//Updated in $/Leap/Source/Library/FeedBack
//modified in code for delete
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified to check constraints
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/29/08   Time: 12:04p
//Updated in $/Leap/Source/Library/FeedBack
//Remove checking of "Active Label " Delete and Edit
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>