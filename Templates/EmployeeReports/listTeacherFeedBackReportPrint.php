<?php
//This file is used as printing version for Teacher Survery FeedBack 
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php         
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
        for ($k=0;$k<$recordFeedBackCount;$k++) {
      $fieldValue.=' SUM(if(ft.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
       }
    } 
    
    $FeedBackArray = $feedBackManager->getFeedBackData($fieldValue,$condition);
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
    $reportManager->setReportHeading('Teacher Survey Feedback Report');
    $reportManager->setReportInformation("Survey: $surveyName   Teacher: $teacherName");
    
    $reportTableHead                   =    array();
                //associated key          col.label,             col. width,      data align
    $reportTableHead['srNo']           = array('#',              'width="2%"  align="center"',  'align="center"');
    $reportTableHead['Question']   = array('Question',   'width="35%" align="left"', 'align="left"');
    
    for ($k=0;$k<$recordFeedBackCount;$k++) {   
        $str = "feedGrade".$recordFeedBackArray[$k]['feedbackGradeId'];
        $str1 = $recordFeedBackArray[$k]['feedbackGradeLabel'];
        $reportTableHead[$str]      =  array($str1, 'width="6%" align="right"', 'align="right" style="padding-right:5px"');
    }
    $reportTableHead['Score']        = array('Score','width="6%" align="right"', 'align="right" style="padding-right:5px" ');
   
    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

// $History: listTeacherFeedBackReportPrint.php $
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

?>
