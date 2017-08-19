<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A FeedBack Label
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackManager.inc.php");
define('MODULE','CreateFeedBackLabels');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['labelName']) || trim($REQUEST_DATA['labelName']) == '') {
        $errorMessage .= ENTER_LABEL_NAME."\n";    
    }
    
    if (trim($errorMessage) == '') {
         /*if(trim($REQUEST_DATA['isActive'])==1){ */   
            
			$foundArray = FeedBackManager::getInstance()->getFeedBackLabel(' AND UCASE(feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'" AND feedbackSurveyId!='.$REQUEST_DATA['labelId']);

            if(trim($foundArray[0]['labelName'])=='') {  //DUPLICATE CHECK
                $returnStatus = FeedBackManager::getInstance()->editFeedBackLabel($REQUEST_DATA['labelId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
                else {
                      //$feedBackLabelId=$REQUEST_DATA['labelId'];   
                      //$activeLabelArray=FeedBackManager::getInstance()->makeAllFeedBackLabelInActive(" AND ffl.feedbackSurveyId !=".$feedBackLabelId); //make previous entries inactive
                      echo SUCCESS;           
                }
            }
            else {
                 echo LABEL_ALREADY_EXIST;
            }
        }
       /*else{
            $foundArray = FeedBackManager::getInstance()->getFeedBackLabel(' WHERE UCASE(feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'" AND feedbackSurveyId!='.$REQUEST_DATA['labelId']);
            if(trim($foundArray[0]['feedbackSurveyLabel'])=='') {  //DUPLICATE CHECK
                $returnStatus = FeedBackManager::getInstance()->editFeedBackLabel($REQUEST_DATA['labelId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
               else{
                   echo SUCCESS;
               } 
            }
            else {
                 echo LABEL_ALREADY_EXIST;
            }
            //check whether any time table label active or not.if not then do not update this label
            
            $activeCheckArray=FeedBackManager::getInstance()->getFeedBackLabel(' WHERE isActive=1 AND feedbackSurveyId!='.$REQUEST_DATA['labelId']);
            if(trim($activeCheckArray[0]['isActive'])!=''){  //if there is a active label other than this
            $foundArray = FeedBackManager::getInstance()->getFeedBackLabel(' WHERE UCASE(feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['labelName'])).'" AND feedbackSurveyId!='.$REQUEST_DATA['labelId']);
            if(trim($foundArray[0]['feedbackSurveyLabel'])=='') {  //DUPLICATE CHECK
                $returnStatus = FeedBackManager::getInstance()->editFeedBackLabel($REQUEST_DATA['labelId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
               else{
                   echo SUCCESS;
               } 
            }
            else {
                 echo LABEL_ALREADY_EXIST;
            }
          }   
         else { 
          echo ACTIVE_FEEDBACK_LABEL_UPDATE;
         }
         
       }*/  
    
    else {
        echo $errorMessage;
    } 
// $History: ajaxInitFeedBackLabelEdit.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Library/FeedBack
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Library/FeedBack
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 3  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified in feedback label & role wise graph
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:16p
//Updated in $/Leap/Source/Library/FeedBack
//modified for new fields
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/29/08   Time: 12:04p
//Updated in $/Leap/Source/Library/FeedBack
//Remove checking of "Active Label " Delete and Edit
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Library/FeedBack
//Created FeedBack Masters
?>