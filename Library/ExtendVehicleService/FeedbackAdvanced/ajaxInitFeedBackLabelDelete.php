<?php
//-------------------------------------------------------
// Purpose: To delete FeedBack detail
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['labelId']) || trim($REQUEST_DATA['labelId']) == '') {
        $errorMessage = LABEL_NOT_EXIST;
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
        $feedBackManager =  FeedbackLabelManager::getInstance();
        //check for usage of this label
        /*$usageArray= FeedbackLabelManager::getInstance()->checkFeedBackLabelUsage(' WHERE feedbackSurveyId='.trim($REQUEST_DATA['labelId']));
        if($usageArray[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
        } */
        //check for usage of this label
        $labelId=trim($REQUEST_DATA['labelId']);
        $answerArray = FeedbackLabelManager::getInstance()->checkFeedbackLabelInAnswer($labelId);
        if($answerArray[0]['totalRecord']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;  
        }
        if ($feedBackManager->deleteFeedBackLabel(trim($REQUEST_DATA['labelId']))){
            echo DELETE;
            die;
        }
        else{
            echo FAILURE;
            die;
        }
     }
   else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackLabelDelete.php $    
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/03/10    Time: 4:35p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated file to add edit/delete checks
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