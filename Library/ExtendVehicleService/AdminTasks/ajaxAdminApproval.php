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
   
   
    //$reappearClassId = add_slashes($REQUEST_DATA['reappearClassId']); 
    //$rollNo = add_slashes($REQUEST_DATA['rollNo']); 
    //$startDate  = add_slashes($REQUEST_DATA['startDate']); 
    //$endDate  = add_slashes($REQUEST_DATA['endDate']); 

    // To Store studentId, Current ClassId, Re-appear ClassId
    $studentId = add_slashes($REQUEST_DATA['studentId']);   
    $reappearStatus  = add_slashes($REQUEST_DATA['reappearStatus']);   
    $studentDetained  = add_slashes($REQUEST_DATA['studentDetained']);   
    
    
    if($reappearStatus==-1) {
       $reappearStatus = 2; 
    }
    
    if($studentDetained==-1) {
       $studentDetained = 'N'; 
    }
      
    $errorMessage ='';      
    
    if($studentId=='') {
      echo "Please select atleast one student record";   
      die();
    }
    
    
    
    global $sessionHandler;
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $dateOfApproved=date('Y-m-d');
    
   
  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
     
     //$studentDetails = $studentRecordArray[$i]['studentId'].",".$curr.",".$reap;  
     $where = " WHERE (studentId,currentClassId,reapperClassId) IN ($studentId) AND instituteId = $instituteId";  
     $filedName = "dateOfApproved='$dateOfApproved', reppearStatus=$reappearStatus, detained='$studentDetained' ";
     $returnStatus = $studentManager->editReapperSubject($filedName,$where); 
     if($returnStatus === false) {
        echo FAILURE;
        die;
     }
     else {
        $errorMessage = SUCCESS;   
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
// $History: ajaxAdminApproval.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/21/10    Time: 11:00a
//Updated in $/LeapCC/Library/AdminTasks
//query format udpated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/21/10    Time: 10:58a
//Updated in $/LeapCC/Library/AdminTasks
//condition format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/14/10    Time: 5:15p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 1:52p
//Updated in $/LeapCC/Library/Student
//instituteId check added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:06p
//Created in $/LeapCC/Library/Student
//initial checkin
?>
