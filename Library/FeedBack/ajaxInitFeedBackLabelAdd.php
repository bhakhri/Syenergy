<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FeedBack
// Author : Dipanjan Bhattacharjee
// Created on : (13.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateFeedBackLabels');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['labelName']) || trim($REQUEST_DATA['labelName']) == '') {
        $errorMessage .= ENTER_LABEL_NAME."\n";    
    }


    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackManager.inc.php");
        require_once(DA_PATH . '/SystemDatabaseManager.inc.php');  
        $foundArray = FeedBackManager::getInstance()->getFeedBackLabel(' AND UCASE(feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'"');
        if(trim($foundArray[0]['feedbackSurveyLabel'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedBackManager::getInstance()->addFeedBackLabel();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                /*if(trim($REQUEST_DATA['isActive'])==1){
                   $feedBackLabelId=SystemDatabaseManager::getInstance()->lastInsertId();   
                   $activeLabelArray=FeedBackManager::getInstance()->makeAllFeedBackLabelInActive(" AND ffl.feedbackSurveyId !=".$feedBackLabelId); //make previous entries inactive
                }*/
               echo SUCCESS;           
            }
        }
        else {
            echo LABEL_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackLabelAdd.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Library/FeedBack
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 3  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified code for insert new fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>