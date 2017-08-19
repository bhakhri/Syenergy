<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit A FeedBack Grades
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackManager.inc.php");
define('MODULE','FeedBackGradesMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();



    $errorMessage ='';
        if (!isset($REQUEST_DATA['gradeLabel']) || trim($REQUEST_DATA['gradeLabel']) == '') {
        $errorMessage .= ENTER_GRADE_LABEL."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['gradeValue']) || trim($REQUEST_DATA['gradeValue']) == '')) {
        $errorMessage .= ENTER_GRADE_VALUE."\n";  
    }

    if (trim($errorMessage) == '') {
        $foundArray = FeedBackManager::getInstance()->checkFeedBackGrades($REQUEST_DATA['gradeLabel'],' AND feedbackGradeId !='.$REQUEST_DATA['feedbackGradeId'],$REQUEST_DATA['surveyId']);
        if(trim($foundArray[0]['feedbackGradeLabel'])=='') {  //DUPLICATE CHECK
            $foundArray1 = FeedBackManager::getInstance()->checkFeedBackLabel($REQUEST_DATA['gradeValue'],'AND feedbackGradeId !='.$REQUEST_DATA['feedbackGradeId'],$REQUEST_DATA['surveyId']);
                if(trim($foundArray1[0]['feedbackGradeValue'])=='') {  //DUPLICATE CHECK
                    $returnStatus = FeedBackManager::getInstance()->editFeedBackGrades($REQUEST_DATA['feedbackGradeId']);
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
                        echo SUCCESS;           
                    }
                }
                else {
                    echo FEEDBACK_VALUE_ALREADY_EXIST;         
                }
        }
        else {
            echo FEEDBACK_GRADE_ALREADY_EXIST;    
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitFeedBackGradeEdit.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/08/09   Time: 12:56
//Updated in $/LeapCC/Library/FeedBack
//corrected access parameter
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/22/09    Time: 12:17p
//Updated in $/Leap/Source/Library/FeedBack
//modification in code for add & edit to check validation messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Library/FeedBack
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>