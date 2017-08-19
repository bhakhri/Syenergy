<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FeedBack
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['feedbackSurveyLabel']) || trim($REQUEST_DATA['feedbackSurveyLabel']) == '') {
        $errorMessage .= ENTER_LABEL_NAME."\n";    
    }
    if (!isset($REQUEST_DATA['timeTableLabelId']) || trim($REQUEST_DATA['timeTableLabelId']) == '') {
        $errorMessage .= SELECT_TIME_TABLE."\n";    
    }
    if (!isset($REQUEST_DATA['noOfAttempts']) || trim($REQUEST_DATA['noOfAttempts']) == '') {
        $errorMessage .= ADV_ENTER_ATTEMPTS."\n";    
    }
    
      
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
        
        $labelName=add_slashes(trim($REQUEST_DATA['feedbackSurveyLabel']));
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        $startDate=trim($REQUEST_DATA['startDate']);
        $toDate=trim($REQUEST_DATA['toDate']);
        $extendDate=trim($REQUEST_DATA['extendDate']);
        $noOfAttempts=add_slashes(trim($REQUEST_DATA['noOfAttempts']));
        $isActive=trim($REQUEST_DATA['isActive']);
        $roleId=trim($REQUEST_DATA['roleId']);
        
        if($roleId!=1 and $roleId!=2 and $roleId!=3){
            echo 'Invalid Role';
            die;
        }
        if($roleId==1){ //for teachers
            $roleId=2;
        }
        else if($roleId==2){ //for parents
            $roleId=3;
        }
        else if($roleId==3){ //for students
            $roleId=4;
        }
        
        //label names canonot be duplicate for a particular time table
        $foundArray = FeedbackLabelManager::getInstance()->getFeedBackLabel(' AND UCASE(ffl.feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['feedbackSurveyLabel'])).'" AND ffl.timeTableLabelId='.$timeTableLabelId);
        if(trim($foundArray[0]['feedbackSurveyLabel'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedbackLabelManager::getInstance()->addFeedBackLabel($labelName,$timeTableLabelId,$isActive,$startDate,$toDate,$noOfAttempts,$roleId,$extendDate);
            if($returnStatus == false){
                echo FAILURE;
                die;
            }
            else{
                echo SUCCESS;
                die;
            }           
        }
        else {
            echo ADV_LABEL_ALREADY_EXIST;
            die;
        }                                                           
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitFeedBackLabelAdd.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/04/10    Time: 12:37p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Label Master Modified : One new field added ("Extend To")
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/02/10    Time: 19:21
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002818,0002819,0002815
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 2:42p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//made updations under feedback module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:03
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified "Feedback Label Master(Advanced)" as two new fields are added
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:10p
//Created in $/LeapCC/Library/FeedbackAdvanced
//created file under feedback advanced label module
//
?>