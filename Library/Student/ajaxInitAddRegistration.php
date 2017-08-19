<?php
//-------------------------------------------------------
// Purpose: To store the data of student registration form
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
       // student login      
       $confirmId=$REQUEST_DATA['confirmId'];
       if($confirmId=='') {
         $confirmId = 'N'; 
       }
    }
    else {
       $confirmId=''; 
       UtilityManager::ifNotLoggedIn(true);   
    }
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    global $sessionHandler;
    
    
    $regDate=date('Y-m-d h:i:s A');
    
    $cgpa=$REQUEST_DATA['cgpa'];
    $majorConcentration=add_slashes($REQUEST_DATA['majorConcentration']);

    
    $totalClass = add_slashes($REQUEST_DATA['totalClass']);  
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $roleId = $sessionHandler->getSessionVariable('RoleId');  
    
    if($roleId==4) { 
       $studentId = $sessionHandler->getSessionVariable('StudentId');
       $currentClassId = $sessionHandler->getSessionVariable('ClassId');
    }
    else {
      $studentId = $REQUEST_DATA['studentId'];
      $currentClassId = $REQUEST_DATA['currentClassId']; 
    }
    
    if($studentId=='') {
      $studentId=0;  
    }
    
    if($currentClassId=='') {
      $currentClassId=0; 
    }
    
    if($totalClass=='') {
      $totalClass=0;  
    }
    
    

    // $results[$i]['subjectId']."~".$results[$i]['credits']."~".$results[$i]['classId'];
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) { 
       
       $condition = " AND m.studentId = $studentId AND m.currentClassId=$currentClassId";
       $filter = "DISTINCT 
                           m.registrationId, m.studentId, m.currentClassId, m.instituteId, m.cgpa, m.majorConcentration,
                           m.confirmId, m.registrationDate, DATE_FORMAT(m.registrationDate,'%Y-%m-%d') AS regDate"; 
       $foundArray = $studentManager->getStudentRegistration($condition,'',$filter);    
       $registrationId=-1;
       
       
       $errorMsg='';
       $condition='';
       $queryFormat = "INSERT";
       if(count($foundArray)>0) {
          $queryFormat = "UPDATE";
          $registrationId = $foundArray[0]['registrationId'];  
          $condition = " WHERE studentId = $studentId AND currentClassId=$currentClassId AND registrationId=$registrationId";
          $returnStatus = $studentManager->deleteStudentRegistration("`student_registration_detail`"," WHERE registrationId=$registrationId");
          if($returnStatus===false) {
            echo FAILURE;
            die;
          }
          if($sessionHandler->getSessionVariable('RoleId')!=4) {
            if($foundArray[0]['registrationDate']!='0000-00-00 00:00:00') { 
               $regDate="";  
            }
          }
          $errorMsg = REGISTRATION_UPDATED;
       }
       $returnStatus = $studentManager->editStudentRegistration($queryFormat,$studentId,$currentClassId,$instituteId,$cgpa,$majorConcentration,$condition,$confirmId,$regDate);  
       if($returnStatus===false) {
         echo FAILURE;
         die;
       }
       if($registrationId==-1) {
         $lastInsertId=SystemDatabaseManager::getInstance()->lastInsertId();
       }
       else {
         $lastInsertId=$registrationId;  
       }
     
       
       $queryList = '';
       for($i=0;$i<$totalClass;$i++) { 
          // Carrer Course
          $id1='careerCourseId'.($i+1);
          $id2='careerCreditId'.($i+1);
          $tClassId = 'tClassId'.($i+1);
          if(count($REQUEST_DATA[$id1])>0) {
             $insertList1 .= getCourseInformation($REQUEST_DATA[$tClassId],$REQUEST_DATA[$id1],$REQUEST_DATA[$id2],$lastInsertId,'Career');
          }
          
          // Elective Course
          $id1='electiveCourseId'.($i+1);
          $id2='electiveCreditId'.($i+1);
          
          if(count($REQUEST_DATA[$id1])>0) { 
            $insertList2 .= getCourseInformation($REQUEST_DATA[$tClassId],$REQUEST_DATA[$id1],$REQUEST_DATA[$id2],$lastInsertId,'Elective');
          }
       }
       $queryList = $insertList1 . $insertList2;
       
       if($queryList!='') {
          $filter = substr($queryList,0,strlen($queryList)-1).';';
          $returnStatus = $studentManager->addStudentRegistration($filter); 
       }
       else {
          $returnStatus = $studentManager->deleteStudentRegistration("`student_registration_master`"," WHERE registrationId=$registrationId");  
          $errorMsg = REGISTRATION_DELETE;
       }
       if($returnStatus===false) {
         echo FAILURE;
         die;
       }
       
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         $errorMessage = REGISTRATION_SUBMITTED;
       }
       else {
         $errorMessage = FAILURE;
         die;
       } 
    }
    if($errorMsg=='') {
       echo $errorMessage;
    } 
    else {
       echo $errorMsg;
    }
    die;


    function getCourseInformation($tClassId,$courseList,$creditList,$lastInsertId,$subjectType) {
       $filter=''; 
       for($j=0;$j<count($creditList);$j++) {
         $classId=$tClassId;
         $subjectId=$courseList[$j];
         $credit = $creditList[$j];
         $filter .= "('".$lastInsertId."','".$classId."','".$subjectId."','".$credit."','".$subjectType."'),"; 
       }
       return $filter;
    }
        
// for VSS
// $History: ajaxInitAddRegistration.php $
//
?>
