<?php 
//This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 15-02-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    $recordArray=array();

  $l=0;
  if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbFinalMgr=FeedBackReportAdvancedManager::getInstance();
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $catId=trim($REQUEST_DATA['catId']);
     $foundArray2=$fbFinalMgr->getAllCategories($labelId,' feedbackCategoryName',$catId);
     $countFoundArray2=count($foundArray2);
     $catId2=-1;
     $x=0;
     $gpaCnt=1;
     $overallGpa=0;
     for($k=0;$k<$countFoundArray2;$k++){
         $catId2=$foundArray2[$k]['feedbackCategoryId'];    
         $catName=$foundArray2[$k]['feedbackCategoryName'];
         $finalInfoArray=$fbFinalMgr->getCategoryResponseCount($catId2,$labelId);
         $finalCnt=count($finalInfoArray);
         $questionId=-1;
         $tempCatName='';
         $tempCatId=-1;
         for($j=0;$j<$finalCnt;$j++){
          if($tempCatId!=$finalInfoArray[$j]['feedbackCategoryId']){
             $tempCatName=$catName; 
             $tempCatId=$finalInfoArray[$j]['feedbackCategoryId'];
             $x++;
             $temp=$x;
             //$questionId=-1;
          }
          else{
             $tempCatName=''; 
             $x=$x;
             $temp='';
          }   
          if($questionId!=$finalInfoArray[$j]['feedbackQuestionId']){
             if($questionId!=-1){
              if($totalAnswerCount>0){    
               $gpa=round($totalPoints/$totalAnswerCount,2);
              }
              else{
               $gpa=0;   
              }
              $overallGpa +=$gpa;
              $gpaCnt++; 
              $recordArray[$l]['answerCount']='<b>GPA : '.$gpa.'</b>';
              $l++;
             } 
             $totalAnswerCount=0;
             $totalPoints=0; 
             $question=$finalInfoArray[$j]['feedbackQuestion'];
             $questionId=$finalInfoArray[$j]['feedbackQuestionId'];
          }
          else{
             $question='';
          }
          $option=$finalInfoArray[$j]['optionLabel'];
          $optionId=$finalInfoArray[$j]['answerSetOptionId'];
          $answerCount=$finalInfoArray[$j]['responseCount'];
          $points=$finalInfoArray[$j]['points'];
          
          $totalAnswerCount +=$answerCount;
          $totalPoints +=$points;
          
            $recordArray[$l]['srNo']=$temp;
            $recordArray[$l]['feedbackCategoryName']=$tempCatName;
            $recordArray[$l]['feedbackQuestion']=$question;
            $recordArray[$l]['optionLabel']=$option;
            $recordArray[$l]['answerCount']=$answerCount;
            $l++;
         }
         
         if($gpaCnt>0){
           $oGpa=round($overallGpa/$gpaCnt,2);  
         }
         else{
          $oGpa=0;
         }
         $recordArray[$l]['answerCount']='<b>Overall GPA : '.$oGpa.'</b>';
         $l++;
         $overallGpa=0;
         $gpaCnt=1;
         
     }
}
//echo '<pre>';
//print_r($recordArray);
//die;
    
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = $recordArray[$i];
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading(' Feedback Category Response Report (Advanced)');
    $reportManager->setReportInformation("Time Table : ".trim($REQUEST_DATA['timeTableName']).", Label : ".trim($REQUEST_DATA['labelName']).', Category : '.trim($REQUEST_DATA['categoryName']));
	 
	$reportTableHead					        =   array();
	$reportTableHead['srNo']			        =   array('#','width="2%"', "align='center' ");
    $reportTableHead['feedbackCategoryName']    =   array('Category','width=5% align="left"', 'align="left"');
    $reportTableHead['feedbackQuestion']        =   array('Feedback Description','width=50% align="left"', 'align="left"');
    $reportTableHead['optionLabel']             =   array('Feedback Response','width=20% align="left"', 'align="left"');
	$reportTableHead['answerCount']	            =   array('Response Count','width=15% align="right"', 'align="right"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: teacherFinalReportPrint.php $
?>