<?php
//This file is used as printing version for Teacher Survery FeedBack 
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    $condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId." AND ft.employeeId = ".$teacherId;  

    $filter = "WHERE feedbackSurveyId = ".$feedbackSurveyId;

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC($filter);
    $recordFeedBackCount = count($recordFeedBackArray);

    if($recordFeedBackCount > 0 ) {
      $fieldValue='';
        for ($k=0;$k<$recordFeedBackCount;$k++) {
      $fieldValue.=' SUM(if(ft.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
       }
    } 
    
    $FeedBackArray = $feedBackManager->getFeedBackData($fieldValue,$condition);
    $recordCount = count($FeedBackArray);
    
    //$studentAssign = 0;
    //$FeedBackStudentArr = $feedBackManager->getStudentAssign($condition);
    //if(count($FeedBackStudentArr)>0) {
    //   $studentAssign =  $FeedBackStudentArr[0]['cnt'];
   // }
        
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
    
    $reportHeading = "Survey: $surveyName   Teacher: $teacherName";
    //        Total no. of students registered in the course in sections allocated to you : ".$studentAssign.",&nbsp;&nbsp;&nbsp;&nbsp;
    //        No. of students who responded to the feedback : ".$FeedBackArray[0]['totalStudent']; 

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Teacher Survey Feedback Report');
    $reportManager->setReportInformation($reportHeading);
    
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

// $History: scTeacherFeedBackReportPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/EmployeeReports
//Intial checkin
//
//*****************  Version 12  *****************
//User: Parveen      Date: 5/06/09    Time: 7:28p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//Total no. of students registered in the course in sections allocated to
//remove
//
//*****************  Version 11  *****************
//User: Parveen      Date: 5/02/09    Time: 11:19a
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//new enhancement in report student assign added
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/13/09    Time: 4:58p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//issue fix
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
