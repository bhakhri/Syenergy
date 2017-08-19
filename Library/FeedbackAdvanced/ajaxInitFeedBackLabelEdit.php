<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A FeedBack Label
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
define('MODULE','ADVFB_Labels');
define('ACCESS','edit');

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
            $labelName=add_slashes(trim($REQUEST_DATA['feedbackSurveyLabel']));
            $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
            $startDate=trim($REQUEST_DATA['startDate']);
            $toDate=trim($REQUEST_DATA['toDate']);
            $extendDate=trim($REQUEST_DATA['extendDate']);
            $noOfAttempts=add_slashes(trim($REQUEST_DATA['noOfAttempts']));
            $isActive=trim($REQUEST_DATA['isActive']);
            $roleId=trim($REQUEST_DATA['roleId']);
            $labelId=trim($REQUEST_DATA['labelId']);

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

            if($labelId==''){
                echo ADV_LABEL_NOT_EXIST;
                die;
            }

            //check for usage of this label
            /*$usageArray= FeedbackLabelManager::getInstance()->checkFeedBackLabelUsage(' WHERE feedbackSurveyId='.$labelId);
            if($usageArray[0]['cnt']!=0){
                echo DEPENDENCY_CONSTRAINT_EDIT;
                die;
            }*/

            //check for usage in answer or survey mapping
            $usage = '';
            $labelValuesArray = FeedbackLabelManager::getInstance()->getFeedBackLabel(' AND ffl.feedbackSurveyId="'.$labelId.'"');
            $mappingArray = FeedbackLabelManager::getInstance()->checkFeedbackLabelInSurveyMapping('WHERE feedbackSurveyId='.$labelId);
            $answerArray = FeedbackLabelManager::getInstance()->checkFeedbackLabelInAnswer($labelId);
            $labelName2 = $labelValuesArray[0]['feedbackSurveyLabel'];
            $timeTableLabelId2 = $labelValuesArray[0]['timeTableLabelId'];
            $noOfAttempts2 = $labelValuesArray[0]['noOfAttempts'];
            $isActive2 = $labelValuesArray[0]['isActive'];
            $roleId2 = $labelValuesArray[0]['roleId'];
            if($mappingArray[0]['foundRecord']!=0 && $answerArray[0]['totalRecord']==0){
                $usage = 1;
                if($timeTableLabelId2 != $timeTableLabelId || $roleId2 != $roleId){
                    echo "You cannot change 'Time Table' and 'Feedback By' fields as this label has already been associated to users";
                    die;
                }
            }
            else if($mappingArray[0]['foundRecord']!=0 && $answerArray[0]['totalRecord']!=0){
                $usage = 2;
                if($timeTableLabelId2 != $timeTableLabelId || $roleId2 != $roleId || $labelName2 != strip_slashes($labelName) || $noOfAttempts2 != $noOfAttempts){
                    echo "You cannot change any field except 'Active','Visible From' and 'Visible To' as feedback has already been given by some users";
                    die;
                }
               $dateArray = FeedbackLabelManager::getInstance()->getMaxDate($labelId);
               $maxDate = $dateArray[0]['maxDate'];
               $maxDateTrim = substr($maxDate, 0, 10);
               $messageDate = UtilityManager::formatDate($maxDateTrim);
               $maxDateTrimTime = strtotime($maxDateTrim);
               $startDateTime = strtotime($startDate);
               $toDateTime = strtotime($toDate);
                if($startDateTime > $maxDateTrimTime){
                    echo "'Visible From' date can not be greater than ".$messageDate;
                    die;
                }
                else if($toDateTime < $maxDateTrimTime){
                    echo "'Visible To' date can not be less than ".$messageDate;
                    die;
                }
            }
            else{
                $usage = 3;
            }
            //label names canonot be duplicate for a particular time table
			$foundArray = FeedbackLabelManager::getInstance()->getFeedBackLabel(' AND ( UCASE(ffl.feedbackSurveyLabel)="'.add_slashes(strtoupper($REQUEST_DATA['feedbackSurveyLabel'])).'" AND ffl.timeTableLabelId='.$timeTableLabelId.' ) AND ffl.feedbackSurveyId!='.$labelId);
            if(trim($foundArray[0]['feedbackSurveyLabel'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedbackLabelManager::getInstance()->editFeedBackLabel($labelId,$labelName,$timeTableLabelId,$isActive,$startDate,$toDate,$noOfAttempts,$roleId,$usage,$extendDate);
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
// $History: ajaxInitFeedBackLabelEdit.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 3/04/10    Time: 12:37p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Label Master Modified : One new field added ("Extend To")
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 6:05p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//removed check that one can not update 'active' field whaen feedback has
//given
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/02/10    Time: 19:21
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002818,0002819,0002815
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/03/10    Time: 4:35p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Updated file to add edit/delete checks
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