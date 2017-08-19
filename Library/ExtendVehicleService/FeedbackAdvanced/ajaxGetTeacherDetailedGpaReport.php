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
define('MODULE','ADVFB_TeacherDetailedGpaReport');
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
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
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
                         <td class="searchhead_text" align="left" width="50%">Teacher</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Total Points</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">Points Scored</td>
                         <td class="searchhead_text" align="right" style="padding-right:3px;">GPA</td>
                   </tr>';
    $tableString='<tr><td colspan="4" align="center">'.NO_DATA_FOUND.'<td></tr>';
    
    
    //first fetch no of categories associated with this label
    $mappedTeacherArray=$fbMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId);
    $teacherCnt=count($mappedTeacherArray);
    if(is_array($mappedTeacherArray) and $teacherCnt>0){
       $tableString =''; 
       for($i=0;$i<$teacherCnt;$i++){
        //fetch teacher information   
        $sumOfTotalPoints=0;
        $sumOfPointsScored=0;
        $sumOfGpa=0;
        $teacherName=$mappedTeacherArray[$i]['employeeName'];
        $teacherId=$mappedTeacherArray[$i]['employeeId'];
        $tableString .='<tr class="searchhead_text" bgcolor="#B7B7B7">
                         <td>'.($i+1).'</td>
                         <td class="padding_top" align="left" colspan="4">'.$teacherName.'</td></tr>';
        
        //fetch total points and points scored and GPAs for each employees
        //fetch mapped categories for teachers
        $categoryArray=$fbMgr->getMappedCategoriesForTeachers($labelId,$teacherId,$timeTableLabelId);
        $categoryCnt=count($categoryArray);
        $gpaScalingFactor=4;
        for($j=0;$j<$categoryCnt;$j++){
            $categoryId=$categoryArray[$j]['feedbackCategoryId'];
            
            //get the GPA Scaling Factor
            $gpaScalingFactorArray=$fbMgr->getGPAScalingFactor($labelId,$categoryId);
            if(is_array($gpaScalingFactorArray) and count($gpaScalingFactorArray)>0){
              $gpaScalingFactor=$gpaScalingFactorArray[0]['gpaScalingFactor'];  
            }
            
            $categoryName=$categoryArray[$j]['feedbackCategoryName'];
            $subjectType=$categoryArray[$i]['subjectTypeId'];
            
            $tableString .='<tr class="searchhead_text">
                         <td class="padding_top" align="left" colspan="5">Form Name : '.$categoryName.'</td></tr>';

            $tempClassId=-1;             
            //get class information
            $classArray=$fbMgr->getMappedClassesForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId);
            $classCnt=count($classArray);
            $categoryGPA=0;
            $subCnt=0;
            for($x=0;$x<$classCnt;$x++){
                $classId=$classArray[$x]['classId'];
                $className=$classArray[$x]['className'];
                if($tempClassId!=$classId){
                   $tableString .='<tr class="searchhead_text">
                         <td class="padding_top" align="left" colspan="5">Class : '.$className.'</td></tr>';
                   $tempClassId=$classId;
                }
                //get subject information             
                $subjectArray=$fbMgr->getMappedSubjectsForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId,$classId);
                $subjectCnt=count($subjectArray);
                for($k=0;$k<$subjectCnt;$k++){
                   $l++;
                   $subCnt++; 
                   $subjectId=$subjectArray[$k]['subjectId']; 
                   $subjectName=$subjectArray[$k]['subjectName'];
                   $subjectCode=$subjectArray[$k]['subjectCode'];
                   //get total points and points scored
                   $totalPointsArray=$fbMgr->getTotalPointsForSubjectCategoriesForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId,$subjectId,$classId);
                   $totalPoints=$totalPointsArray[0]['totalPoints'];
                   $pointsScoredArray=$fbMgr->getPointsScoredForSubjectCategoriesForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId,$subjectId,$classId);
                   $pointsScored=$pointsScoredArray[0]['pointsScored'];
                   if($totalPoints!=0){
                     $gpa=round(($pointsScored/$totalPoints)*$gpaScalingFactor,2);
                   }
                   else{
                     $gpa=0;
                   }
                   $categoryGPA +=$gpa;
                   $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                   $tableString .='<tr '.$bg.'>
                             <td class="padding_top" align="left">'.($k+1).'</td>
                             <td class="padding_top" align="left">'.$subjectName.' ( '.$subjectCode.' )</td>
                             <td class="padding_top" align="right" style="padding-right:3px;">'.$totalPoints.'</td>
                             <td class="padding_top" align="right" style="padding-right:3px;">'.$pointsScored.'</td>
                             <td class="padding_top" align="right" style="padding-right:3px;">'.$gpa.'</td>
                           </tr>';  
                }
          }
          $categoryGPAAvg=0;
          if($subCnt!=0){
             $categoryGPAAvg=round($categoryGPA/$subCnt,2);
          }
         $tableString .='<tr class="searchhead_text">
                         <td class="padding_top" align="right" colspan="5">GPA for   '.$categoryName.' : '.$categoryGPAAvg.'</td></tr>'; 
       }
      }
    }
   echo $headerString.$tableString; 
}
else{
    echo 'Required Parameters Missing';
    die;
}
// $History: ajaxGetTeacherDetailedGpaReport.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/08/10    Time: 6:08p
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Fixed Access Denied Bug
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