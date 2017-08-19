<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fee Cycle Classes
// Author : Parveen Sharma
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance(); 
    
 
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
/*$ 
    feeCycleId = $REQUEST_DATA['feeCycleId'];
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
*/
    if($rollNo=='') {
      $rollNo=0;  
    }
    $classCondition = '';
    $condition = " AND (s.rollNo = '$rollNo' OR s.regNo = '$rollNo'  OR s.universityRollNo = '$rollNo' ) ";
    $foundArray = $collectStudentFeeManager->getStudentClassDetail($condition);
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
    
    
    //$foundArray = $collectStudentFeeManager->getFeeCycleClasses($condition);
    $foundArray = $collectStudentFeeManager->getFeeReceiptClasses($classCondition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }  
?>