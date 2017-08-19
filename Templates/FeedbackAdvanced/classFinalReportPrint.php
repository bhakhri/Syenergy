<?php 
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 02-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    $teacherName=trim($REQUEST_DATA['teacherName']);
    $className=trim($REQUEST_DATA['className']);
    
    $search=" Time Table : ".$timeTableLabaleName." Label : ".$labelName." Class : ".$className." Teacher : ".$teacherName;

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
                        <tr><th colspan="3" '.$reportManager->getReportHeadingStyle().' align="center">Feedback Class Final Report (Advanced)<br/>'.$search.'</th></tr>
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

    
    $headerString='<table border="1" cellpadding="0" cellspacing="0" width="90%" class="reportTableBorder"  align="center">'.
                    $visibleFromToString
                    .'<tr>
                         <td class = "headingFont" align="left" width="2%"  rowspan="2">#</td>
                         <td class = "headingFont" align="left" width="20%" rowspan="2">Class</td>
                         <td class = "headingFont" align="left" width="8%" rowspan="2">Subject</td>
                         <td class = "headingFont" align="left" width="10%" rowspan="2">Teacher</td>
                         <td class = "headingFont" align="left" width="38%"  rowspan="2">Question</td>
                         <td class = "headingFont" align="center" width="15%" colspan="'.$maxLength.'">Options</td>
                         <td class = "headingFont" align="right" width="5%" rowspan="2" style="padding-right:3px;">GPA</td>
                   </tr>';
    
    $optionsArray=$fbMgr->getOptionsInformation($labelId,$classId1);
    
    $optionsCnt=count($optionsArray);
    $optionsString='';
    for($x=0;$x<$optionsCnt;$x++){
      $optionsString .='<td class="headingFont" align="right">'.$optionsArray[$x]['optionLabel'].'</td>';
    }                    
    $headerString =$headerString.'<tr>'.$optionsString.'</tr>';
    
    $tableString='<tr><td '.$reportManager->getReportDataStyle().' colspan="7" align="center">'.NO_DATA_FOUND.'</td></tr>';
    
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
                                   $tableString .='<tr>
                                                       <td '.$reportManager->getReportDataStyle().'>'.$z.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$className.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$subjectCode.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$employeeName.'</td>
                                                       <td '.$reportManager->getReportDataStyle().' align="left">'.$questionText.'</td>';
                               }
                              //code for options TD
                             $tableString .='<td '.$reportManager->getReportDataStyle().' align="right">'.$responceArray[$y]['answerCount'].'</td>'; 
                           }
                      }
                      if($tempQuestionId2!=$questionId){
                                 $tableString .='<td '.$reportManager->getReportDataStyle().' align="right">'.round($gpaArray[$n]['gpa'],2).'</td>';
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
                $tableString .='<tr><td '.$reportManager->getReportDataStyle().' align="right" style="padding-right:3px;" colspan="'.(6+$maxLength).'">GPA of '.$employeeName.' for '.$subjectCode.' : '.$teacherSubjectGPA.'</td></tr>'; 
                echo $reportHeader;
                echo $headerString.$tableString; 
                $tableString='';
                //generate footer part dynamically
                generateFooter($pageBreakFlag,$totalPages);
                $pageBreakFlag++;
                echo '<br class="page" />';
              }
             
        }
    }
    
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