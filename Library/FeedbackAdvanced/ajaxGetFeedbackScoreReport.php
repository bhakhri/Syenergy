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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
$fbMgr=FeedBackReportAdvancedManager::getInstance();

    global $sessionHandler;   
    
    $valueArray = array();  
    $valueArrayPoint = array();  
    
    $sessionHandler->setSessionVariable('IdToFeedbackScoreReport',''); 
    $sessionHandler->setSessionVariable('IdToFeedbackPointReport','');    

    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $categoryId=trim($REQUEST_DATA['categoryId']);   
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($labelId=='') {
      $labelId=0;  
    }
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'questionName';
    
    $orderBy = " $sortField $sortOrderBy";


    $condition = " WHERE feedbackadv_survey.timeTableLabelId = '$timeTableLabelId' AND 
                      feedbackadv_survey.feedbackSurveyId = '$labelId' AND feedbackadv_survey_mapping.roleId = '4' ";
                      
    if($classId!='' && $classId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.classId = '$classId'";  
    }
    
    if($employeeId!='' && $employeeId!='all') {
      $condition .= " AND employee.employeeId = '$employeeId'";  
    }
    
    if($categoryId!='' && $categoryId!='all') {
      $condition .= " AND feedbackadv_survey_mapping.feedbackCategoryId = '$categoryId'";  
    }
    
 
    // FeedBack Point List
    $feedbackPointArray = $fbMgr->getFeedbackPointsList($condition);
    $pointData='';
    for($i=0;$i<count($feedbackPointArray);$i++) {               
      $point = $feedbackPointArray[$i]['optionPoints'];
      $pointData .= "<td width='4%' valign='middle' class='searchhead_text' align='right'><strong>$point</strong></td>";
      $valueArrayPoint[] = $feedbackPointArray[$i]['optionPoints']; 
    }
    
    
    // FeedBack Question List
    $feedbackQuestionRecordArray = $fbMgr->getFeedbackQuestionList($condition);
    $cnt = count($feedbackQuestionRecordArray);
    
    $tableData="<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                  <tr class='rowheading'>
                    <td width='2%'  valign='middle' class='searchhead_text' ><b>#</b></td>
                    <td width='58%' valign='middle' class='searchhead_text' align='left'><strong>Question</strong></td>
                    $pointData
                    <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Weight Average</strong></td>
                    <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Response</strong></td>
                  </tr>";

    // FeedBack Question List
    $avgTotal=0;
    $responseTotal=0;
    for($i=0; $i<$cnt;$i++) {   
        $valueArray[$i]['srNo']=($i+1);  
        $valueArray[$i]['questionName']=$feedbackQuestionRecordArray[$i]['feedbackQuestion'];  
                                             
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
        $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
                         <td valign='top' class='padding_top' align='left'>".$feedbackQuestionRecordArray[$i]['feedbackQuestion']."</td>";
        $questionId = $feedbackQuestionRecordArray[$i]['feedbackQuestionId'];                 
        $avg = 0;                
        $res = 0;
        for($j=0;$j<count($feedbackPointArray);$j++) {  
           $point = $feedbackPointArray[$j]['optionPoints'];   
           $cond = $condition." AND feedbackadv_questions.feedbackQuestionId = '$questionId' 
                                AND IFNULL(feedbackadv_answer_set_option.optionPoints,0) = '$point' "; 
           $feedbackScoreRecordArray = $fbMgr->getFeedbackScoreList($cond);
           $response=0;
           if(count($feedbackScoreRecordArray)>0) {
             $response=$feedbackScoreRecordArray[0]['response'];  
           }
           if($response=='') {
             $response=0;  
           }
           $pp = "p_".$j;      
           $valueArray[$i][$pp]=$response;
           
           $tableData .= "<td width='4%' valign='top' class='padding_top' align='right'>$response</td>";
           $avg += ($response*$point);
           $res += $response;
        }       
        $valueArray[$i]['avg']=$avg;
        $valueArray[$i]['response']=$res;  
        $tableData .= "<td width='4%' valign='top' class='padding_top' align='right'>".$avg."</td>
                       <td width='4%' valign='top' class='padding_top' align='right'>".$res."</td>";        
        $avgTotal += $avg;
        $responseTotal += $res;               
        $tableData .= "</tr>";                    
    }
    
    if($i!=0) { 
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
        $colspan = count($feedbackPointArray)+2;
        $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='right' colspan='$colspan'><b>Total</b></td>  
                         <td valign='top' class='padding_top' align='right'><b>".$avgTotal."</b></td>
                         <td valign='top' class='padding_top' align='right'><b>".$responseTotal."</b></td>
                       </tr>";
                       
        $pp = "p_".(count($feedbackPointArray)-1);  
        if( (count($feedbackPointArray)-1) >= 0 ) {    
          $valueArray[$i][$pp]="<b>Total</b>";
        }
        $valueArray[$i]['avg']="<b>$avgTotal</b>";
        $valueArray[$i]['response']="<b>$responseTotal</b>";
        $i++; 
        
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';
        $colspan = count($feedbackPointArray)+4;    
        if($responseTotal==0) {
          $score=0;  
        }
        else {
          $score= number_format((($avgTotal/$responseTotal)*2),3);
        }
        $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='right' colspan='$colspan'><b>Score&nbsp;:&nbsp; $score</b></td>
                       </tr>";
        
        $valueArray[$i]['avg']="<b>Score</b>";
        $valueArray[$i]['response']="<b>$score</b>";               
        
    }
        
        
    if($i==0) {
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='center' colspan='50'>No Record Found</td>  
                      </tr>";             
    }
    $tableData .= "</table>"; 
                                                                    
    $sessionHandler->setSessionVariable('IdToFeedbackScoreReport',$valueArray); 
    $sessionHandler->setSessionVariable('IdToFeedbackPointReport',$valueArrayPoint);      
    
    echo $tableData;
die;
?>