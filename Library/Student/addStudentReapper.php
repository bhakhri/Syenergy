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
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn();  
    UtilityManager::headerNoCache();
   
    $studentId = add_slashes($REQUEST_DATA['studentId']);   
    $reappearClassId   = add_slashes($REQUEST_DATA['classId']);   
    $subjectId  = add_slashes($REQUEST_DATA['subjectId']);   
    $reappearId  = add_slashes($REQUEST_DATA['reappearId']);   
    $currentClassId = add_slashes($REQUEST_DATA['currentClassId']);   
    
    $assignmentChk  = add_slashes($REQUEST_DATA['assignmentChk']);   
    $midSemesterChk  = add_slashes($REQUEST_DATA['midSemesterChk']);   
    $attendanceChk  = add_slashes($REQUEST_DATA['attendanceChk']);   
    $msg = add_slashes($REQUEST_DATA['msg']);   
     
	if($sessionHandler->getSessionVariable('SuperUserId')!=''){
		echo ACCESS_DENIED;
		die;
	}

    $errorMessage ='';      
    if($reappearClassId == '') {  
      $errorMessage = SELECT_CLASS;
      die();
    }
   
    if($studentId=='') {
      die();
    }
    
    if($reappearClassId=='') {
      die();
    }
    
    if($currentClassId=='') {
      die();
    }
   
    if($assignmentChk=='') {
      $assignmentChk = 0;
    }
   
    if($midSemesterChk=='') {
      $midSemesterChk = 0;
    }
    
    if($attendanceChk=='') {
      $attendanceChk = 0;
    }
    
    global $sessionHandler;
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $dateOfEntry=date('Y-m-d');
    
    $subjectIdArr = explode(',',$subjectId);
    $cnt = count($subjectIdArr);
    
    $str = '';
    for($i = 0; $i < $cnt; $i++) {
        $subject = $subjectIdArr[$i];
        $reppearStatus = 3;
        if($str!='') {
            $str .= ',';
        }                                            
        // studentId, reapperClassId, $subject, dateOfEntry, reppearStatus, 
        // currentClassId, assignmentStatus, midSemesterStatus, attendanceStatus, instituteId
        $str .= "($studentId, $reappearClassId, $subject, '$dateOfEntry', $reppearStatus,
                  $currentClassId, $assignmentChk, $midSemesterChk, $attendanceChk, $instituteId )";
    }
   
   
  //****************************************************************************************************************    
  //***********************************************STRAT TRANSCATION************************************************
  //****************************************************************************************************************
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      
    if($subjectId=='' && $reappearId=='') {
       $deleteCondition = " WHERE (studentId,reapperClassId,reppearStatus,currentClassId) = ($studentId,$reappearClassId,3,$currentClassId) AND instituteId = $instituteId ";
       $returnStatus = $studentManager->deleteReapperSubject($deleteCondition);
       if($returnStatus === false) {
         echo FAILURE;
         die;
       }
    }  
    else {
        $reapperClassId  = $reappearClassId;
        $condition = " AND sr.currentClassId=$currentClassId AND sc.classId=$reappearClassId AND reppearStatus='3' AND sr.instituteId = $instituteId ";
        $studentRecordArray = $studentManager->getStudentReappearDetails($condition,$orderBy,$reapperClassId);
        $ids = UtilityManager::makeCSList($studentRecordArray, 'reappearId', ',');        
        if($ids=='') {
          $ids=0;   
        }  
          
        $deleteCondition = " WHERE reappearId IN ($ids) ";  
        $returnStatus = $studentManager->deleteReapperSubject($deleteCondition);
        if($returnStatus === false) {
         echo FAILURE;
         die;
        }
        else {
          $returnStatus = $studentManager->addReapperSubject($str);
          if($returnStatus === false) {
            echo FAILURE;
            die;
          }
          else {
            $reapperClassId  = $reappearClassId;   
            $condition = " AND sc.classId = $reappearClassId  AND sr.instituteId = $instituteId ";
            $studentRecordArray = $studentManager->getStudentReappearDetails($condition,$orderBy,$reapperClassId);
            $ids = "0";
            for($i=0; $i < count($studentRecordArray); $i++) {
               if($studentRecordArray[$i]['reappearId']!='') { 
                 $ids .= ",".$studentRecordArray[$i]['reappearId'];
               }
            }
            $where = " WHERE reappearId IN ($ids) ";
            $filedName = "dateOfEntry='$dateOfEntry',assignmentStatus=$assignmentChk,midSemesterStatus=$midSemesterChk,attendanceStatus=$attendanceChk";
            $returnStatus = $studentManager->editReapperSubject($filedName,$where); 
            if($returnStatus === false) {
               echo FAILURE;
               die;
            }
          }
        }
    }
    //*****************************COMMIT TRANSACTION************************* 
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        $errorMessage =  REGISTRATION_SUBMITTED;
        if($subjectId=='' && $reappearId=='') { 
          $errorMessage =  REGISTRATION_DELETE;
        }
        else if($msg==1) {
          $errorMessage =  REGISTRATION_UPDATED;  
        }
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
//*****************  Version 5  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Library/Student
//function & validation message and format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/13/10    Time: 2:12p
//Updated in $/LeapCC/Library/Student
//subjectId base checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/12/10    Time: 6:16p
//Updated in $/LeapCC/Library/Student
//format validation updated
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
