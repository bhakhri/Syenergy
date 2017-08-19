<?php
//-------------------------------------------------------
// Purpose: To store the records of Student Re-apper Subject from the database functionality
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
  
      
    $errorMessage ='';      
    
    if($studentId=='') {
      echo "Please select atleast one student record";   
      die();
    }
    
    
    
   global $sessionHandler;
    
   $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
     //$studentDetails = $studentRecordArray[$i]['studentId'].",".$curr.",".$reap;  
     $deleteCondition = " WHERE (studentId,currentClassId,reapperClassId) IN ($studentId) AND instituteId = $instituteId";  
     $returnStatus = $studentManager->deleteReapperSubject($deleteCondition); 
     if($returnStatus === false) {
        echo FAILURE;
        die;
     }
     else {
        $errorMessage = DELETE;   
     }
        
     //*****************************COMMIT TRANSACTION************************* 
     if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        $errorMessage =  DELETE;
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
// $History: ajaxAdminDeleteApproval.php $
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
