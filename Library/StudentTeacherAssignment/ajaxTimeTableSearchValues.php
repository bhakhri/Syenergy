<?php
//--------------------------------------------------------  
//It contains the time table 
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2) {
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AssignmentReportManager.inc.php");
    $teacherManager = AssignmentReportManager::getInstance();
    
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']); 
    $classId = trim($REQUEST_DATA['classId']); 
    $employeeId = trim($REQUEST_DATA['employeeId']); 
    $groupId = trim($REQUEST_DATA['groupId']); 
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $valType = trim($REQUEST_DATA['valType']); 
    
    $condition='';
    $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
    $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName"; 
    $orderBy = " ORDER BY employeeName"; 
    $employeeArray = $teacherManager->getEmployeeTimeTable($fieldName,$condition,$orderBy,$timeTableLabelId); 
    

    $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
    if($employeeId!='') {
      $condition .= " AND tt.employeeId = '$employeeId' "; 
    }
    $fieldName = " DISTINCT c.classId, c.className";
    $orderBy = " ORDER BY className";
    $classArray = $teacherManager->getEmployeeTimeTable($fieldName,$condition,$orderBy,$timeTableLabelId); 
   
    
    $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
    if($employeeId!='') {
      $condition .= " AND tt.employeeId = '$employeeId' "; 
    }
    if($classId!='') {
      $condition .= " AND tt.classId = '$classId' "; 
    }
    $fieldName = " DISTINCT sub.subjectId, sub.subjectCode, sub.subjectName";       
    $orderBy = " ORDER BY subjectCode";
    $subjectArray = $teacherManager->getEmployeeTimeTable($fieldName,$condition,$orderBy,$timeTableLabelId); 
    
    
    
    $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
    if($employeeId!='') {
      $condition .= " AND tt.employeeId = '$employeeId' "; 
    }
    if($classId!='') {
      $condition .= " AND tt.classId = '$classId' "; 
    }
    if($subjectId!='') {
      $condition .= " AND tt.subjectId = '$subjectId' "; 
    }
    $fieldName = " DISTINCT grp.groupId, grp.groupName";     
    $orderBy = " ORDER BY groupName"; 
    $groupArray = $teacherManager->getEmployeeTimeTable($fieldName,$condition,$orderBy,$timeTableLabelId); 
    
    echo json_encode($employeeArray).'~~'.json_encode($classArray).'~~'.json_encode($subjectArray).'~~'.json_encode($groupArray);
?>