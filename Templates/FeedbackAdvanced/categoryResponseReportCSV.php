<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 15.02.2010
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
              $recordArray[$l]['answerCount']='GPA : '.$gpa;
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
         $recordArray[$l]['answerCount']='Overall GPA : '.$oGpa;
         $l++;
         $overallGpa=0;
         $gpaCnt=1;
     }
}
    

    $cnt = count($recordArray);
    $valueArray = array();
    for($f=0;$f<$cnt;$f++) {
        $valueArray[] = $recordArray[$f];
    }

	$csvData = '';
    $csvData .= "#, Category, Feedback Description, Feedback Response, Responce Count \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['feedbackCategoryName']).','.parseCSVComments($record['feedbackQuestion']).', '.parseCSVComments($record['optionLabel']).','.parseCSVComments($record['answerCount']);
		$csvData .= "\n";
	}

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="teacherFinalReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: teacherFinalCSV.php $
?>