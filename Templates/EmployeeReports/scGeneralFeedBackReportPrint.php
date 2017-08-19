<?php
//This file is used as printing version for General Survery FeedBack 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php         
    global $FE;    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $feedBackManager = TeacherManager::getInstance();

    $teacherId=$REQUEST_DATA['teacherId'];
    $feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];   
    $teacherName=$REQUEST_DATA['teacherName'];    
    $surveyName=$REQUEST_DATA['surveyName'];   
    
    $filter = " WHERE feedbackSurveyId = ".$feedbackSurveyId;  
    
    $condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId;  

           
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC($filter);
    $recordFeedBackCount = count($recordFeedBackArray);

    if($recordFeedBackCount > 0 ) {
      $fieldValue='';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
      $fieldValue.=' SUM(if(sgs.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
       }
    } 
    
    $FeedBackArray = $feedBackManager->getFeedBackData1($fieldValue,$condition);
    $recordCount = count($FeedBackArray);
        
    $valueArray = array();
    for($i=0;$i<$recordCount;$i++) {
        //$FeedBackArray[$i]['feedbackQuestion'] = strip_slashes($FeedBackArray[$i]['feedbackQuestion']);
        $valueArray[] = array_merge(array('srNo' => $i+1,
                                          'Question' => (strip_slashes($FeedBackArray[$i]['feedbackQuestion'])),
                                          'Score' => (number_format(($FeedBackArray[$i]['ratio']),2,'.','')),
                                    ), $FeedBackArray[$i]);
        $teacherName=$FeedBackArray[$i]['employeeName'];
        $surveyName=$FeedBackArray[$i]['feedbackSurveyLabel']; 
   }
    

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('General Survey Feedback Report');
    $reportManager->setReportInformation("Survey: $surveyName");
    
    $reportTableHead                   =    array();
                //associated key          col.label,             col. width,      data align
    $reportTableHead['srNo']           = array('#',              'width="2%"  align="center"',  'align="center"');
    $reportTableHead['Question']   = array('Question',   'width="35%" align="left"', 'align="left"');
    
    for ($k=0;$k<$recordFeedBackCount;$k++) {   
        $str = "feedGrade".$recordFeedBackArray[$k]['feedbackGradeId'];
        $str1 = $recordFeedBackArray[$k]['feedbackGradeLabel'];
        $reportTableHead[$str]      =  array($str1, 'width="6%" align="right"', 'align="right" style="padding-right:5px"');
    }
   
    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

// $History: scGeneralFeedBackReportPrint.php $
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
