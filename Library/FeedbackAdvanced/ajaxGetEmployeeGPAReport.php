<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_EmployeeGPAReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$tempClassId=-1;
$l=0;
if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();
    $visibleFromToString='';
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $categoryId=trim($REQUEST_DATA['categoryId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
    $labelInfoArray=$fbMgr->getLabelDetails(' WHERE feedbackSurveyId='.$labelId);
    if(is_array($labelInfoArray) and count($labelInfoArray)>0){
        $visibleFromDate = UtilityManager::formatDate($labelInfoArray[0]['visibleFrom']);
        $visibleToDate   = UtilityManager::formatDate($labelInfoArray[0]['visibleTo']);
        //$visibleFromToString='<tr class="row0"><td colspan="4" class="searchhead_text" align="center">From '.$visibleFromDate.' to '.$visibleToDate.'</td></tr>';
    }
    
    //********FETCH MAXIMUM OPTIONS LENGTH********
    //$categoryArray=$fbMgr->getSelectedTimeTableLabelCategories($timeTableLabelId,$labelId,$categoryId);
    $categoryArray=$fbMgr->getCategory($labelId,$employeeId,$timeTableLabelId,$categoryId);
    
    $categoryCnt=count($categoryArray);
    if(is_array($categoryArray) and $categoryCnt>0){
        $categoryIds=UtilityManager::makeCSList($categoryArray,'feedbackCategoryId');
        $maxOptionsArray=$fbMgr->getMaxOptionsLengthForCategories($categoryIds);
        $maxLength=$maxOptionsArray[0]['maxOptionLength'];
        if($maxLength==''){
            echo 'Options Information Missing';
            die;
        }
    }

    
    $headerString='<table border="0" cellpadding="0" cellspacing="1" width="100%">'.
                    $visibleFromToString
                    .'<tr class="rowheading">
                         <td class="searchhead_text" align="left" width="2%"  rowspan="2">#</td>
                         <td class="searchhead_text" align="left" width="20%" rowspan="2">Category</td>
                         <td class="searchhead_text" align="left" width"38%"  rowspan="2">Question</td>
                         <td class="searchhead_text" align="center" width="15%" colspan="'.$maxLength.'">Options</td>
                         <td class="searchhead_text" align="right" width="5%" rowspan="2" style="padding-right:3px;">GPA</td>
                   </tr>';
    
    $optionsArray=$fbMgr->getOptionsInformationForEmployees($labelId,$categoryIds);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      $optionsString .='<td class="searchhead_text" align="right">'.$optionsArray[$x]['optionLabel'].'</td>';
    }                    
    $headerString =$headerString.'<tr class="rowheading">'.$optionsString.'</tr>';
    
    $tableString='<tr><td colspan="5" align="center">'.NO_DATA_FOUND.'</td></tr>';
    
    if($categoryCnt>0){
        $tableString='';
    }
    $z=0;
    
    $employeeArray=$fbMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId,'' );
    $employeeCnt=count($employeeArray);
    if($employeeCnt==0){
        echo $headerString;
        echo '<tr class="row0"><td class="padding_top" align="center" colspan="'.(5+$maxLength).'">'.NO_DATA_FOUND.'</td></tr>';
        die;
    }
    for($w=0;$w<$employeeCnt;$w++){
     $employeeId=$employeeArray[$w]['employeeId'];
     $employeeName=$employeeArray[$w]['employeeName'];
     $overallGPAS=0;
     
     $tableString .='<tr class="rowheading"><td class="searchhead_text" align="left" style="padding-left:3px;" colspan="'.(5+$maxLength).'">Employee : '.$employeeName.'</td></tr>';
     for($i=0;$i<$categoryCnt;$i++){
        $genCategoryId=$categoryArray[$i]['feedbackCategoryId'];
        $categoryName=$categoryArray[$i]['feedbackCategoryName'];
        
        $empOptionCount=0;
        $empPointsCount=0;

        //no need of time table label id as one survey label is associated with one time table label only
        $responceArray=$fbMgr->getEmployeeResponseCount($labelId,$genCategoryId,$employeeId);
        $gpaArray=$fbMgr->getEmployeeQuestionWiseGPA($labelId,$genCategoryId,$employeeId);
        
        $responceCnt=count($responceArray);
        $questionIdArray=array_values(array_unique(explode(',',UtilityManager::makeCSList($responceArray,'feedbackQuestionId'))));
        //***sorts the array accoring to feedbackQuestionId***
        sort($questionIdArray);
        $questionIdCnt=count($questionIdArray);
        $tempQuestionId=-1;
        $tempQuestionId2=-1;
        $genCategoryId2=-1;
        $questionText='';
         for($n=0;$n<$questionIdCnt;$n++){
              /* For Not Showing Repeating Category Names;
              if($genCategoryId2!=$responceArray[$y]['feedbackCategoryId']){
               $categoryName=$categoryArray[$i]['feedbackCategoryName'];
               $genCategoryId2=$responceArray[$y]['feedbackCategoryId'];
              }
              else{
               $categoryName='';  
              }
              */
                $tempQuestionId=-1;
                $tempQuestionId2=-1;
                $questionId=$questionIdArray[$n];
                $questionText='';
                $optionsTdString='';
                for($y=0;$y<$responceCnt;$y++){
                   if($questionId==$responceArray[$y]['feedbackQuestionId']){
                       if($tempQuestionId!=$questionId){
                           $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                           $z++;
                           $tempQuestionId=$questionId;
                           $questionText=$responceArray[$y]['feedbackQuestion'];
                           $tableString .='<tr '.$bg.'>
                                               <td>'.$z.'</td>
                                               <td class="padding_top" align="left">'.$categoryName.'</td>
                                               <td class="padding_top" align="left">'.$questionText.'</td>';
                       }
                      //code for options TD
                     $tableString .='<td class="padding_top" align="right">'.$responceArray[$y]['answerCount'].'</td>'; 
                   }
              }
              if($tempQuestionId2!=$questionId){
                         $tableString .='<td class="padding_top" align="right">'.round($gpaArray[$n]['gpa'],2).'</td>';
                         $tempQuestionId2=$questionId;
                         $empPointsCount += round($gpaArray[$n]['points'],2);
                         $empOptionCount += round($gpaArray[$n]['counts'],2);
              }
          }
        //code for teacher GPA of a subject
        $empGPA=0;
        if($empOptionCount>0){
            $empGPA =round($empPointsCount/$empOptionCount,2);
            $overallGPAS +=$empGPA;
        }
        $tableString .='<tr class="rowheading"><td class="searchhead_text" align="right" style="padding-right:3px;" colspan="'.(5+$maxLength).'">GPA of '.$categoryName.' : '.$empGPA.'</td></tr>'; 
    }
   
   $tableString .='<tr class="rowheading"><td class="searchhead_text" align="right" style="padding-right:3px;" colspan="'.(5+$maxLength).'">Overall GPA is : '.round(($overallGPAS/$categoryCnt),2).'</td></tr>';
  }
  echo $headerString.$tableString; 
}
else{
    echo 'Required Parameters Missing';
    die;
}
// $History: ajaxGetClassFinalReport.php $
?>