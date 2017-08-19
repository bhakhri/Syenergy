<?php 
//  This File calls addFunction used in adding Book Records
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ExtraClassAttendance');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/ExtraClassAttendanceManager.inc.php");   
    $extraClassAttendanceManager = ExtraClassAttendanceManager::getInstance();
    
    $errorMessage ='';
    
    
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']); 
    $classId = trim($REQUEST_DATA['classId']); 
    $employeeId = trim($REQUEST_DATA['employeeId']); 
    $groupId = trim($REQUEST_DATA['groupId']); 
    $subjectId = trim($REQUEST_DATA['subjectId']); 
    $periodId = trim($REQUEST_DATA['periodId']); 
    $fromDate = trim($REQUEST_DATA['forDate']);
    
 
   $condition  = " classId = '$classId' AND groupId = '$groupId' AND employeeId= '$employeeId' AND fromDate = '$fromDate' AND ";
   $condition .= " subjectId = '$subjectId' AND periodId = '$periodId' AND timeTableLabelId= '$timeTableLabelId' ";
   $tableName ="extra_class ";
   $fieldName =" COUNT(*) AS cnt"; 
   $foundArray = $extraClassAttendanceManager->getFetchName($fieldName,$tableName,$condition);
   if($foundArray[0]['cnt'] > 0 ) {
     echo "Record already exist"; 
     die;
   }
   
   $returnStatus = $extraClassAttendanceManager->addExtraClassAttendance();
   if($returnStatus === false) {
        echo FAILURE;
   }
   else {
        echo SUCCESS;           
   }
 
?>

