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

  $l=0;
  $m=0;
  $tempClassId=-1;
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
        $visibleFromToString='From '.$visibleFromDate.' to '.$visibleToDate;
    }
    
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
        
        //fetch total points and points scored and GPAs for each employees
        //fetch mapped categories for teachers
        $categoryArray=$fbMgr->getMappedCategoriesForTeachers($labelId,$teacherId,$timeTableLabelId);
        $categoryCnt=count($categoryArray);
        $gpaScalingFactor=4;
        
        for($j=0;$j<$categoryCnt;$j++){
            $totalPoints=0;
            $pointsScored=0;
            $gpa=0;
            $categoryId=$categoryArray[$j]['feedbackCategoryId'];
            
            //get the GPA Scaling Factor
            $gpaScalingFactorArray=$fbMgr->getGPAScalingFactor($labelId,$categoryId);
            if(is_array($gpaScalingFactorArray) and count($gpaScalingFactorArray)>0){
              $gpaScalingFactor=$gpaScalingFactorArray[0]['gpaScalingFactor'];  
            }
            
            $categoryName=$categoryArray[$j]['feedbackCategoryName'];
            $subjectType=$categoryArray[$i]['subjectTypeId'];
            $tempClassId=-1;
            
            //get class information
            $classArray=$fbMgr->getMappedClassesForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId);
            $classCnt=count($classArray);
            $categoryGPA=0;
            $subCnt=0;
            for($x=0;$x<$classCnt;$x++){
                $classId=$classArray[$x]['classId'];
                $className=$classArray[$x]['className'];
                
                //get subject information             
                $subjectArray=$fbMgr->getMappedSubjectsForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId,$classId);
                $subjectCnt=count($subjectArray);
                for($k=0;$k<$subjectCnt;$k++){
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
                   $recordArray[$l]['srNo']=($m+1);
                   $recordArray[$l]['teacherName']=$teacherName;
                   $recordArray[$l]['categoryName']=$categoryName;
                   $recordArray[$l]['className']=$className;
                   $recordArray[$l]['subjectName']=$subjectName.' ( '.$subjectCode.' )';
                   $recordArray[$l]['totalPoints']=$totalPoints;
                   $recordArray[$l]['pointsScored']=$pointsScored;
                   $recordArray[$l]['gpa']=$gpa;
                   $l++;
                   $m++;
                   $subCnt++;
                }
             }
             $categoryGPAAvg=0;
             if($subCnt!=0){
              $categoryGPAAvg=round($categoryGPA/$subCnt,2);
             }
             $recordArray[$l]['srNo']=''; 
             $recordArray[$l]['teacherName']='';
             $recordArray[$l]['categoryName']='';
             $recordArray[$l]['className']='';
             $recordArray[$l]['subjectName']='';
             $recordArray[$l]['totalPoints']='Overall GPA for teacher '.$teacherName;
             $recordArray[$l]['pointsScored']=' in form '.$categoryName.' is ';
             $recordArray[$l]['gpa']=$categoryGPAAvg;
             $l++;                   
          }
       }
    }
}
    

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = $recordArray[$i];
    }

	$csvData = '';
    $csvData .= "#, Teacher, Form Name, Class, Subject, Total Points, Points Scored, GPA \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['teacherName']).', '.parseCSVComments($record['categoryName']).', '.parseCSVComments($record['className']).', '.parseCSVComments($record['subjectName']).', '.parseCSVComments($record['totalPoints']).', '.parseCSVComments($record['pointsScored']).','.parseCSVComments($record['gpa']);
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="teacherDetailedGpa.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: teacherDetailedGpaCSV.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/02/10   Time: 10:17
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified Teacher Detailed GPA report
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified GPA calculation logic.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/02/10   Time: 18:44
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Teacher Detaile GPA Report"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/02/10   Time: 15:25
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Teacher GPA Report"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/10   Time: 17:17
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created college gpa report for feedback modules
?>