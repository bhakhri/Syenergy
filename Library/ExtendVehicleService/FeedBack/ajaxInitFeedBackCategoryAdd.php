<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FeedBackCategory
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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .= ENTER_CATEGORY_NAME."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        $foundArray = FeedBackManager::getInstance()->getFeedBackCategory(' WHERE UCASE(TRIM(feedbackCategoryName))="'.add_slashes(trim(strtoupper($REQUEST_DATA['categoryName']))).'"');
        if(trim($foundArray[0]['feedbackCategoryName'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedBackManager::getInstance()->addFeedBackCategory();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo CATEGORY_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackCategoryAdd.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 1:31p
//Updated in $/LeapCC/Library/FeedBack
//Corrected add/edit problem
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