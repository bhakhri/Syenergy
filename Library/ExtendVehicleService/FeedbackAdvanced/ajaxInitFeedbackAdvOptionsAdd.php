<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A FEEDBACK GRADE
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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['surveyId']) || trim($REQUEST_DATA['surveyId']) == '') {
        $errorMessage .=  SELECT_ANSWERSET."\n"; 
    }

    $optionLabel = $REQUEST_DATA['optionLabel'];
    $optionPoints = $REQUEST_DATA['optionPoints'];
    $printOrder = $REQUEST_DATA['printOrder'];
    $surveyId = $REQUEST_DATA['surveyId'];
    $tIdNos = explode('!@~!~!@~!@~!',$tIdNos);
	$tIdNosArr = explode('!@~!~!@~!@~!',$tIdNos);
    $optionLabelArr = explode('!@~!~!@~!@~!',$optionLabel);
    $optionPointsArr = explode('!@~!~!@~!@~!',$optionPoints);
    $printOrderArr = explode('!@~!~!@~!@~!',$printOrder);
    $optionLabelCount = count($optionLabelArr);
    $optionPointsCount = count($optionPointsArr);
    $printOrderCount = count($printOrderArr);
    $optionLabelUniqueCount = count(array_unique($optionLabelArr));
    $optionPointsUniqueCount = count(array_unique($optionPointsArr));
    $printOrderUniqueCount = count(array_unique($printOrderArr));
    if($optionLabelCount != $optionLabelUniqueCount){
       echo "You can not enter same option text under one answer set ";
       die; 
    }
    if($optionPointsCount != $optionPointsUniqueCount){
       echo "You can not enter same option weight under one answer set ";
       die; 
    }
    if($printOrderCount != $printOrderUniqueCount){
       echo "You can not enter same print order for different options ";
       die; 
    }
    
    if (trim($errorMessage) == '') {
        $feedbackOptionsManager =  FeedbackOptionsManager::getInstance();
         $labelValue='';
            for ($i=0;$i<$optionLabelCount;$i++){
              if($labelValue!=''){
                  $labelValue .=' , ';
              }  
               $labelValue .='"'.add_slashes(trim($optionLabelArr[$i])).'"';
             }
              
             $optionValue='';
            for ($j=0;$j<$optionLabelCount;$j++){
              if($optionValue!=''){
                  $optionValue .=' , ';
              }  
               $optionValue .=add_slashes(trim($optionPointsArr[$j]));
             } 
             $orderValue='';
            for ($k=0;$k<$optionLabelCount;$k++){
              if($orderValue!=''){
                  $orderValue .=' , ';
              }  
               $orderValue .=add_slashes(trim($printOrderArr[$k])); 
             } 
        $foundArray = $feedbackOptionsManager->checkFeedBackOptions2(' WHERE answerSetId='.$REQUEST_DATA['surveyId'].' AND  UCASE(optionLabel) IN ('.$labelValue.')'); 
        if(trim($foundArray[0]['optionLabel'])=='') {  //DUPLICATE CHECK
            $foundArray1 = $feedbackOptionsManager->checkFeedBackLabel2(' WHERE answerSetId='.$REQUEST_DATA['surveyId'].' AND  UCASE(optionPoints) IN ('.$optionValue.')');
                if(trim($foundArray1[0]['optionPoints'])=='') {  //DUPLICATE CHECK
                 $foundArray2 = $feedbackOptionsManager->checkFeedBackOrder2(' WHERE answerSetId='.$REQUEST_DATA['surveyId'].' AND  UCASE(printOrder) IN ('.$orderValue.')');
                if(trim($foundArray2[0]['printOrder'])=='') {  //DUPLICATE CHECK
                    $value='';
                        for ($i=0;$i<$optionLabelCount;$i++){
                            if($value!=''){
                                $value .=' , ';
                             }  
                            $value .='( '.$surveyId.',"'. add_slashes(trim($optionLabelArr[$i])).'",'. add_slashes(trim($optionPointsArr[$i])).','.add_slashes(trim($printOrderArr[$i])).' )';
                        }    
                    $returnStatus = $feedbackOptionsManager->addFeedBackOptions($value);
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
                        echo SUCCESS;           
                    }
                  }
                else {
                    echo FEEDBACK_ORDER_ALREADY_EXIST;         
                }
                }
                else {
                    echo FEEDBACK_OPTION_VALUE_ALREADY_EXIST;         
                }
        }
        else {
           echo FEEDBACK_OPTION_ALREADY_EXIST; 
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitFeedbackAdvOptionsAdd.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//made enhancements under feedback module
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 1/20/10    Time: 5:05p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Resolved issues:0002615,0002635,0002600,0002601,0002614
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 1/14/10    Time: 6:22p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Resolved issue: 0002609,0002607,0002608,0002610,0002611,
//0002612,0002613
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:43a
//Updated in $/LeapCC/Library/FeedbackAdvanced
//resolved issue
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:19p
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>