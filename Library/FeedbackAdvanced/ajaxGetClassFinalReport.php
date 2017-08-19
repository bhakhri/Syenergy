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
define('MODULE','COMMON');
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
    if(is_array($classArray) and $classCnt>0){
        $classIds=UtilityManager::makeCSList($classArray,'classId');
        $maxOptionsArray=$fbMgr->getMaxOptionsLength($classIds);
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
                         <td class="searchhead_text" align="left" width="20%" rowspan="2">Class</td>
                         <td class="searchhead_text" align="left" width="8%" rowspan="2">Subject</td>
                         <td class="searchhead_text" align="left" width="10%" rowspan="2">Teacher</td>
                         <td class="searchhead_text" align="left" width"38%"  rowspan="2">Question</td>
                         <td class="searchhead_text" align="center" width="15%" colspan="'.$maxLength.'">Options</td>
                         <td class="searchhead_text" align="right" width="5%" rowspan="2" style="padding-right:3px;">GPA</td>
                   </tr>';
    
    $optionsArray=$fbMgr->getOptionsInformation($labelId,$classId1);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      $optionsString .='<td class="searchhead_text" align="right">'.$optionsArray[$x]['optionLabel'].'</td>';
    }                    
    $headerString =$headerString.'<tr class="rowheading">'.$optionsString.'</tr>';
    
    $tableString='<tr><td colspan="7" align="center">'.NO_DATA_FOUND.'</td></tr>';
    
    if($classCnt>0){
        $tableString='';
    }
    $z=0;
    for($i=0;$i<$classCnt;$i++){
        $className=$classArray[$i]['className'];
        $classId=$classArray[$i]['classId'];
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
                                   $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                                   $z++;
                                   $tempQuestionId=$questionId;
                                   $questionText=$responceArray[$y]['feedbackQuestion'];
                                   $tableString .='<tr '.$bg.'>
                                                       <td>'.$z.'</td>
                                                       <td class="padding_top" align="left">'.$className.'</td>
                                                       <td class="padding_top" align="left">'.$subjectCode.'</td>
                                                       <td class="padding_top" align="left">'.$employeeName.'</td>
                                                       <td class="padding_top" align="left">'.$questionText.'</td>';
                               }
                              //code for options TD
                             $tableString .='<td class="padding_top" align="right">'.$responceArray[$y]['answerCount'].'</td>'; 
                           }
                      }
                      if($tempQuestionId2!=$questionId){
                                 $tableString .='<td class="padding_top" align="right">'.round($gpaArray[$n]['gpa'],2).'</td>';
                                 $tempQuestionId2=$questionId;
                                 $teacherPointsCount += round($gpaArray[$n]['points'],2);
                                 $teacherOptionCount += round($gpaArray[$n]['counts'],2);
                      }
                  }
                //code for teacher GPA of a subject
                $teacherSubjectGPA=0;
                if($teacherOptionCount>0){
                    $teacherSubjectGPA =round($teacherPointsCount/$teacherOptionCount,2);
                }
                $tableString .='<tr class="rowheading"><td class="searchhead_text" align="right" style="padding-right:3px;" colspan="'.(6+$maxLength).'">GPA of '.$employeeName.' for '.$subjectCode.' : '.$teacherSubjectGPA.'</td></tr>'; 
              }
             
        }
    }
    
   echo $headerString.$tableString; 
}
else{
    echo 'Required Parameters Missing';
    die;
}
// $History: ajaxGetClassFinalReport.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:54
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Created "Class Final Report"  for advanced feedback modules.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/02/10   Time: 10:17
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified Teacher Detailed GPA report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Modified GPA calculation logic.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/02/10   Time: 18:44
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Teacher Detaile GPA Report"
?>