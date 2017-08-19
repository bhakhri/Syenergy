<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');

global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
   UtilityManager::ifTeacherNotLoggedIn(true); //for teachers
    $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();


$classId = trim($REQUEST_DATA['classId'] );
if($classId=='') { 
  $classId='0';
}

     $tableName ='';
     $condition = "";
    if($roleId=='2') {  
      $tableName = ",  ".TIME_TABLE_TABLE."  tt";  
      $condition = " AND tt.employeeId = '$employeeId' 
                     AND tt.subjectId = a.subjectId
                     AND tt.classId = a.classId ";  
    }

	$foundArray = $studentManager->getClassSubjectsTestTypes($classId,$condition,$tableName);


    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

?>
