<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE FeedBackCategory LIST
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feedBackCategoryId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $foundFeedBackCategoryArray=FeedBackManager::getInstance()->checkFeedBackCategory($REQUEST_DATA['feedBackCategoryId']);
    if ($foundFeedBackCategoryArray[0]['feedBackCategoryId'] !='') {
          echo DEPENDENCY_CONSTRAINT_EDIT;
          die;
    }
    $foundArray = FeedBackManager::getInstance()->getFeedBackCategory(' WHERE feedbackCategoryId="'.$REQUEST_DATA['feedBackCategoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetFeedBackCategoryValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Library/FeedBack
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeedBack
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>