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
if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();
    $visibleFromToString='';
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId1=trim($REQUEST_DATA['classId']);
    $employeeId2=trim($REQUEST_DATA['employeeId']);
    
    $labelInfoArray=$fbMgr->getLabelDetails(' WHERE feedbackSurveyId='.$labelId);
    if(is_array($labelInfoArray) and count($labelInfoArray)>0){
        $visibleFromDate = UtilityManager::formatDate($labelInfoArray[0]['visibleFrom']);
        $visibleToDate   = UtilityManager::formatDate($labelInfoArray[0]['visibleTo']);
        //$visibleFromToString='<tr class="row0"><td colspan="4" class="searchhead_text" align="center">From '.$visibleFromDate.' to '.$visibleToDate.'</td></tr>';
    }
    
    //********FETCH MAXIMUM OPTIONS LENGTH********
    $classArray=$fbMgr->getClassFromAnswerTable($timeTableLabelId,$labelId,$classId1);
    $classCnt=count($classArray);
    $maxLength=1;
    if(is_array($classArray) and $classCnt>0){
        $classIds=UtilityManager::makeCSList($classArray,'classId');
        $maxOptionsArray=$fbMgr->getMaxOptionsLength($classIds);
        $maxLength=$maxOptionsArray[0]['maxOptionLength'];
        if($maxLength==''){
            echo 'Options Information Missing';
            die;
        }
    }

    
    $optionsArray=$fbMgr->getOptionsInformation($labelId,$classId1);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      if($optionsString!=''){
          $optionsString .=', ';
      }  
      $optionsString .=$optionsArray[$x]['optionLabel'];
    }                    
    
    if($classCnt>0){
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
    for($i=0;$i<$classCnt;$i++){
        $className=$classArray[$i]['className'];
        $classId=$classArray[$i]['classId'];
        
        $pageCountArray=$fbMgr->getTotalPageCount($timeTableLabelId,$labelId,$classId,$employeeId2);
        $totalPages=$pageCountArray[0]['totalPages'];
        
        //fetch subject information
        $subjectArray=$fbMgr->getSubjectsFromAnswerTable($timeTableLabelId,$labelId,$classId,$employeeId2);
        $subjectCnt=count($subjectArray);
        for($j=0;$j<$subjectCnt;$j++){
            $subjectId   = $subjectArray[$j]['subjectId'];
            $subjectCode = $subjectArray[$j]['subjectCode'];
            $subjectName = $subjectArray[$j]['subjectName'];
            //fetch employee information
            $employeeArray=$fbMgr->getActualEmplyeesFromAnswerTable($timeTableLabelId,$labelId,$classId,$subjectId,$employeeId2);
            $employeeCnt=count($employeeArray);
            for($k=0;$k<$employeeCnt;$k++){
                $teacherOptionCount=0;
                $teacherPointsCount=0;
                $employeeId=$employeeArray[$k]['employeeId'];
                $employeeName=$employeeArray[$k]['employeeName'];
                $employeeCode=$employeeArray[$k]['employeeCode'];
                //no need of time table label id as one survey label is associated with one time table label only
                $responceArray=$fbMgr->getTeacherResponseCount($labelId,$classId,$subjectId,$employeeId);
                $gpaArray=$fbMgr->getTeacherQuestionWiseGPA($labelId,$classId,$subjectId,$employeeId);
                
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
                                   $tableString .=$z.', '.parseCSVComments($className).', '.parseCSVComments($subjectCode).', '.parseCSVComments($employeeName).','.parseCSVComments($questionText);
                               }
                              //code for options TD
                             $tableString .=', '.parseCSVComments($responceArray[$y]['answerCount']); 
                           }
                      }
                      if($tempQuestionId2!=$questionId){
                                 $tableString .=', '.parseCSVComments(round($gpaArray[$n]['gpa'],2));
                                 $tempQuestionId2=$questionId;
                                 $teacherPointsCount += round($gpaArray[$n]['points'],2);
                                 $teacherOptionCount += round($gpaArray[$n]['counts'],2);
                      }
                     $tableString .= " \n"; 
                  }
                //code for teacher GPA of a subject
                $teacherSubjectGPA=0;
                if($teacherOptionCount>0){
                    $teacherSubjectGPA =round($teacherPointsCount/$teacherOptionCount,2);
                }
                $tableString .=str_repeat(", ",(4+$maxLength)).', GPA of '.$employeeName.' for '.$subjectCode.' : '.parseCSVComments($teacherSubjectGPA); 
                $tableString .= " \n";
              }
             
        }
    }
    
   //echo $headerString.$tableString; 
}

	$csvData = '';
    $csvData .= "#, Class, Subject, Teacher, Question, Options,".str_repeat(", ",$maxLength-1)." GPA \n";
    $csvData .= str_repeat(", ",5).$optionsString." , \n";
    $csvData =$csvData.$tableString;

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="classFinalReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: classFinalReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:55
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Class Final Report"  for advanced feedback modules.
?>