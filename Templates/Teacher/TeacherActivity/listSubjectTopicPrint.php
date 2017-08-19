<?php 
//This file is used as printing version for subject topic
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH."/EmployeeReportsManager.inc.php");   
 
    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php"); 
    $subjecttopicManager = SubjectTopicManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    
    $filter = "";
    $search = "";
    
    global $sessionHandler;
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); 
    if($employeeId=='') {
      $employeeId = 0;  
    }
    
    
    // Findout Time Table Name
    $employeeNameArray = $studentManager->getSingleField('employee', "employeeName,employeeCode ", "WHERE employeeId  = $employeeId");
    $employeeName = $employeeNameArray[0]['employeeName'];
    $employeeCode = $employeeNameArray[0]['employeeCode']; 
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }
    if($employeeCode=='') {
      $employeeCode = NOT_APPLICABLE_STRING;  
    }
    
    $search .= trim($employeeName)." (".trim($employeeCode).")<br>";
    $search .= "Search By: ".trim($REQUEST_DATA['searchbox']);
    
     /// Search filter /////  
	 if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND (sub.subjectName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       sub.subjectCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       st.topic LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                       st.topicAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    $cond = " AND tt.employeeId = $employeeId";
    $subjectArray = EmployeeReportsManager::getInstance()->getActiveTimeTableSubject($cond);
    
    $subjectId=0;
    for($i=0;$i<count($subjectArray);$i++) {
      $subjectId .=",".$subjectArray[$i]['subjectId']; 
    }
    $filter .= " AND sub.subjectId IN ($subjectId) ";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    $subjecttopicRecordArray = $subjecttopicManager->getSubjectTopicList($filter,'',$orderBy);
    $cnt = count($subjecttopicRecordArray);
    
    $valueArray  = array();
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('srNo' => ($i+1)),$subjecttopicRecordArray[$i]);
    }
    
    $reportManager->setReportWidth(780);
    $reportManager->setReportHeading('Bulk Subject Topic Master Report');
    $reportManager->setReportInformation($search);

    $reportTableHead                 = array();
    //associated key                  col.label,       col. width,      data align
    $reportTableHead['srNo']         = array('#',            'width="2%" align="left"',  'align="left" valign="top" ');
    $reportTableHead['subjectName']  = array('Subject Name', 'width=15%   align="left"', 'align="left" valign="top" ');
    $reportTableHead['subjectCode']  = array('Subject Code', 'width=10%   align="left"', 'align="left" valign="top" ');
    $reportTableHead['topic']        = array('Topic',        'width="35%" align="left"', 'align="left" valign="top" style="padding-right:1px"');
    $reportTableHead['topicAbbr']    = array('Abbr.',        'width="35%" align="left"', 'align="left" valign="top" style="padding-right:1px"');

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>
<?php 
// $History: listSubjectTopicPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/13/10    Time: 2:27p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//look & feel updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/13/10    Time: 2:09p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//validation format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/10    Time: 1:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/13/10    Time: 12:30p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/SubjectTopic
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Templates/SubjectTopic
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/30/09    Time: 1:40p
//Updated in $/LeapCC/Templates/SubjectTopic
//Fixed - 0000780: Bulk Subject Topic Master - Admin> “#” field is right
//aligned by default in print report. It must be left aligned.
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/01/09    Time: 3:22p
//Updated in $/LeapCC/Templates/SubjectTopic
//formatting & spelling correct
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/30/09    Time: 10:38a
//Updated in $/LeapCC/Templates/SubjectTopic
//search condition subject code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Created in $/LeapCC/Templates/SubjectTopic
//print & csv file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/20/09    Time: 1:21p
//Created in $/Leap/Source/Templates/SubjectTopic
//print & CSV files added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 4:01p
//Created in $/Leap/Source/Templates/ScTimeTable
//inital checkin
//


?>

