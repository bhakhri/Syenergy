<?php 
//This file is used as printing version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 15-02-2010
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

  $l=0;
  if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) !='') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
    $fbFinalMgr=FeedBackReportAdvancedManager::getInstance();
    $labelId=trim($REQUEST_DATA['labelId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $catId=trim($REQUEST_DATA['catId']);
    //$employeeId=trim($REQUEST_DATA['employeeId']);
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
    
    $foundArray=$fbFinalMgr->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.$labelId,$employeeId );
     $countFoundArray=count($foundArray);
     for($i=0;$i<$countFoundArray;$i++){
        $employeeId2=$foundArray[$i]['employeeId'];
        $employeeName2=$foundArray[$i]['employeeName'];
        $foundArray2=$fbFinalMgr->getCategory($labelId,$employeeId2,$timeTableLabelId,$catId);
        $countFoundArray2=count($foundArray2);
     for($k=0;$k<$countFoundArray2;$k++){
        $catId2=$foundArray2[$k]['feedbackCategoryId'];    
        $catName=$foundArray2[$k]['feedbackCategoryName'];
        $finalInfoArray=$fbFinalMgr->getOptions($employeeId2,$catId2,$labelId);
        $finalCnt=count($finalInfoArray);
        $questionId=-1;
        $x=0;
      
     for($j=0;$j<$finalCnt;$j++){ 
         if($questionId!=$finalInfoArray[$j]['feedbackQuestionId']){
             $question=$finalInfoArray[$j]['feedbackQuestion'];
             $questionId=$finalInfoArray[$j]['feedbackQuestionId'];
             $employeeName2=$foundArray[$i]['employeeName'];
             $catName=$foundArray2[$k]['feedbackCategoryName'];
             $x++;
             $temp=$x;
         }
         else{
             $employeeName2='';
             $catName='';
             $question='';
             $x=$x;
             $temp='';
         }
         $option=$finalInfoArray[$j]['optionLabel'];
         $optionId=$finalInfoArray[$j]['answerSetOptionId'];
         $answerCount=$finalInfoArray[$j]['answerCount'];
    
     
               $recordArray[$l]['srNo']=$temp;
               $recordArray[$l]['employeeName']=$employeeName2;
               $recordArray[$l]['feedbackCategoryName']=$catName;
               $recordArray[$l]['feedbackQuestion']=$question;
               $recordArray[$l]['optionLabel']=$option;
               $recordArray[$l]['answerCount']=$answerCount;
               //$recordArray[$l]['gpa']=$gpa;
               $l++;
            }
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
	$reportManager->setReportHeading('Feedback Teacher Final Report (Advanced)');
    $reportManager->setReportInformation("Time Table : ".trim($REQUEST_DATA['timeTableName']).", Label : ".trim($REQUEST_DATA['labelName']).',  Teacher : '.$sessionHandler->getSessionVariable('EmployeeName').',  Category : '.trim($REQUEST_DATA['categoryName']));
	 
	$reportTableHead					        =   array();
	$reportTableHead['srNo']			        =   array('#','width="2%"', "align='center' ");
    $reportTableHead['employeeName']            =   array('Teacher','width=15% align="left"', 'align="left"');
    $reportTableHead['feedbackCategoryName']    =   array('Category','width=5% align="left"', 'align="left"');
    $reportTableHead['feedbackQuestion']        =   array('Feedback Description','width=25% align="left"', 'align="left"');
    $reportTableHead['optionLabel']             =   array('Feedback Response','width=30% align="left"', 'align="left"');
	$reportTableHead['answerCount']	            =   array('Responce Count','width=10% align="right"', 'align="right"');
	//$reportTableHead['gpa']		           =   array('GPA','width="15%" align="right" ', 'align="right"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: teacherFinalReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/03/10   Time: 16:46
//Created in $/LeapCC/Templates/FeedbackAdvanced/Teacher
//Created Feedback Teacher Final Report (Advanced) for Teacher login
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/16/10    Time: 12:13p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//created file under feedback teacher final report
//

?>