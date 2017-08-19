<?php
//-------------------------------------------------------
// Purpose: To delete FeedBackOptions detail
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
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
if (!isset($REQUEST_DATA['answerSetOptionId']) || trim($REQUEST_DATA['answerSetOptionId']) == '') {
    $errorMessage = 'Invalid FeedBack Option';
}
/*
if(FeedbackOptionsManager::getInstance()->checkFeedBackOptionsUses($REQUEST_DATA['answerSetOptionId'])!=''){
  echo GRADE_CAN_NOT_MOD_DEL; //if this grade is already used
  exit();    
} */
    
    if (trim($errorMessage) == '') {
        $feedbackOptionsManager =  FeedbackOptionsManager::getInstance();
        $id  = $REQUEST_DATA['answerSetOptionId'];
        $countArr = $feedbackOptionsManager->checkOptionsDependency($id);
        if ($countArr[0]['cnt']!=0){
            echo DEPENDENCY_CONSTRAINT;
            die;
            }
        if($feedbackOptionsManager->deleteFeedBackOptions($id) ) {
               echo DELETE;
        }
      
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitFeedbackAdvOptionsDelete.php $    
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//made enhancements under feedback module
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:19p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>