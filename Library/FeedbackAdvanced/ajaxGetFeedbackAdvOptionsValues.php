<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBackOptions LIST
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
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['answerSetOptionId'] ) != '') {

   //Checking for use of gradeId in feedback response table
  /*  if(FeedbackOptionsManager::getInstance()->checkFeedBackOptionsUses($REQUEST_DATA['answerSetOptionId'])!=''){
     echo GRADE_CAN_NOT_MOD_DEL; //if this grade is already used
     exit();    
    } */

    $foundArray = FeedbackOptionsManager::getInstance()->getFeedBackOptions(' WHERE answerSetOptionId="'.$REQUEST_DATA['answerSetOptionId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedbackAdvOptionsValues.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:19p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>