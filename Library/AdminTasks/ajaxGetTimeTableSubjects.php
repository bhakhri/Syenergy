<?php
//--------------------------------------------------------------------------------------------------------------
//This file returns the array of subjects, based on classId
// Author : Parveen Sharma
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();

    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();


    $conditionEmployee = '';     
    if($roleId==2) {    
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      $conditionEmployee = " AND tt.employeeId = '$employeeId' ";
    }
     
    $timeTableLabelId = trim(add_slashes($REQUEST_DATA['timeTableLabelId'])); 
    $classId = trim(add_slashes($REQUEST_DATA['classId']));
      
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
    
    if($classId=='') {
      $classId = 0;  
    }
    
    
    $cond = " AND c.classId = $classId ".$conditionEmployee;   
	
	
    $filter= " DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, IF(sc.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode) AS subjectCode, IF(sc.isAlternateSubject='0',su.subjectName,su.alternateSubjectName) AS subjectName, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = " classId,  subjectTypeId, subjectCode, subjectId";
    $foundArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
 
// $History: ajaxGetTimeTableSubjects.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/10    Time: 11:35a
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>