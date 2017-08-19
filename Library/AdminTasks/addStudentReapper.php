<?php
//-------------------------------------------------------
// Purpose: To store the records of Student Re-apper Subject from the database functionality
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    define('MODULE','DisplayStudentReappear');  
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);  
    UtilityManager::headerNoCache();
   
    //echo "<pre>";
    //print_r($REQUEST_DATA);
    //die;
    
    $reappearStatusId1 = add_slashes($REQUEST_DATA['reappearStatusId1']);   
    $reappearStatusId  = add_slashes($REQUEST_DATA['reappearStatusId']);   
    $studentDetained  = add_slashes($REQUEST_DATA['studentDetained']);   
      
    $errorMessage ='';      
    
    if($reappearStatusId1=='') {
      $reappearStatusId1 ='(0,0,0,0)';  
    }
    
    if($reappearStatusId=='') {
      $reappearStatusId ='(0,0,0,0)';  
    }

    if($studentDetained=='') {
      $studentDetained ='(0,0,0,0)';  
    }

    global $sessionHandler;
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $dateOfApproved=date('Y-m-d');
    
   //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
    
     //$studentDetails = $studentRecordArray[$i]['studentId'].",".$curr.",".$reap;  
     for($i=0;$i<count($REQUEST_DATA['reappearStatusId1']);$i++) {
       $sDetail = $REQUEST_DATA['reappearStatusId1'][$i];  
       $rStatus = $REQUEST_DATA['reappearStatusId'][$i];
       if($sDetail==0 && $sDetail=='') {
         $sDetail ='(0,0,0,0)';  
       }
       if($rStatus=='' && $rStatus ==0) {
         $rStatus = 0;  
       }
       $where = " WHERE (studentId,currentClassId,reapperClassId,subjectId) IN ($sDetail) AND instituteId = $instituteId";  
       $filedName = "dateOfApproved='$dateOfApproved', reppearStatus=$rStatus, detained='N' ";
       $returnStatus = $studentManager->editReapperSubject($filedName,$where); 
       if($returnStatus === false) {
          echo FAILURE;
          die;
       }
     }
     
     for($i=0;$i<count($REQUEST_DATA['studentDetained']);$i++) {
       $sDetail = $REQUEST_DATA['studentDetained'][$i]; 
       if($sDetail==0 && $sDetail=='') {
         $sDetail ='(0,0,0,0)';  
       }
       $where = " WHERE (studentId,currentClassId,reapperClassId,subjectId) IN ($sDetail) AND instituteId = $instituteId";  
       $filedName = "dateOfApproved='$dateOfApproved', detained='Y'";
       $returnStatus = $studentManager->editReapperSubject($filedName,$where); 
       if($returnStatus === false) {
          echo FAILURE;
          die;
       }
     }
     
    //*****************************COMMIT TRANSACTION************************* 
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        $errorMessage =  SUCCESS;
     }
     else {
        $errorMessage =  FAILURE;
    }
  }
  else{
     $errorMessage =  FAILURE;  
  }
    
  echo $errorMessage;
  die;
    
    
// for VSS
// $History: addStudentReapper.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/14/10    Time: 5:15p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//
?>
