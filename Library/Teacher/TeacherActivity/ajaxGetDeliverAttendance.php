<?php
//--------------------------------------------------------------------------------------------------------------
//This file returns the array of total lecture deliver attendance 
// Author : Parveen Sharma
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReportManager = StudentReportsManager::getInstance();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance(); 

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
  
    $employeeId = $sessionHandler->getSessionVariable('EmployeeId');        
    $timeTableLabelId = trim(add_slashes($REQUEST_DATA['timeTableLabelId'])); 
    $classId = trim(add_slashes($REQUEST_DATA['classId']));
    $subjectId = trim(add_slashes($REQUEST_DATA['subjectId'])); 
    $groupId = trim(add_slashes($REQUEST_DATA['groupId']));  
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate  = $REQUEST_DATA['toDate'];
    $consolidatedId = $REQUEST_DATA['consolidatedId'];
    $reportType = $REQUEST_DATA['reportType'];
      
    
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
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
    
    if($consolidatedId=='') {
      $consolidatedId=0;  
    }
    
    if($employeeId =='') {
      $employeeId=0;     
    }

    
    $employeeIdAtt = '';
    $employeeIdCheck = '';
    if($reportType==1) {
       $employeeIdCheck = " AND tt.employeeId=$employeeId ";  
       $employeeIdAtt = " AND att.employeeId=$employeeId ";  
    }
    else {
       $employeeIdCheck = ''; 
       $employeeIdAtt = '';
    }   
    
     

    $groupTypeId = -1;
    // Findout Group Type Id
    if($consolidatedId==1) {
       $groupTypeArray = $studentManager->getSingleField('`group`', 'groupTypeId', "WHERE groupId  = $groupId");
       $groupTypeId = $groupTypeArray[0]['groupTypeId'];
    }
    
    // Fetch subject Name
    $subjectName='';
    $subjectArray = $studentManager->getSingleField('`subject`', "CONCAT(subjectName,' (',subjectCode,')') AS subjectName", "WHERE subjectId = $subjectId");
    $subjectName = $subjectArray[0]['subjectName'];
    

    global $sessionHandler;
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');      
    
    
    // Fetch Employee Name
    $employeeName='';
    $tableName = " employee e, `group` g,  ".TIME_TABLE_TABLE."  tt ";
    $fieldsName ="GROUP_CONCAT(DISTINCT employeeName,' (',employeeCode,')' SEPARATOR ', ') AS employeeName";
    $empCondition = " WHERE 
                            e.employeeId=tt.employeeId AND   
                            tt.groupId=g.groupId AND  
                            tt.toDate IS NULL AND  
                            g.classId=$classId  AND tt.subjectId = $subjectId AND tt.timeTableLabelId=$timeTableLabelId AND
                            tt.sessionId=$sessionId AND tt.instituteId=$instituteId AND tt.groupId=$groupId $employeeIdCheck
                      GROUP BY 
                            g.classId, tt.subjectId";  
    $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);
    $employeeName = $employeeArray[0]['employeeName'];
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }
    
    $groupTypeId = -1;
    // Findout Group Type Id
    if($consolidatedId==1) {
       $groupTypeArray = $studentManager->getSingleField('`group`', 'groupTypeId', "WHERE groupId  = $groupId");
       $groupTypeId = $groupTypeArray[0]['groupTypeId'];
    }
    
    $cnt1=0;
    $cnt2=0;
    
    if($groupTypeId==1 || $groupTypeId==3) {
     // Find Student 
        $studentCondition  = " $employeeIdCheck AND c.classId = $classId AND tt.subjectId = $subjectId AND tt.groupId = $groupId AND tt.timeTableLabelId='".$timeTableLabelId."'"; 
        $orderBy = " studentId";
        $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
        $cnt = count($studentArray);
        
        $ffStudentId = 0;    
        for($i=0; $i<$cnt; $i++) {
          $ffStudentId .= ",".$studentArray[$i]['studentId'];  
        } 
        
        $cnt=0;
        $condition = " $employeeIdAtt AND att.classId = $classId  AND att.subjectId = $subjectId AND (att.fromDate <= '$toDate')";  
        $tgroupId =  " $employeeIdAtt AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1,3) ";   
        $tgroupId1 = " $employeeIdAtt AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1) ";
        $tgroupId3 = " $employeeIdAtt AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (3) ";
        
        // Tutorial
        $attendanceCondition =  $condition." $tgroupId1";   
        $attendanceArray1 =  $studentReportManager->getDeliveredLectureCount($attendanceCondition,'groupTypeId');    
        $cnt2='';
        for($gg=0;$gg<count($attendanceArray1);$gg++) {
          $cnt2 .= "<B>T-".($gg+1)."&nbsp;:&nbsp;</B>".$attendanceArray1[$gg]['cnt']."&nbsp;&nbsp;"; 
        }
        if($cnt2=='') {
          $cnt2='';  
        }
        
        // Lecture
        $attendanceCondition =  $condition." $tgroupId3";   
        $attendanceArray3 =  $studentReportManager->getDeliveredLectureCount($attendanceCondition);   
        $cnt1 = $attendanceArray3[0]['cnt'];    
        
        
        // View of Lectures (Date wise)    
        $attendanceConditionDate  = " AND att.classId = $classId AND att.subjectId = $subjectId"; 
        $attendanceConditionDate .= " AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate') $tgroupId"; 
        $fieldName=" DISTINCT 
                           att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber  "; 
        $dateOrderBy = "att.classId, att.subjectId, att.fromDate, att.periodId, att.studentId";
        $dateArray =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
        $cntDate = count($dateArray); 
        
        //$cnt =  $attendanceArray3[0]['cnt'] + $attendanceArray1[0]['cnt'];  
    }
    else {

      // View of Lectures (Date wise)
         $attendanceConditionDate  = " $employeeIdAtt AND att.classId = $classId AND att.subjectId = $subjectId  AND  att.groupId = $groupId "; 
         $attendanceConditionDate .= " AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate')";
         $fieldName=" DISTINCT 
                               att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber "; 
         $dateOrderBy = "att.classId, att.subjectId, att.fromDate, att.periodId, att.studentId";
         $dateArray =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
         $cntDate = count($dateArray);
        
         $attendanceCondition  = " $employeeIdAtt AND att.classId = $classId AND att.subjectId = $subjectId  AND  att.groupId = $groupId "; 
         $attendanceCondition .= " AND (att.fromDate <= '$toDate')";  
         $foundArray =  $studentReportManager->getDeliveredLectureCount($attendanceCondition);  
         $cnt1 = $foundArray[0]['cnt'];
    }
    
    echo $consolidatedId.'!~~!'.$groupTypeId.'!~~!'.$cntDate.'!~~!'.$cnt1.'!~~!'.$cnt2.'!~~!'.$subjectName.'!~~!'.$employeeName;
      
  
  
  
  
// $History: ajaxGetDeliverAttendance.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/12/10    Time: 10:52a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//validation format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/12/10    Time: 10:50a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/12/10    Time: 10:49a
//Updated in $/LeapCC/Library/AdminTasks
//condition format update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/10/10    Time: 5:32p
//Updated in $/LeapCC/Library/AdminTasks
//validation format update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/10/10    Time: 2:48p
//Updated in $/LeapCC/Library/AdminTasks
//condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/10/10    Time: 11:35a
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//
 

?>