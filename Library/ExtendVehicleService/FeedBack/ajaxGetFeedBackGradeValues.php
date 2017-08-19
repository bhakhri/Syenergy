<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBackGrades LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackManager.inc.php");
define('MODULE','FeedBackGradesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feedbackGradeId'] ) != '') {

   //Checking for use of gradeId in feedback response table
    if(FeedBackManager::getInstance()->checkFeedBackGradesUses($REQUEST_DATA['feedbackGradeId'])!=''){
     echo GRADE_CAN_NOT_MOD_DEL; //if this grade is already used
     exit();    
    } 

    $foundArray = FeedBackManager::getInstance()->getFeedBackGrades(' WHERE feedbackGradeId="'.$REQUEST_DATA['feedbackGradeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackGradeValues.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Done bug fixing.
//Bug ids---
//00001201,00001207,00001208,00001216
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
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