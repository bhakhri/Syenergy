<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");   
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();
    
    
    $timeTableLabelId = trim($REQUEST_DATA['labelId']); 
    $classId = trim($REQUEST_DATA['degreeId']); 
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $employeeId = trim($REQUEST_DATA['employeeId']); 
    $val = trim($REQUEST_DATA['val']);
    
    $isTimeTableCheck=  trim($REQUEST_DATA['isTimeTableCheck']); 
    if($timeTableLabelId=='') {
      $timeTableLabelId='0';  
    }
    
    if($isTimeTableCheck=='') {
      $isTimeTableCheck=0;  
    }
   
    //if($isTimeTableCheck==1) {
        // Fetch Classes
        $condition= " AND tt.timeTableLabelId LIKE '$timeTableLabelId' "; 
        $fieldName = " DISTINCT c.classId, c.className";
        $orderBy = " ORDER BY className";
        $classArray = $extraClassAttendanceManager->getEmployeeTimeTable($fieldName,$condition,$orderBy);  
        
          
        // Fetch Subject
        $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' ";    
        $condition .= " AND tt.classId LIKE '$classId' "; 
        
        $fieldName = " DISTINCT sub.subjectId, sub.subjectCode, sub.subjectName";       
        $orderBy = " ORDER BY subjectCode";
        $subjectArray = $extraClassAttendanceManager->getEmployeeTimeTable($fieldName,$condition,$orderBy);  
        
        
        // Fetch Employee Name
        $condition= " AND tt.timeTableLabelId = '$timeTableLabelId' ";    
        $condition .= " AND tt.classId LIKE '$classId' "; 
        if($subjectId!='all' && $subjectId!='') {
          $condition .= " AND tt.subjectId LIKE '$subjectId' ";    
        }
        $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName"; 
        $orderBy = " ORDER BY employeeName";
        $employeeArray = $extraClassAttendanceManager->getEmployeeTimeTable($fieldName,$condition,$orderBy); 
/*        
    }
    else {
        $condition= "  AND tt.timeTableLabelId = '$timeTableLabelId'"; 
        $fieldName = " DISTINCT c.classId, c.className";
        $orderBy = " ORDER BY className";
        $classArray = $extraClassAttendanceManager->getEmployeeAttendance($fieldName,$condition,$orderBy);  
        
          
        // Fetch Subject
        $condition= " AND tt.timeTableLabelId = '$timeTableLabelId'";    
        $condition .= " AND att.classId LIKE '$classId' "; 
        
        $fieldName = " DISTINCT sub.subjectId, sub.subjectCode, sub.subjectName";       
        $orderBy = " ORDER BY subjectCode";
        $subjectArray = $extraClassAttendanceManager->getEmployeeAttendance($fieldName,$condition,$orderBy);  
        
        
        // Fetch Employee Name
        $condition= "  AND tt.timeTableLabelId = '$timeTableLabelId'";    
        $condition .= " AND att.classId LIKE '$classId' "; 
        if($subjectId!='all' && $subjectId!='') {
          $condition .= " AND att.subjectId LIKE '$subjectId' ";    
        }
        $fieldName = " DISTINCT e.employeeId, CONCAT(e.employeeName,' (',e.employeeCode,')') AS employeeName"; 
        $orderBy = " ORDER BY employeeName";
        $employeeArray = $extraClassAttendanceManager->getEmployeeAttendance($fieldName,$condition,$orderBy); 
    } 
*/    
    
    echo json_encode($classArray)."!~~!".json_encode($subjectArray)."!~~!".json_encode($employeeArray);    
?>
