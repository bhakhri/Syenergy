<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    //to parse csv values    
    function parseCSVComments($comments) {
      $comments = str_replace('"', '""', $comments);
      $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
        return '"'.$comments.'"'; 
      } 
      else {
        return $comments; 
      }
    }

$recordArray=array();

$tempClassId=-1;
$l=0;

$csvData = "#, Category, Question, Options, GPA \n";
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

    
    $optionsArray=$fbMgr->getOptionsInformationForEmployees($labelId,$categoryIds);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      if($optionsString!=''){
          $optionsString .=', ';
      }  
      $optionsString .=$optionsArray[$x]['optionLabel'];
    }                    
    
    if($categoryCnt>0){
        $tableString='';
    }
    else{
      ob_end_clean();
      header("Cache-Control: public, must-revalidate");
      header('Content-type: application/octet-stream; charset=utf-8');
      header("Content-Length: " .strlen($csvData) );
      header('Content-Disposition: attachment;  filename="employeeGPAReport.csv"');
      header("Content-Transfer-Encoding: binary\n");
      echo $csvData;  
      die;  
    }
    
    $employeeArray=$fbMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId,'' );
    $employeeCnt=count($employeeArray);
    
    $z=0;
    $pageBreakFlag=1;
    for($w=0;$w<$employeeCnt;$w++){
     $employeeId=$employeeArray[$w]['employeeId'];
     $employeeName=$employeeArray[$w]['employeeName'];
     $overallGPAS=0;
     $tableString .=str_repeat(", ",(2+$maxLength)).', Employee : '.parseCSVComments($employeeName)."\n"; 
     for($i=0;$i<$categoryCnt;$i++){
        $genCategoryId=$categoryArray[$i]['feedbackCategoryId'];
        $categoryName=$categoryArray[$i]['feedbackCategoryName'];
        
        $empOptionCount=0;
        $empPointsCount=0;
        
        //$pageCountArray=$fbMgr->getTotalPageCount($timeTableLabelId,$labelId,$classId,$employeeId2);
        //$totalPages=$pageCountArray[0]['totalPages'];
        
                
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
                                   $tableString .=$z.', '.parseCSVComments($categoryName).','.parseCSVComments($questionText);
                               }
                              //code for options TD
                             $tableString .=', '.parseCSVComments($responceArray[$y]['answerCount']); 
                           }
                      }
                      if($tempQuestionId2!=$questionId){
                                 $tableString .=', '.parseCSVComments(round($gpaArray[$n]['gpa'],2));
                                 $tempQuestionId2=$questionId;
                                 $empPointsCount += round($gpaArray[$n]['points'],2);
                                 $empOptionCount += round($gpaArray[$n]['counts'],2);
                      }
                     $tableString .= " \n"; 
                  }
                //code for teacher GPA of a subject
                $empGPA=0;
                if($empOptionCount>0){
                    $empGPA =round($empPointsCount/$empOptionCount,2);
                    $overallGPAS +=$empGPA;
                }
                $tableString .=str_repeat(", ",(2+$maxLength)).', GPA of '.$categoryName.' : '.parseCSVComments($empGPA); 
                $tableString .= " \n";
     }
    $tableString .=str_repeat(", ",(2+$maxLength)).', Overall GPA is : '.parseCSVComments($overallGPAS/$categoryCnt); 
   }
    
   //echo $headerString.$tableString; 
}

    $csvData = "#, Category, Question, Options,".str_repeat(", ",$maxLength-1)." GPA \n";
    $csvData .= str_repeat(", ",3).$optionsString." , \n";
    $csvData =$csvData.$tableString;

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="employeeGPAReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: classFinalReportCSV.php $
//
?>