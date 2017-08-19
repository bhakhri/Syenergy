<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A UNIVERSITY
// Author : Dipanjan Bhattacharjee
// Modified By: Pushpender Kumar Chauhan
// Created on : (14.06.2008 )
//modified on: 20.06.2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementGenerateStudentList');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    $studentString=trim($REQUEST_DATA['studentString']);
    
    if($placementDriveId==''){
        die(SELECT_PLACEMENT_DRIVE);
    }
    if($studentString==''){
        die(NO_DATA_SUBMIT);
    }
    
    require_once(MODEL_PATH . "/Placement/StudentUploadManager.inc.php");
    $studentManager=StudentUploadManager::getInstance();
    
     require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
     if(SystemDatabaseManager::getInstance()->startTransaction()) {
       
       //check whether this placement drive is in result table
       $resultArray=$studentManager->checkInPlacementResult($placementDriveId);
       if($resultArray[0]['found']!=0){
          die("Records can not be updated as result corresponding to this placement drive exits");
       }
       $studentArray=explode(',',$studentString);
       $studentArray=array_values(array_unique($studentArray));
       $studentIds='';
       $cnt=count($studentArray);
       for($i=0;$i<$cnt;$i++){
          if($studentIds!=''){
              $studentIds .=',';
          }
          $student=explode('_',$studentArray[$i]);
          if(trim($student[0])==''){
              die('Student Information Missing');
          }
          
          if(trim($student[1])!='1' and trim($student[1])!='0'){
              die('Student Status Information Missing');
          }
          
          $studentIds .=trim($student[0]);
       }
       $uniqueStudentIdArray=array_unique(explode(',',$studentIds));
       $uniqueCount=count($uniqueStudentIdArray);
       $studentIds=implode(',',$uniqueStudentIdArray);
       
       //check for these students in student table whether these students belongs to
       //this placement drive
       $studentFoundArray=$studentManager->checkStudents($placementDriveId,$studentIds);
       if($studentFoundArray[0]['found']!=$uniqueCount){
           die('Some of the selected students does not belong to this placement drive');
       }
       
       //now delete data corresponding to these students
       $ret=$studentManager->deleteEligableStudents($placementDriveId,$studentIds);
       if($ret==false){
           die(FAILURE);
       }
       
       //now make fresh insert for students who are selected i.e,checked
       $studentArr2=array();
       $insertString='';
       for($i=0;$i<$cnt;$i++){
         $student=explode('_',$studentArray[$i]);
         if(in_array(trim($student[0]),$studentArr2)){
             continue;
         }
         else{
             $studentArr2[]=trim($student[0]);
         }
         
         if($student[1]==0){
             continue;
         }
         
         if($insertString!=''){
             $insertString .=',';
         }
         $insertString .= " ( $placementDriveId, ".trim($student[0])." ) ";
       }
      
       if($insertString!=''){
          $ret=$studentManager->insertEligibleStudents($insertString);
          if($ret==false){
              die(FAILURE);
          }
       }   
         
         
      if(SystemDatabaseManager::getInstance()->commitTransaction()) {
       die(SUCCESS);
      }
      else {
        die(FAILURE);
      }
     }
     else {
      die(FAILURE);
     }

// $History: ajaxInitAdd.php $
?>