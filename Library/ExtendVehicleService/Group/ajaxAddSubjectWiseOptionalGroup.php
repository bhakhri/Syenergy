<?php
//-------------------------------------------------------
// Purpose: To create a subject wise optional group.
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set('MEMORY_LIMIT','5000M'); 
set_time_limit(0);  
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/OptionalSubjectGroupManager.inc.php");
$groupManager = OptionalSubjectGroupManager::getInstance();
  

global $sessionHandler;
$sessionId = $sessionHandler->getSessionVariable('SessionId');
$instituteId = $sessionHandler->getSessionVariable('InstituteId');

    
    $chb = $REQUEST_DATA['chb'];
    
    $errorMessage ='';
    /*
      if(trim($chb) == '') {  
        $errorMessage = SELECT_ATLEASTONE_CHECKBOX;
      }
    */
    
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              for($i=0;$i<count($chb);$i++) {
                  $listArray = explode('~',$chb[$i]);
                  $classId      = $listArray[0];
                  $subjectId    = $listArray[1];
                  $groupTypeId  = $listArray[2];
                  $subjectCode  = $listArray[3];
                  $groupId      = $listArray[4];
                  $statusId     = $listArray[5]; 
                  
                  // Findout Student List
                  $condition = " AND d.classId = $classId AND d.subjectId = $subjectId ";
                  $fieldValue ='';
                  $studentArray = $groupManager->getRegistrationStudentList($condition);    
                  $ttGroupId=0;
                  $ttStudentId=0;
                  for($j=0;$j<count($studentArray);$j++) {  
                     $studentId = $studentArray[$j]['studentId']; 
                     if($fieldValue!='') {
                       $fieldValue .= ",";  
                       $ttStudentId .= ",";  
                     }
                     $fieldValue  .= "($subjectId, $studentId, $classId, $id)";
                     $ttGroupId   .= ",".$studentArray[$j]['groupId'];
                     $ttStudentId .= ",".$studentArray[$j]['studentId'];
                  }
                      
                  if($statusId=='N') {  
                      // Check classes visible to role  Table
                      $condition = " classId = $classId AND groupId IN ($ttGroupId) ";  
                      $studentArray = $groupManager->getClassVisibleRole($condition);    
                      if(count($studentArray) > 0) {
                        echo "Group cannot deallocation ".$subjectCode;
                        die;  
                      }
                      
                      
                      // Check Time Table
                      $condition = " AND classId = $classId AND subjectId = $subjectId AND groupId IN ($ttGroupId) ";  
                      $studentArray = $groupManager->getTimeTableList($condition);    
                      if(count($studentArray) > 0) {
                        echo "Group cannot deallocation ".$subjectCode;
                        die;  
                      }
                      

                      // Check Test Table
                      $condition = " AND classId = $classId AND subjectId = $subjectId AND groupId IN ($ttGroupId) ";  
                      $studentArray = $groupManager->getCheckTestList($condition);    
                      if(count($studentArray) > 0) {
                        echo "Group cannot deallocation ".$subjectCode;
                        die;  
                      }
                      
                      
                      // Check Attendance Table
                      $condition = " classId = $classId AND subjectId = $subjectId AND groupId IN ($ttGroupId) ";  
                      $studentArray = $groupManager->getCheckAttendanceList($condition);    
                      if(count($studentArray) > 0) {
                        echo "Group cannot deallocation ".$subjectCode;
                        die;  
                      }
                      
                      
                      // Delete Student Optional Subject Group
                      $condition = " classId= $classId AND subjectId=$subjectId AND groupId IN ($ttGroupId) ";
                      $returnStatus = $groupManager->deleteStudentOptionalSubjectGroup($condition);
                      if($returnStatus === false) {
                        echo FAILURE;
                        die;
                      } 
                      
                      
                      // Delete Group
                      $condition = " classId= $classId AND optionalSubjectId=$subjectId AND isOptional=1 ";
                      $returnStatus = $groupManager->deleteOptionalSubjectGroup($condition);
                      if($returnStatus === false) {
                         echo FAILURE;
                         die;
                      }
                      
                      // Delete Group 
                      $condition = " classId= $classId AND optionalSubjectId IS NULL AND isOptional=1 AND groupName = $subjectCode ";
                      $returnStatus = $groupManager->deleteOptionalSubjectGroup($condition);
                      if($returnStatus === false) {
                         echo FAILURE;
                         die;
                      }
                  }
                  
                  
                  // Check Optional Group
                  if($statusId=='Y') {
                      $id = "-1";
                      $condition = " classId= $classId AND isOptional=1 AND  groupName = $subjectCode";
                      $returnStatus = $groupManager->checkOptionalSubjectGroup($condition);
                      if(is_array($returnStatus) && count($returnStatus)>0 ) {   
                        $id = $returnStatus[0]['groupId'];
                      }
                      
                      // Create a New Group
                      if($id=='-1') {
                         $returnStatus = $groupManager->addOptionalSubjectGroup($subjectCode,$subjectCode,$groupTypeId,$classId,1,$subjectId);
                         if($returnStatus === false) {
                           echo FAILURE;
                           die;
                         }
                         $id=SystemDatabaseManager::getInstance()->lastInsertId(); 
                      } 
                      else {
                         $grpCondition = " groupName = $subjectCode AND classId = '$classId' AND isOptional='1' AND groupTypeId = '$groupTypeId' ";  
                         $returnStatus = $groupManager->editOptionalSubjectGroup($grpCondition,$subjectId);
                         if($returnStatus === false) {
                           echo FAILURE;
                           die;
                         }  
                      }
                       
                     
                      for($j=0;$j<count($studentArray);$j++) {  
                        $studentId = $studentArray[$j]['studentId']; 
                        
                        // Check Student Optional Group
                        $status=0;
                        $condition = " classId= $classId AND subjectId=$subjectId AND studentId=$studentId AND groupId = '$id' ";    
                        $returnStatus = $groupManager->checkStudentOptionalSubjectGroup($condition);
                        if(is_array($returnStatus) && count($returnStatus)>0 ) {   
                           $status=1;
                        }
                         
                        if($status==0) {
                            // Insert Student Optional Subject Group
                            $returnStatus = $groupManager->addStudentOptionalSubjectGroup($subjectId, $studentId, $classId, $id);
                            if($returnStatus === false) {
                              echo FAILURE;
                              die;
                            } 
                        }
                        else {
                            $stuCondition =" groupId = '$id' AND studentId='$studentId' AND classId ='$classId' ";
                            // Insert Student Optional Subject Group
                            $returnStatus = $groupManager->editStudentOptionalSubjectGroup($stuCondition,$subjectId, $studentId, $classId, $id);
                            if($returnStatus === false) {
                              echo FAILURE;
                              die;
                            } 
                        }
                        
                     }
                  }
              }
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $errorMessage = SUCCESS;
              }
              else {
                $errorMessage =  FAILURE;
              }    
        }
    }
 
    echo $errorMessage;
 
// $History: ajaxQuotaSeatsAdd.php $
//

?>