<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

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
         $recordArray[$k]['totalPoints']='<b>'.$sumOfTotalPoints.'</b>';
         $recordArray[$k]['pointsScored']='<b>'.$sumOfPointsScored.'</b>';
         $recordArray[$k]['gpa']='<b>'.$sumOfGpa.'</b>';
       }
    }
}
    
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = $recordArray[$i];
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback Teacher GPA Report (Advanced)');
    $reportManager->setReportInformation("Time Table : ".trim($REQUEST_DATA['timeTableName'])." Label : ".trim($REQUEST_DATA['labelName']).'  Teacher : '.trim($REQUEST_DATA['teacherName']).'<br/>'.$visibleFromToString);
	 
	$reportTableHead					   =   array();
	$reportTableHead['srNo']			   =   array('#','width="2%"', "align='center' ");
    $reportTableHead['teacherName']        =   array('Teacher','width=25% align="left"', 'align="left"');
    $reportTableHead['categoryName']       =   array('Form Name','width=31% align="left"', 'align="left"');
    $reportTableHead['totalPoints']        =   array('Total Points','width=15% align="right"', 'align="right"');
	$reportTableHead['pointsScored']	   =   array('Points Scored','width=15% align="right"', 'align="right"');
	$reportTableHead['gpa']		           =   array('GPA','width="15%" align="right" ', 'align="right"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: teacherGpaPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 16/02/10   Time: 11:19
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected coding for "Teacher GPA Report"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/02/10   Time: 11:47
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified GPA calculation logic.
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