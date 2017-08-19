<?php
//-------------------------------------------------------
// Purpose: To generate topic taught list for subject centric Print
//
// Author :Parveen Sharma
// Created on : 02-06-2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeManager = EmployeeReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");  
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    global $sessionHandler;   

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    
    
    $orderBy = " ORDER by $sortField $sortOrderBy";  
    
    $conditions = "";
    
    $timeTableLabelId   = add_slashes($REQUEST_DATA['labelId']);
    $employeeId =$sessionHandler->getSessionVariable('EmployeeId');   
    $classId      = add_slashes($REQUEST_DATA['classId']);
    $subjectId    = add_slashes($REQUEST_DATA['subject']);
    $groupId      = add_slashes($REQUEST_DATA['group']);
    $subjectTopic = add_slashes($REQUEST_DATA['subjectTopic']);

    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;
    }
    
    if($employeeId=='') {
      $employeeId = 0;
    }
    
    if($classId=='') {
      $classId = 0;
    }
    
    if($subjectId=='') {
      $subjectId = 0;
    }
    
    if($groupId=='') {
      $groupId = 0;
    }
    
    
    if($subjectTopic=='') {
      $foundArray = CommonQueryManager::getInstance()->getSubjectTopic(' topic'," st.subjectId = '".$subjectId."'");
      $resultsCount = count($foundArray);
      $subjectTopic = 0;
      for($i=0; $i<$resultsCount; $i++) {
         $subjectTopic .= ','.$foundArray[$i]['subjectTopicId'];  
      }  
    }
    
    if($subjectTopic=='') {
      $subjectTopic = 0;  
    }
    
     // Findout Employee Name
    $employeeNameArray = $studentReportsManager->getSingleField('employee', "CONCAT(employeeName,' (',employeeCode,')') AS employeeName ", "WHERE employeeId  = $employeeId");
    $employeeName = $employeeNameArray[0]['employeeName'];
    
    
    // Findout Class Name
    $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);

    // Findout Subject
    $subCode = 'All';
    if ($subjectId != 'all') {
        $subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode, subjectName', "WHERE subjectId  = $subjectId");
        $subCode = $subCodeArray[0]['subjectCode'];
        $subName = $subCodeArray[0]['subjectName'];
    }

    
    $conditions .= " AND cls.classId = ".$classId." AND (st.subjectTopicId IN (".$subjectTopic.")) ";  
    if(trim(strtolower($groupId))!='all') {
      $conditions .= " AND gr.groupId = $groupId";
    }
     
    $conditions .= " AND st.subjectId = $subjectId  AND tt.employeeId = ".$employeeId;  
    
    $startDate = $REQUEST_DATA['startDate'];
    $endDate = $REQUEST_DATA['endDate'];
    
    
    if($startDate!='' && $endDate =='')
        $conditions .= " AND fromDate >='$startDate' ";

    if($startDate=='' && $endDate!='')
        $conditions .= " AND toDate <='$endDate'";

    if($startDate!='' && $endDate!=''){
        $conditions .= " AND ((fromDate BETWEEN '$startDate' AND '$endDate') OR (toDate BETWEEN '$startDate' AND '$endDate'))";
    }
    
    $conditions .= " AND sub.hasAttendance = 1";
    
    $employeeRecordArray = $employeeManager->getTeacherSubjectTopic($conditions, $orderBy, '');
    $cnt = count($employeeRecordArray);
        
    $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
    $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    

    for($i=0;$i<$cnt;$i++) {
        $employeeRecordArray[$i]['fromDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['fromDate']);    
        $employeeRecordArray[$i]['toDate'] = UtilityManager::formatDate($employeeRecordArray[$i]['toDate']);    
        
        if($employeeRecordArray[$i]['topic'] != '') {
          $topic1 = $employeeRecordArray[$i]['topic'];  
          $employeeRecordArray[$i]['topic'] = NOT_APPLICABLE_STRING.$topic1; 
        }
        
        $className    = $employeeRecordArray[$i]['className'];      
        $subjectName  = $employeeRecordArray[$i]['subjectName'];
        $subjectCode  = $employeeRecordArray[$i]['subjectCode'];
        
        $valueArray[] = array_merge(array('srNo' => ($i+1) ),$employeeRecordArray[$i]);
    }       

        
    $heading = "<B>Teacher:</B>&nbsp;$employeeName&nbsp;&nbsp;
                <B>Class:</b>&nbsp;$className2<br>
                <B>Subject:</B>&nbsp;$subName&nbsp;&nbsp;<B>Subject Code:</B>&nbsp;$subCode<br>
                <B>Date From:</b>&nbsp;$startDate1&nbsp;&nbsp;<b>To</b>&nbsp;&nbsp;$endDate1 \n";

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Subject Wise Topic Taught Report');
    $reportManager->setReportInformation($heading);
    
    $reportTableHead                    =    array();
                //associated key          col.label,       col. width,      data align
    $reportTableHead['srNo']            = array('#',       'width="2%" align="center"',  'align="center"');
	$reportTableHead['groupName']       = array('Group',   'width="10%" align="left"', 'align="left"');
	$reportTableHead['topic']           = array("Topics<br><b><font color=red size=1>Note: '---' Means Multipile Topics</font></b>", 'width="15%" align="center"', 'align="left"');
	$reportTableHead['fromDate']        = array('From Date', 'width="12%" align="center"', 'align="center"');
    $reportTableHead['toDate']          = array('To Date',   'width="12%" align="center"', 'align="center"');

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

    
// $History: teacherTopicCoveredPrint.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/23/10    Time: 12:45p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//timeTableLabel Id check updated 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/03/09   Time: 11:33a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//field name updated (group) 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/20/09   Time: 6:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopic checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/09/09   Time: 6:21p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//setting in date format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/09/09   Time: 2:28p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Search format paramter updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//class added, look & feel formating 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:24p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//

?>
