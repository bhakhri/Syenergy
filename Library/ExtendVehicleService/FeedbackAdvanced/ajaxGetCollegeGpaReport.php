<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_CollegeGpaReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();
    $visibleFromToString='';
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    
    $labelInfoArray=$fbMgr->getLabelDetails(' WHERE feedbackSurveyId='.$labelId);
    if(is_array($labelInfoArray) and count($labelInfoArray)>0){
        $visibleFromDate = UtilityManager::formatDate($labelInfoArray[0]['visibleFrom']);
        $visibleToDate   = UtilityManager::formatDate($labelInfoArray[0]['visibleTo']);
        //$visibleFromToString='<tr class="row0"><td colspan="4" class="searchhead_text" align="center">From '.$visibleFromDate.' to '.$visibleToDate.'</td></tr>';
    }
    
    $headerString='<table border="0" cellpadding="0" cellspacing="1" width="100%">'.
                    $visibleFromToString
                    .'<tr class="rowheading">
                         <td class="searchhead_text" align="left" width="2%">#</td>
                         <td class="searchhead_text" align="left" width="50%">Form Name</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Total Points</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Points Scored</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">GPA</td>
                   </tr>';
    $tableString='<tr><td colspan="4" align="center">'.NO_DATA_FOUND.'<td></tr>';
    
    
    //first fetch no of categories associated with this label
    $categoryArray=$fbMgr->getMappedCategories(' AND fsm.feedbackSurveyId='.$labelId);
    $categoryCnt=count($categoryArray);
    if(is_array($categoryArray) and $categoryCnt>0){
       $tableString =''; 
       //fetch total points and points scored and GPAs for each categories
       $sumOfTotalPoints=0;
       $sumOfPointsScored=0;
       $sumOfGpa=0;
       $gpaScalingFactor=4;
       for($i=0;$i<$categoryCnt;$i++){
        $totalPoints=0;
        $pointsScored=0;
        $gpa=0;
        $categoryId=$categoryArray[$i]['feedbackCategoryId'];
        
        //get the GPA Scaling Factor
        $gpaScalingFactorArray=$fbMgr->getGPAScalingFactor($labelId,$categoryId);
        if(is_array($gpaScalingFactorArray) and count($gpaScalingFactorArray)>0){
          $gpaScalingFactor=$gpaScalingFactorArray[0]['gpaScalingFactor'];  
        }
        
        $categoryName=$categoryArray[$i]['feedbackCategoryName'];
        $subjectType=$categoryArray[$i]['subjectTypeId'];
        $totalPointsArray=$fbMgr->getTotalPointsForCategories($labelId,$categoryId,$subjectType);
        $totalPoints=$totalPointsArray[0]['totalPoints'];
        $sumOfTotalPoints +=round($totalPoints,2);
        $pointsScoredArray=$fbMgr->getPointsScoredForCategories($labelId,$categoryId);
        $pointsScored=$pointsScoredArray[0]['pointsScored'];
        $sumOfPointsScored +=round($pointsScored,2);
        if($totalPoints!=0){
         $gpa=round(($pointsScored/$totalPoints)*$gpaScalingFactor,2);
        }
        else{
            $gpa=0;
        }
        $sumOfGpa +=round($gpa,2);
        
        $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
        $tableString .='<tr '.$bg.'>
                         <td class="padding_top" align="left">'.($i+1).'</td>
                         <td class="padding_top" align="left">'.$categoryName.'</td>
                         <td class="padding_top" align="right" style="padding-right:3px;">'.$totalPoints.'</td>
                         <td class="padding_top" align="right" style="padding-right:3px;">'.$pointsScored.'</td>
                         <td class="padding_top" align="right" style="padding-right:3px;">'.$gpa.'</td>
                       </tr>';  
       }
       $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
       $tableString .='<tr '.$bg.'>
                       <td colspan="2">&nbsp;</td>
                       <td class="padding_top" align="right" style="padding-right:3px;"><b>'.$sumOfTotalPoints.'</b></td>
                       <td class="padding_top" align="right" style="padding-right:3px;"><b>'.$sumOfPointsScored.'</b></td>
                       <td class="padding_top" align="right" style="padding-right:3px;"><b>'.$sumOfGpa.'</b></td>
                      </tr>';
    }
    echo $headerString.$tableString; 
}
else{
    echo 'Required Parameters Missing';
    die;
}
// $History: ajaxGetCollegeGpaReport.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified GPA calculation logic.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/02/10   Time: 15:24
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified function parameters
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/10   Time: 17:16
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created college gpa report for feedback modules
?>