<?php
//-------------------------------------------------------
// Purpose: To delete FeedBackGrades detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackManager.inc.php");
define('MODULE','FeedBackGradesMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
if (!isset($REQUEST_DATA['feedbackGradeId']) || trim($REQUEST_DATA['feedbackGradeId']) == '') {
    $errorMessage = 'Invalid FeedBack Grade';
}

if(FeedBackManager::getInstance()->checkFeedBackGradesUses($REQUEST_DATA['feedbackGradeId'])!=''){
  echo GRADE_CAN_NOT_MOD_DEL; //if this grade is already used
  exit();    
}
    
    if (trim($errorMessage) == '') {
        $feedBackManager =  FeedBackManager::getInstance();
        if($feedBackManager->deleteFeedBackGrades($REQUEST_DATA['feedbackGradeId']) ) {
               echo DELETE;
        }
       else {
               echo DEPENDENCY_CONSTRAINT;
       }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitFeedBackGradeDelete.php $    
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