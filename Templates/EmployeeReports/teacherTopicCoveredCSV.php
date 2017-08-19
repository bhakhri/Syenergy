<?php
//-------------------------------------------------------
// Purpose: To generate topic taught list for subject centric Print
//
// Author :Parveen Sharma
// Created on : 02-06-2009
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    UtilityManager::ifNotLoggedIn(true);


    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeManager = EmployeeReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    global $sessionHandler;   
    
    $timeTableLabelId   = add_slashes($REQUEST_DATA['labelId']);    
    $employeeId   = add_slashes($REQUEST_DATA['employeeId']);
    $classId      = add_slashes($REQUEST_DATA['classId']);
    $subjectId    = add_slashes($REQUEST_DATA['subject']);
    $groupId      = add_slashes($REQUEST_DATA['group']);
    $subjectTopic = add_slashes($REQUEST_DATA['subjectTopic']);
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    
    if($classId=='') {
      $classId =0;   
    }
    
    if($subjectId=='') {
      $subjectId =0;   
    }
    
    if($groupId=='') {
      $groupId =0;   
    }
    
    if($subjectTopic=='') {
      $subjectTopic =0;   
    }
    
    if($timeTableLabelId=='') {
      $timeTableLabelId =0;   
    }
    
    // Findout Employee Name
    if($employeeId != '') {
     $employeeArray = $studentReportsManager->getSingleField(" employee emp LEFT JOIN department d ON emp.departmentId = d.departmentId ", 
                                                           " emp.employeeName, emp.employeeCode, 
                                                             IFNULL(d.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName ", 
                                                           " WHERE emp.employeeId = $employeeId");
                                                               
     $employeeName = $employeeArray[0]['employeeName'];
     $employeeCode = $employeeArray[0]['employeeCode'];
     $departmentName = $employeeArray[0]['departmentName'];
    }
    
    // Findout Class Name
    $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);

    // Findout Subject
    // Findout Subject
    if($subjectId!='') {
        $subCode = 'All';
        if ($subjectId != 'all') {
            $subCodeArray = $studentReportsManager->getSingleField(" `subject` sub, subject_type st ", 
                                                                   " sub.subjectName, sub.subjectCode, st.subjectTypeName ", 
                                                                   " WHERE st.subjectTypeId = sub.subjectTypeId AND subjectId = $subjectId");
            $subType = $subCodeArray[0]['subjectTypeName'];
            $subCode = $subCodeArray[0]['subjectCode'];
            $subName = $subCodeArray[0]['subjectName'];
        }
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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    
    if($sortField == 'undefined') {
       $sortOrderBy = 'ASC';
       $sortField = 'fromDate';  
    }
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";  
    
    
    $conditions = "";
    
  
    
    $conditions .= " AND cls.classId = ".$classId." AND (st.subjectTopicId IN (".$subjectTopic.")) ";  
        
    if(trim(strtolower($groupId))!='all') {
      $conditions .= " AND gr.groupId = $groupId";
    }
     
    $conditions .= " AND st.subjectId = $subjectId  AND tt.employeeId = ".$employeeId;  
    
    $startDate = $REQUEST_DATA['startDate'];
    $endDate = $REQUEST_DATA['endDate'];
    
    if($startDate!='' && $endDate =='') {
       $conditions .= " AND fromDate >='$startDate' ";
       $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
    }
        
    if($startDate=='' && $endDate!='') {
       $conditions .= " AND toDate <='$endDate'";
       $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    
    }
    
    if($startDate!='' && $endDate!=''){
       $conditions .= " AND ((fromDate BETWEEN '$startDate' AND '$endDate') OR (toDate BETWEEN '$startDate' AND '$endDate'))";
        
       $startDate1 = UtilityManager::formatDate($REQUEST_DATA['startDate']);    
       $endDate1  = UtilityManager::formatDate($REQUEST_DATA['endDate']);    
    }
    
    $conditions .= " AND sub.hasAttendance = 1 ";     
    
    $record = $employeeManager->getTeacherSubjectTopic($conditions, $orderBy, '');
    $cnt = count($record);
    
    
    $csvData = '';
    $csvData .= "Faculty Name, $employeeName ($employeeCode),Department, $departmentName \n";
    $csvData .= "Class, $className \n"; 
    $csvData .= "Subject, $subName, Subject Code, $subCode \n";    
    $csvData .= "Date From, $startDate1, To, $endDate1 \n"; 
    $csvData .= "Sr. No., From Date, To Date, Group, Period No., Topics Covered (Note: '---' Means Multiple Topics)\n";
    for($i=0;$i<$cnt;$i++) {
       $record[$i]['fromDate'] = UtilityManager::formatDate($record[$i]['fromDate']);    
       $record[$i]['toDate'] = UtilityManager::formatDate($record[$i]['toDate']);    
       $csvData .= ($i+1).','.$record[$i]['fromDate'].','.$record[$i]['toDate'].','.parseCSVComments($record[$i]['groupName']).',';
       $csvData .= parseCSVComments($record[$i]['periodNumber']).','.parseCSVComments($record[$i]['topic']);
       $csvData .= "\n";
    }
   
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream; charset="utf-8"',true);
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="TeacherTopicTaught.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: teacherTopicCoveredCSV.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/11/10    Time: 4:45p
//Updated in $/LeapCC/Templates/EmployeeReports
//timetable label check added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Templates/EmployeeReports
//date format & alignement updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/09   Time: 5:16p
//Updated in $/LeapCC/Templates/EmployeeReports
//format & validation format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/09   Time: 3:22p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
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