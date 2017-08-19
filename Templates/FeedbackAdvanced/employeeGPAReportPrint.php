<?php 
//This file is used as printing version for attendance report.
//
// Author :Dipanjan Bhattacharjee
// Created on : 02-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    $timeTableLabaleName=trim($REQUEST_DATA['timeTableName']);
    $labelName=trim($REQUEST_DATA['labelName']);
    
    $search=" Time Table : ".$timeTableLabaleName." Label : ".$labelName;

    //generating report header and footer
     $reportHeader='
                    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
                        <tr>
                            <td align="left" colspan="1" width="25%" class="">'.$reportManager->showHeader().'</td>
                            <th align="center" colspan="1" width="50%" '.$reportManager->getReportTitleStyle().'>
                            '.$reportManager->getInstituteName().'</th>
                            <td align="right" colspan="1" width="25%" class="">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("d-M-y").'</td>
                                    </tr>
                                    <tr>
                                        <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right">Time :&nbsp;</td><td valign="" colspan="1" '. $reportManager->getDateTimeStyle().'>'.date("h:i:s A").'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><th colspan="3" '.$reportManager->getReportHeadingStyle().' align="center">Feedback Employee GPA Report (Advanced)<br/>'.$search.'</th></tr>
                        <tr><th colspan="3">&nbsp;</th></tr>
                        </table>';
                        
      $reportFooter='<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
                        <tr>
                            <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
                        </tr>
                        </table>'; 
    //generating report header and footer

function generateFooter($currentPage,$totalPage){
   global $reportManager; 
   echo  $reportFooter='<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
                        <tr>
                            <td align="left" colspan="7" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
                            <td align="right" colspan="1" '.$reportManager->getFooterStyle().'>Page '.$currentPage.' / '.$totalPage.'</td>
                        </tr>
                        </table>';
}    
    
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
    $maxLength=1;
    if(is_array($categoryArray) and $categoryCnt>0){
        $categoryIds=UtilityManager::makeCSList($categoryArray,'feedbackCategoryId');
        $maxOptionsArray=$fbMgr->getMaxOptionsLengthForCategories($categoryIds);
        $maxLength=$maxOptionsArray[0]['maxOptionLength'];
        if($maxLength==''){
            echo 'Options Information Missing';
            die;
        }
    }

    
    $headerString='<table border="1" cellpadding="0" cellspacing="0" width="90%" class="reportTableBorder"  align="center">'.
                    $visibleFromToString
                    .'<tr>
                         <td class = "headingFont" align="left" width="2%"  rowspan="2">#</td>
                         <td class = "headingFont" align="left" width="20%" rowspan="2">Category</td>
                         <td class = "headingFont" align="left" width="50%"  rowspan="2">Question</td>
                         <td class = "headingFont" align="center" width="15%" colspan="'.$maxLength.'">Options</td>
                         <td class = "headingFont" align="right" width="5%" rowspan="2" style="padding-right:3px;">GPA</td>
                   </tr>';
    
    $optionsArray=$fbMgr->getOptionsInformationForEmployees($labelId,$categoryIds);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      $optionsString .='<td class="headingFont" align="right">'.$optionsArray[$x]['optionLabel'].'</td>';
    }                    
    $headerString =$headerString.'<tr>'.$optionsString.'</tr>';
    
    $tableString='<tr><td '.$reportManager->getReportDataStyle().' colspan="5" align="center">'.NO_DATA_FOUND.'</td></tr>';
    
    if($categoryCnt>0){
        $tableString='';
    }
    else{
      echo $reportHeader;  
      echo $headerString.$tableString;
      echo $reportFooter;
      die;  
    }
    $z=0;
    $pageBreakFlag=1;
    $totalQuestions=0;
    
    $employeeArray=$fbMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId,'' );
    $employeeCnt=count($employeeArray);
    
    $pageCountArray=$fbMgr->getTotalPageCountForEmployees($labelId,$categoryIds);
    $totalPages=$pageCountArray[0]['totalPages'];
    if($totalPages!=0){
        $totalPages=ceil($totalPages*$employeeCnt/RECORDS_PER_PAGE);
    }
    
    
    if($employeeCnt==0){
        echo $reportHeader;
        echo $headerString;
        echo '<tr><td '.$reportManager->getReportDataStyle().' align="center" colspan="'.(5+$maxLength).'">'.NO_DATA_FOUND.'</td></tr>';
        echo $reportFooter;
        die;
    }
    for($w=0;$w<$employeeCnt;$w++){
     $employeeId=$employeeArray[$w]['employeeId'];
     $employeeName=$employeeArray[$w]['employeeName'];
     $overallGPAS=0;
     $tableString .='<tr><td '.$reportManager->getReportDataStyle().' align="left" style="padding-left:3px;" colspan="'.(5+$maxLength).'"><b>Employee : '.$employeeName.'</b></b></td></tr>'; 
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
                $questionText='';
                 for($n=0;$n<$questionIdCnt;$n++){
                     if($totalQuestions==RECORDS_PER_PAGE){
                         $totalQuestions=0;
                         echo $reportHeader;
                         echo $headerString.$tableString; 
                         $tableString='';
                         generateFooter($pageBreakFlag,$totalPages);
                         echo '<br class="page" />';
                         $pageBreakFlag++;
                     }
                     $totalQuestions++;
                        $tempQuestionId=-1;
                        $tempQuestionId2=-1;
                        $questionId=$questionIdArray[$n];
                        $questionText='';
                        $optionsTdString='';
                        for($y=0;$y<$responceCnt;$y++){
                           if($questionId==$responceArray[$y]['feedbackQuestionId']){
                               if($tempQuestionId!=$questionId){
                                   $z++;
                                   $tempQuestionId=$questionId;
                                   $questionText=$responceArray[$y]['feedbackQuestion'];
                                   $tableString .='<tr>
                                                       <td '.$reportManager->getReportDataStyle().'>'.$z.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$categoryName.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$questionText.'</td>';
                               }
                              //code for options TD
                             $tableString .='<td '.$reportManager->getReportDataStyle().' align="right">'.$responceArray[$y]['answerCount'].'</td>'; 
                           }
                      }
                      if($tempQuestionId2!=$questionId){
                                 $tableString .='<td '.$reportManager->getReportDataStyle().' align="right">'.round($gpaArray[$n]['gpa'],2).'</td>';
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
                $tableString .='<tr><td '.$reportManager->getReportDataStyle().' align="right" style="padding-right:3px;" colspan="'.(5+$maxLength).'"><b>GPA of '.$categoryName.' : '.$empGPA.'</b></td></tr>'; 
              
      }
      $tableString .='<tr><td '.$reportManager->getReportDataStyle().' align="right" style="padding-right:3px;" colspan="'.(5+$maxLength).'"><b>Overall GPA is : '.round(($overallGPAS/$categoryCnt),2).'</b></td></tr>';
    }
    echo $reportHeader;
    
    echo $headerString.$tableString; 
    $tableString='';
    //generate footer part dynamically
    generateFooter($pageBreakFlag,$totalPages);
    $pageBreakFlag++;
    echo '<br class="page" />';
    
   //echo $headerString.$tableString; 
}
else{
    echo 'Required Parameters Missing';
    die;
}

    
	

//$History: classFinalReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:55
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Class Final Report"  for advanced feedback modules.
?>