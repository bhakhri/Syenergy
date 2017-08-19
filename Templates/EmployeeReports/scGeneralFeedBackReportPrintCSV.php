<?php 
//This file is used as CSV format for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $feedBackManager = TeacherManager::getInstance();

    $feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];   
    $surveyName=$REQUEST_DATA['surveyName'];   
    
    $condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId;  

    $filter = " WHERE feedbackSurveyId = ".$feedbackSurveyId;  

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC($filter);
    $recordFeedBackCount = count($recordFeedBackArray);

    if($recordFeedBackCount > 0 ) {
      $fieldValue='';
      $fieldHead='';
      $fieldData ='';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
         $fieldValue.=' SUM(if(sgs.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS 
                        feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
         $fieldHead .=  $recordFeedBackArray[$k]['feedbackGradeLabel'].",";
       }
    } 
    
    $FeedBackArray = $feedBackManager->getFeedBackData1($fieldValue,$condition);
    $recordCount = count($FeedBackArray);
        
    $valueArray = array();
    for($i=0;$i<$recordCount;$i++) {
      $valueArray[] = array_merge(array('srNo' => ($i+1) ), 
                                       $FeedBackArray[$i]);
        $teacherName=$FeedBackArray[$i]['employeeName'];
        $surveyName=$FeedBackArray[$i]['feedbackSurveyLabel']; 
   }
   
    
	$csvData = '';
    $csvData = "Survey,$surveyName\n";
	$csvData .= "Sr. No., Question,$fieldHead \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',"'.strip_slashes($record['feedbackQuestion']).'",';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
           $str = "feedGrade".$recordFeedBackArray[$k]['feedbackGradeId'];  
           $csvData .= $record[$str].','; 
        }
         
		$csvData .= "\n";                                                                            
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="GeneralSurveyFeedback.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: scGeneralFeedBackReportPrintCSV.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/EmployeeReports
//Intial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/13/09    Time: 5:06p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//surveryId added in surverygrade table
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/13/09    Time: 2:15p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//query updated
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/06/09    Time: 6:57p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//Intial checkin
?>