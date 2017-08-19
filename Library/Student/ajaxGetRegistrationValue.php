<?php
//-------------------------------------------------------
// Purpose: This File contains Validation and ajax function used in Student Registration Form List
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    if($sessionHandler->getSessionVariable('RoleId')==4) {
       UtilityManager::ifStudentNotLoggedIn(true);  
    }
    else {
       UtilityManager::ifNotLoggedIn(true);   
    }
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    
    //$queryString =  $_SERVER['QUERY_STRING'];
    
    
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    if($roleId==4) {
       $studentId = $sessionHandler->getSessionVariable('StudentId');
       $currentClassId = $sessionHandler->getSessionVariable('ClassId');
    }
    else {
       $studentId = $REQUEST_DATA['studentId'];
       $currentClassId = $REQUEST_DATA['classId'];
    }
    
    if($studentId=='') {
      $studentId=0;  
    }
    
    if($currentClassId=='') {
      $currentClassId=0; 
    }
  
    $condition = " AND m.studentId = $studentId AND 
                   CONCAT_WS(',',c.batchId,c.branchId,c.degreeId) IN 
                   (SELECT CONCAT_WS(',',batchId,branchId,degreeId) FROM class WHERE classId='$currentClassId')";
    // m.currentClassId=$currentClassId "; 
    $foundArray = $studentManager->getStudentRegistration($condition);   
    
    
  /*
    if($roleId==4) {  
       $ssDate = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE_END_DATE');
       $sDate = explode('-',$ssDate); 
       $serverDate = explode('-',date('Y-m-d')); 
       $start_date=gregoriantojd($sDate[1], $sDate[2], $sDate[0]);
       $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
       $diff=$end_date-$start_date;
       if(count($foundArray)==0 || $diff >0) {
          $foundArray=0;  
       }
    }
  */ 
    
    echo json_encode($foundArray);       
    
    
// for VSS
// $History: ajaxGetRegistrationValue.php $
?>
