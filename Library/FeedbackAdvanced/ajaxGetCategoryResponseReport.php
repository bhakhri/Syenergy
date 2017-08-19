<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (15.02.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
ini_set('MEMORY_LIMIT','300M');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherCategoryResponseReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbFinalMgr=FeedBackReportAdvancedManager::getInstance();
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $catId=trim($REQUEST_DATA['catId']);
    
    $headerString='<table border="0" cellpadding="0" cellspacing="1" width="100%">
                        <tr class="rowheading">
                         <td class="searchhead_text" align="left" width="2%">#</td>
                         <td class="searchhead_text" align="left" width="10%">Category</td>
                         <td class="searchhead_text" align="left" width="50%">Feedback Description</td>
                         <td class="searchhead_text" align="left">Feedback Response</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Response Count</td>
                    </tr>';
     //$tableString='<tr><td colspan="4" align="center">'.NO_DATA_FOUND.'<td></tr>';
    
     $foundArray2=$fbFinalMgr->getAllCategories($labelId,' feedbackCategoryName',$catId);
     $countFoundArray2=count($foundArray2);
     $catId2=-1;
     $x=0;
     
     if($countFoundArray2==0){
       $tableString='<tr><td colspan="5" align="center">'.NO_DATA_FOUND.'<td></tr>';  
       echo $headerString.$tableString.'</table>';
       die;
     }
     $gpaCnt=1;
     $overallGpa=0;
     for($k=0;$k<$countFoundArray2;$k++){
         $catId2=$foundArray2[$k]['feedbackCategoryId'];    
         $catName=$foundArray2[$k]['feedbackCategoryName'];
         $finalInfoArray=$fbFinalMgr->getCategoryResponseCount($catId2,$labelId);
         $finalCnt=count($finalInfoArray);
         $questionId=-1;
         $tempCatName='';
         $tempCatId=-1;
         if($finalCnt==0){
           $tableString .='<tr '.$bg.'>
                          <td class="padding_top" align="center" colspan="5">'.NO_DATA_FOUND.' for '.$catName.' category</td>
                         </tr>';
         }
         for($j=0;$j<$finalCnt;$j++){
          if($tempCatId!=$finalInfoArray[$j]['feedbackCategoryId']){
             $tempCatName=$catName; 
             $tempCatId=$finalInfoArray[$j]['feedbackCategoryId'];
             $x++;
             $temp=$x;
             //$questionId=-1;
          }
          else{
             $tempCatName=''; 
             $x=$x;
             $temp='';
          }   
          if($questionId!=$finalInfoArray[$j]['feedbackQuestionId']){
             if($questionId!=-1){
              if($totalAnswerCount>0){    
               $gpa=round($totalPoints/$totalAnswerCount,2);
              }
              else{
               $gpa=0;   
              }
              $overallGpa +=$gpa;
              $gpaCnt++; 
              $tableString .='<tr '.$bg.'>
                          <td class="padding_top" colspan="6" align="right" style="padding-right:3px;"><b>GPA : '.$gpa.'</b></td>
                         </tr>';
             } 
             $totalAnswerCount=0;
             $totalPoints=0;
             $question=$finalInfoArray[$j]['feedbackQuestion'];
             $questionId=$finalInfoArray[$j]['feedbackQuestionId'];
          }
          else{
             $question='';
          }
          $option=$finalInfoArray[$j]['optionLabel'];
          $optionId=$finalInfoArray[$j]['answerSetOptionId'];
          $answerCount=$finalInfoArray[$j]['responseCount'];
          $points=$finalInfoArray[$j]['points'];
          
          $totalAnswerCount +=$answerCount;
          $totalPoints +=$points;
          
          $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
          $tableString .='<tr '.$bg.'>
                         <td class="padding_top" align="left">'.$temp.'</td>
                         <td class="padding_top" align="left">'.$tempCatName.'</td>
                         <td class="padding_top" align="left">'.$question.'</td>
                         <td class="padding_top" align="left" >'.$option.'</td>
                         <td class="padding_top" align="right" style="padding-right:3px;">'.$answerCount.'</td>
                         </tr>';  
          }
          
         if($gpaCnt>0){
           $oGpa=round($overallGpa/$gpaCnt,2);  
         }
         else{
          $oGpa=0;
         }
           $tableString .='<tr '.$bg.'>
                      <td class="padding_top" colspan="6" align="right" style="padding-right:3px;"><b>Overall GPA : '.$oGpa.'</b></td>
                     </tr>';
           $overallGpa=0;
           $gpaCnt=1;
      }
     echo $headerString.$tableString;
}
else{
    echo 'Required Parameters Missing';
    die;
}
  
// $History: ajaxGetTeacherFinalReport.php $
?>