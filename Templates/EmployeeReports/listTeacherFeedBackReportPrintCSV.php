<?php 
//This file is used as CSV format for Teacher Survery FeedBack 
//
// Author :Parveen Sharma
// Created on : 02-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;    
    require_once(BL_PATH . '/ScReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $feedBackManager = ScTeacherManager::getInstance();

    $teacherId=$REQUEST_DATA['teacherId'];
    $feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];   
    $teacherName=$REQUEST_DATA['teacherName'];    
    $surveyName=$REQUEST_DATA['surveyName'];   
    
    $condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId." AND ft.employeeId = ".$teacherId;  


    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC();
    $recordFeedBackCount = count($recordFeedBackArray);

    if($recordFeedBackCount > 0 ) {
      $fieldValue='';
      $fieldHead='';
      $fieldData ='';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
         $fieldValue.=' SUM(if(ft.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS 
                        feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
         $fieldHead .=  $recordFeedBackArray[$k]['feedbackGradeLabel'].",";
       }
    } 
    
    $FeedBackArray = $feedBackManager->getFeedBackData($fieldValue,$condition);
    $recordCount = count($FeedBackArray);
        
    $valueArray = array();
    for($i=0;$i<$recordCount;$i++) {
      $valueArray[] = array_merge(array('srNo' => ($i+1) ), 
                                       $FeedBackArray[$i]);
        $teacherName=$FeedBackArray[$i]['employeeName'];
        $surveyName=$FeedBackArray[$i]['feedbackSurveyLabel']; 
   }
   
    
	$csvData = '';
    $csvData = "Survey,$surveyName,Teacher,$teacherName\n";
	$csvData .= "Sr. No., Question,$fieldHead Score \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',"'.strip_slashes($record['feedbackQuestion']).'",';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
           $str = "feedGrade".$recordFeedBackArray[$k]['feedbackGradeId'];  
           $csvData .= $record[$str].','; 
        }
        $csvData .= '"'.number_format(($record['ratio']),2,'.','').'"'; 
		$csvData .= "\n";                                                                            
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="TeacherSurveyFeedback.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: listTeacherFeedBackReportPrintCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 3:44p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/13/08   Time: 12:40p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//bug fix
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/08/08   Time: 5:14p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//score number format set
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/05/08   Time: 2:04p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//list name change modify (score)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/04/08   Time: 12:48p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//strip_slashes format settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/04/08   Time: 10:40a
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//html tags & formatting settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/03/08   Time: 12:58p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//strip_slashes added 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/03/08   Time: 11:31a
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//Print & CSV format update 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/02/08   Time: 5:08p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//teacher feedback update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/02/08   Time: 3:48p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Templates/SMSReports
//list and report formatting
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 12:23p
//Updated in $/Leap/Source/Templates/SMSReports
//tabs settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Templates/SMSReports
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Templates/SMSReports
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 11:27a
//Updated in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 10:55a
//Created in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/24/08    Time: 7:11p
//Updated in $/Leap/Source/Templates/ScStudent
//changed CSV headers
?>