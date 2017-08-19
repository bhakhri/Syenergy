<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FEEDBACK GRADE
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackGradesMaster');
define('ACCESS','add');
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
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        $foundArray = FeedBackManager::getInstance()->checkFeedBackGrades($REQUEST_DATA['gradeLabel'],' ',$REQUEST_DATA['surveyId']);
        if(trim($foundArray[0]['feedbackGradeLabel'])=='') {  //DUPLICATE CHECK
            $foundArray1 = FeedBackManager::getInstance()->checkFeedBackLabel($REQUEST_DATA['gradeValue'],' ',$REQUEST_DATA['surveyId']);
                if(trim($foundArray1[0]['feedbackGradeValue'])=='') {  //DUPLICATE CHECK
                    $returnStatus = FeedBackManager::getInstance()->addFeedBackGrades();
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

// $History: ajaxInitFeedBackGradeAdd.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/22/09    Time: 6:33p
//Updated in $/Leap/Source/Library/FeedBack
//remove uncomment from add
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