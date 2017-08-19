<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2) {
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");   
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();
    
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']); 
    $classId = trim($REQUEST_DATA['classId']); 
    $employeeId = trim($REQUEST_DATA['employeeId']); 
    $groupId = trim($REQUEST_DATA['groupId']); 
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $periodId = trim($REQUEST_DATA['periodId']); 
    $substituteEmployeeId = trim($REQUEST_DATA['substituteEmployeeId']);  
    
    $viewType = trim($REQUEST_DATA['viewType']);   
    $val = trim($REQUEST_DATA['val']);
    
    
    $condition='';
    if($val=='E') {
      $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
      $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName"; 
      $orderBy = " ORDER BY employeeName";
    }
    else if($val=='C') {
      $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
      if($viewType!='search') {
        $condition .= " AND tt.employeeId = '$substituteEmployeeId' "; 
      }
      $fieldName = " DISTINCT c.classId, c.className";
      $orderBy = " ORDER BY className";
    }
    else if($val=='S') {
      $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
      if($viewType!='search') {
        $condition .= " AND tt.classId = '$classId' AND tt.employeeId = '$substituteEmployeeId' "; 
      }
      $fieldName = " DISTINCT sub.subjectId, sub.subjectCode, sub.subjectName";       
      $orderBy = " ORDER BY subjectCode";
    }
    else if($val=='P') {
       
    }
    else if($val=='G') {
       $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' "; 
       if($viewType!='search') {
          $condition .= " AND tt.subjectId = '$subjectId' AND tt.classId = '$classId' AND tt.employeeId = '$substituteEmployeeId' "; 
       }
       $fieldName = " DISTINCT grp.groupId, grp.groupName";     
       $orderBy = " ORDER BY groupName"; 
    }
    
    if($val=='P') {
       $foundArray = $extraClassAttendanceManager->getAllPeriods();  
    }
    else if($condition!='') {
       $foundArray = $extraClassAttendanceManager->getEmployeeTimeTable($fieldName,$condition,$orderBy);  
    }
  
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
    
?>
