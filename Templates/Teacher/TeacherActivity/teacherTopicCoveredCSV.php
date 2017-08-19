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

    if($groupId=='') {
      $groupId = 0;
    }
    
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
    
    
    // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
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
    
    $record = $employeeManager->getTeacherSubjectTopic($conditions, $orderBy, '');
    $cnt = count($record);
        
    $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
    $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    

   // $record = $employeeManager->getAllSubjectTopic($conditions, $orderBy, '');
   // $cnt = count($record);
    $csvData = '';
    $csvData .= "Teacher, $employeeName, Class, $className \n"; 
    $csvData .= "Subject, $subName, Subject Code, $subCode \n";    
    $csvData .= "Date From, $startDate1, To, $endDate1 \n"; 
    $csvData .= "Sr. No.,Group, Topic (Note: '---' Means Multipile Topics), From Date, To Date \n";
    
    for($i=0;$i<$cnt;$i++) {
        $record[$i]['fromDate'] = UtilityManager::formatDate($record[$i]['fromDate']);    
        $record[$i]['toDate'] = UtilityManager::formatDate($record[$i]['toDate']);    
        
        if($record[$i]['topic'] != '') {
          $topic1 = $record[$i]['topic'];  
          $record[$i]['topic'] = NOT_APPLICABLE_STRING.$topic1; 
        }
        
        $className    = $record[$i]['className'];      
        $subjectName  = $record[$i]['subjectName'];
        $subjectCode  = $record[$i]['subjectCode'];
        
        $record[$i]['fromDate'] = UtilityManager::formatDate($record[$i]['fromDate']);    
        $record[$i]['toDate'] = UtilityManager::formatDate($record[$i]['toDate']);    
        $csvData .= ($i+1).",".parseCSVComments($record[$i]['groupName']).",".parseCSVComments($record[$i]['topic']).",".parseCSVComments($record[$i]['fromDate']).",".parseCSVComments($record[$i]['toDate']);
        $csvData .= "\n";
    }       

   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="TopicTaught.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: teacherTopicCoveredCSV.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/23/10    Time: 12:45p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//timeTableLabel Id check updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/20/09   Time: 6:04p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopic checks updated
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