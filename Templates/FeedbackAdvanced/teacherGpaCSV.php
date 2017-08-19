<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
  $k=0;
  $sNo=-1;
  $tId=0;
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
        if($k!=0){
            $k++;
        }
        for($j=0;$j<$categoryCnt;$j++){
            if($sNo!=$teacherId){
              $sNo=$teacherId;
              $tId++;
               $temp=$tId;
            }
            else{
             $temp='';
            }
        
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
            $totalPointsArray=$fbMgr->getTotalPointsForCategoriesForTeachers($labelId,$categoryId,$teacherId,$timeTableLabelId,$subjectType);
            $totalPoints=$totalPointsArray[0]['totalPoints'];
            $pointsScoredArray=$fbMgr->getPointsScoredForCategoriesForTeachers($labelId,$categoryId,$teacherId);
            $pointsScored=$pointsScoredArray[0]['pointsScored'];
            if($totalPoints!=0){
             $gpa=round(($pointsScored/$totalPoints)*$gpaScalingFactor,2);
            }
            else{
                $gpa=0;
            }
            $sumOfTotalPoints +=round($totalPoints,2);
            $sumOfPointsScored +=round($pointsScored,2);
            $sumOfGpa +=round($gpa,2);
            
            $recordArray[$k]['srNo']=$temp;
            $recordArray[$k]['teacherName']=$teacherName;
            $recordArray[$k]['categoryName']=$categoryName;
            $recordArray[$k]['totalPoints']=$totalPoints;
            $recordArray[$k]['pointsScored']=$pointsScored;
            $recordArray[$k]['gpa']=$gpa;
            $k++;
         }
         $recordArray[$k]['srNo']='';
         $recordArray[$k]['teacherName']='';
         $recordArray[$k]['categoryName']='';
         $recordArray[$k]['totalPoints']=$sumOfTotalPoints;
         $recordArray[$k]['pointsScored']=$sumOfPointsScored;
         $recordArray[$k]['gpa']=$sumOfGpa;
       }
    }
}
    

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = $recordArray[$i];
    }

	$csvData = '';
    $csvData .= "#, Teacher, Form Name, Total Points, Points Scored, GPA \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['teacherName']).', '.parseCSVComments($record['categoryName']).', '.parseCSVComments($record['totalPoints']).', '.parseCSVComments($record['pointsScored']).','.parseCSVComments($record['gpa']);
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="teacherGpa.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: teacherGpaCSV.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 16/02/10   Time: 11:19
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected coding for "Teacher GPA Report"
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified GPA calculation logic.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/02/10   Time: 18:14
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected csv  file name
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