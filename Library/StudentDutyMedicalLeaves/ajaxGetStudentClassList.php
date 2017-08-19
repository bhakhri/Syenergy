<?php
//-------------------------------------------------------
// Purpose: To get classes of a particular student
//
// Author : Kavish Manjkhola
// Created on : 5/3/2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ini_set("memory_limit","250M");       
set_time_limit(0);
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','studentDutyLeaveReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
    $dutyManager = DutyLeaveManager::getInstance();
    
 
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));

    if($rollNo=='') {
      $rollNo=0;
    }
    $classCondition = '';
    $condition = " AND (s.rollNo = '$rollNo' OR s.regNo = '$rollNo'  OR s.universityRollNo = '$rollNo' ) ";
    $foundArray = $dutyManager->getStudentClassDetail($condition);
    $isLeet = $foundArray[0]['isLeet'];  
    $isMigration = $foundArray[0]['isMigration'];  
    $migrationClassId = $foundArray[0]['migrationClassId'];
    
    if($migrationClassId=='') {
      $migrationClassId=0;  
    }
    
    if(is_array($foundArray) && count($foundArray)>0 ) {    
       $classCondition = " AND c.batchId =  '".$foundArray[0]['batchId']."' AND c.degreeId = '".$foundArray[0]['degreeId']."'  
                           AND c.branchId = '".$foundArray[0]['branchId']."'";
    }
    else {
       echo 0;
       die; 
    }

    if($isMigration=='1') {
      $classCondition .= " AND c.classId >= $migrationClassId ";
    } 
    else if($isLeet=='1') {
      $classCondition .= " AND sp.periodValue >=3 ";  
    }
    
    
    $foundArray = $dutyManager->getPreviousClasses($classCondition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
