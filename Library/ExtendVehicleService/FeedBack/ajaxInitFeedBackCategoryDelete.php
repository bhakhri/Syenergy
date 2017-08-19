<?php
//-------------------------------------------------------
// Purpose: To delete FeedBackCategory detail
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
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['feedBackCategoryId']) || trim($REQUEST_DATA['feedBackCategoryId']) == '') {
        $errorMessage = 'Invalid FeedBack Category';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        $FeedBackManager =  FeedBackManager::getInstance();
        $foundFeedBackCategoryArray = $FeedBackManager->checkFeedBackCategory($REQUEST_DATA['feedBackCategoryId']);
        if ($foundFeedBackCategoryArray[0]['feedBackCategoryId'] !='') {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }

        if($FeedBackManager->deleteFeedBackCategory($REQUEST_DATA['feedBackCategoryId']) ) {
            echo DELETE;
            die;
        }
       else {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackCategoryDelete.php $    
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Library/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
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

