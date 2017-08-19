<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit A FeedBack Options
//
//
// Author : Gurkeerat Sidhu
// Created on : (12.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;

require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedbackOptionsManager.inc.php");
define('MODULE','ADVFB_Options');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
        if (!isset($REQUEST_DATA['optionLabel']) || add_slashes(trim($REQUEST_DATA['optionLabel'])) == '') {
        $errorMessage .= ENTER_OPTION_LABEL."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['optionPoints']) || add_slashes(trim($REQUEST_DATA['optionPoints'])) == '')) {
        $errorMessage .= ENTER_OPTION_VALUE."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['printOrder']) || add_slashes(trim($REQUEST_DATA['printOrder'])) == '')) {
        $errorMessage .= ENTER_ADV_PRINT_ORDER."\n";  
    }

	if ($REQUEST_DATA['printOrder']<1 || $REQUEST_DATA['printOrder']>100) {
       $errorMessage .= ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS."\n";  
    }


    if (trim($errorMessage) == '') {
        $feedbackOptionsManager =  FeedbackOptionsManager::getInstance();
        $id  = $REQUEST_DATA['answerSetOptionId'];
        $countArr = $feedbackOptionsManager->checkOptionsDependency($id);
        if ($countArr[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT_EDIT;
            die;
        }
        $foundArray = $feedbackOptionsManager->checkFeedBackOptions($REQUEST_DATA['optionLabel'],' AND answerSetOptionId !='.$REQUEST_DATA['answerSetOptionId'],$REQUEST_DATA['surveyId']);
        if(trim($foundArray[0]['optionLabel'])=='') {  //DUPLICATE CHECK
            $foundArray1 = $feedbackOptionsManager->checkFeedBackLabel($REQUEST_DATA['optionPoints'],'AND answerSetOptionId !='.$REQUEST_DATA['answerSetOptionId'],$REQUEST_DATA['surveyId']);
                if(trim($foundArray1[0]['optionPoints'])=='') {  //DUPLICATE CHECK
                $foundArray2 = $feedbackOptionsManager->checkFeedBackOrder($REQUEST_DATA['printOrder'],'AND answerSetOptionId !='.$REQUEST_DATA['answerSetOptionId'],$REQUEST_DATA['surveyId']);
                if(trim($foundArray2[0]['printOrder'])=='') {  //DUPLICATE CHECK
                        $returnStatus = $feedbackOptionsManager->editFeedBackOptions($id);
                        if($returnStatus === false) {
                        $errorMessage = FAILURE;
                        }
                    else {
                        echo SUCCESS;           
                        }
                 }
                    else {
                        echo FEEDBACK_ORDER_ALREADY_EXIST;         
                        }
                        }
                else {
                    echo FEEDBACK_OPTION_VALUE_ALREADY_EXIST;         
                }
        }
        else {
            echo FEEDBACK_OPTION_ALREADY_EXIST;    
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitFeedbackAdvOptionsEdit.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//made enhancements under feedback module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 1/20/10    Time: 5:05p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Resolved issues:0002615,0002635,0002600,0002601,0002614
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/14/10    Time: 6:22p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Resolved issue: 0002609,0002607,0002608,0002610,0002611,
//0002612,0002613
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:24a
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Fixed issue as list was not populating
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:19p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>