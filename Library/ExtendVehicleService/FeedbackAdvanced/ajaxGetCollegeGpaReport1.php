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
    define('MODULE','ADVFB_CollegeGpaReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager1.inc.php");
    $fbMgr=FeedBackReportAdvancedManager::getInstance();

    $finalResultArray = array();
    
    
     // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'surveyName';
    
    $orderBy = " $sortField $sortOrderBy";      
    
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
      if($labelId=='') {
       $labelId=0; 
    } 
     
    if($timeTableLabelId=='') {
       $timeTableLabelId=0; 
    } 
    
    // Findout Feedback Survey Name
    $feedBackName=$fbMgr->getFeedbackName1($labelId);
    
    
   
   // Fetch student feedback record feedbackToQuestionId	
   $studentQuestionAnswerArray=$fbMgr->getFeedbackQuestionAnswer($labelId,$timeTableLabelId); 
   $sqa=count($studentQuestionAnswerArray); 
   
 
    
   $question=$studentQuestionAnswerArray[0]['feedbackToQuestionId'];
   if($question=='') {
     $question=0;  
   }
  
   
   $answer=array();
   $arrw=array();
   for($i=0;$i<$sqa;$i++){
      $answer[$i]=$studentQuestionAnswerArray[$i]['answerSetOptionId'];
      $arrw[$i]=explode(',',$answer[$i]);
   }
   
   
   //getting question id for quention and answerId
   $questionRecordArray=$fbMgr->getOptionName($question);
   $optionRecordArray=$fbMgr->getAnswerOptionValue($question); 
   $question='0';
   $answerPerQu=array(); 
   $AnswerIdPerStudentIdArrayCount=array();
   $valueArray = array();
   
   $totalResponse=0;	
   $totalWeightAvg=0;
   for($i=0;$i<count($questionRecordArray);$i++){
      $question .=",".$questionRecordArray[$i]['feedbackQuestionId'];
      $questionArray[$i]['questionName'] = $questionRecordArray[$i]['feedbackQuestion'];
      $questionArray[$i]['questionId']   = $questionRecordArray[$i]['feedbackQuestionId'];
      $questionArray[$i]['answerSetId']  = $questionRecordArray[$i]['answerSetId'];
      $temp='';
      $answerPerQu[$i][0] = '';
      $finalResultArray[$i]['questionName'] = $questionArray[$i]['questionName'];
      $finalResultArray[$i]['srNo'] = ($i+1);
      
      for($j=0;$j<count($arrw);$j++){
         if($temp!='') {
           $temp .="~";  
         } 
         $temp .= $arrw[$j][$i];
         $answerPerQu[$i][$j] = $arrw[$j][$i];
      }
      $questionArray[$i]['answerId'] = $temp;
      $AnswerIdPerStudentIdArrayCount=array_count_values($answerPerQu[$i]);    
      
      
      $response=0;
      $weightedAvg=0;
      $gpaQuestion=0;
      
     
      for($j=0;$j<count($optionRecordArray);$j++) {
         $idPoint='optPoint'.($j+1); 
         $idStudent='optStudent'.($j+1); 
         $pointValue = $optionRecordArray[$j]['optionPoints'];
         $answerSetOptionId = $optionRecordArray[$j]['answerSetOptionId'];
         $finalResultArray[$i][$id] = 0; 
         $total=0;
         $totalStudent=0;
         foreach($AnswerIdPerStudentIdArrayCount as $key => $value) {
            if($key==$answerSetOptionId) {
              $total += ($value * $pointValue);   
              $totalStudent += $value; 
            }  
         }
         $finalResultArray[$i][$idPoint] = $total;
         $finalResultArray[$i][$idStudent] = $totalStudent;
         $response += $totalStudent;
         $weightedAvg += $total;
      }

      $finalResultArray[$i]['response'] = $response;
      $finalResultArray[$i]['weightedAvg'] = $weightedAvg;
      $totalResponse += $response;	
      $totalWeightAvg +=$weightedAvg;
      if($response!=0) {
        $gpaQuestion = number_format(($weightedAvg/$response), 2, '.', ' ');
      }
      $finalResultArray[$i]['gpa'] = $gpaQuestion;     
      
      $valueArray = array_merge(array('srNo' => ($i+1)),
                                      $finalResultArray[$i]);
      if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
      }
      else {
         $json_val .= ','.json_encode($valueArray);           
      }  
      $ii=$i;                                     
   }
   
   if($totalResponse!=0) {
     $gpa = number_format(($totalWeightAvg/$totalResponse), 2, '.', ' ');
   }
   
   $finalResultArray[$i]['weightedAvg']=$totalWeightAvg;
   $finalResultArray[$i]['response']=$totalResponse;
   $finalResultArray[$i]['gpa']=$gpa;
   
   $json_option_val ='';   				   
   for($j=0;$j<count($optionRecordArray);$j++) {
      $idPoint='optPoint'.($j+1); 
      $idStudent='optStudent'.($j+1); 
      $finalResultArray[$i][$idPoint]='';
      $finalResultArray[$i][$idStudent]='';
      if(trim($json_option_val)=='') {
        $json_option_val = json_encode($optionRecordArray[$j]);
      }
      else {
       $json_option_val .= ','.json_encode($optionRecordArray[$j]);           
      }      
   }
   
   $valueArray = array_merge(array('srNo' =>'','questionName'=>''),
   				   $finalResultArray[$i]);

   
   if(trim($json_val)=='') {
     $json_val = json_encode($valueArray);
   }
   else {
     $json_val .= ','.json_encode($valueArray);           
   }  
   
   
   global $sessionHandler;
   $sessionHandler->setSessionVariable('IdToFeedbackReportOption',$optionRecordArray);
   $sessionHandler->setSessionVariable('IdToFeedbackReportData',$finalResultArray);
   
   echo '{"optionValueArray":['.$json_option_val.'],"infoArray":['.$json_val.']}';
?>
