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
define('MODULE','PlacementGenerateStudentResultList');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    $studentString1=trim($REQUEST_DATA['studentString1']); //for tests
    $studentString2=trim($REQUEST_DATA['studentString2']); //for gd
    $studentString3=trim($REQUEST_DATA['studentString3']); //for interview
    $studentString4=trim($REQUEST_DATA['studentString4']); //for selection
    
    if($placementDriveId==''){
        die(SELECT_PLACEMENT_DRIVE);
    }
    if($studentString4==''){
        die(NO_DATA_SUBMIT);
    }
    
    require_once(MODEL_PATH . "/Placement/StudentUploadManager.inc.php");
    $studentManager=StudentUploadManager::getInstance();
    
     require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
     if(SystemDatabaseManager::getInstance()->startTransaction()) {
       
       $studentArray4=explode(',',$studentString4);
       $studentArray4=array_values(array_unique($studentArray4));
       $studentIds='';
       $cnt=count($studentArray4);
       $cnt1=$cnt;
       for($i=0;$i<$cnt;$i++){
          if($studentIds!=''){
              $studentIds .=',';
          }
          $student=explode('_',$studentArray4[$i]);
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
       
       
       
       //now check for test,gd and interview
       $eliArray=$studentManager->getPlacementDriveCriteria($placementDriveId);
       
       if($eliArray[0]['isTest']==1){
           if($studentString1==''){
               die('Information regarding test missing');
           }
           $studentArray1=explode(',',$studentString1);
           $studentArray1=array_values(array_unique($studentArray1));
           $studentIds1='';
           $cnt=count($studentArray1);
           if($cnt!=$cnt1){
               die('Mismatched student information');
           }
           for($i=0;$i<$cnt;$i++){
            if($studentIds1!=''){
              $studentIds1 .=',';
            }
            $student=explode('_',$studentArray1[$i]);
            if(trim($student[0])==''){
              die('Student Information Missing');
            }
          
            if(trim($student[1])!='1' and trim($student[1])!='0'){
              die('Student Status Information Missing');
            }
          
            $studentIds1 .=trim($student[0]);
          }
          $uniqueStudentIdArray=array_unique(explode(',',$studentIds1));
          $uniqueCount=count($uniqueStudentIdArray);
          $studentIds=implode(',',$uniqueStudentIdArray);
       
         //check for these students in student table whether these students belongs to
         //this placement drive
         $studentFoundArray=$studentManager->checkStudents($placementDriveId,$studentIds1);
         if($studentFoundArray[0]['found']!=$uniqueCount){
           die('Some of the selected students does not belong to this placement drive');
         }
       }
        
       if($eliArray[0]['groupDiscussion']==1){
           if($studentString2==''){
               die('Information regarding group discussion missing');
           }
           $studentArray2=explode(',',$studentString2);
           $studentArray2=array_values(array_unique($studentArray2));
           $studentIds2='';
           $cnt=count($studentArray2);
           if($cnt!=$cnt1){
               die('Mismatched student information');
           }
           for($i=0;$i<$cnt;$i++){
            if($studentIds2!=''){
              $studentIds2 .=',';
            }
            $student=explode('_',$studentArray2[$i]);
            if(trim($student[0])==''){
              die('Student Information Missing');
            }
          
            if(trim($student[1])!='1' and trim($student[1])!='0'){
              die('Student Status Information Missing');
            }
          
            $studentIds2 .=trim($student[0]);
          }
          $uniqueStudentIdArray=array_unique(explode(',',$studentIds2));
          $uniqueCount=count($uniqueStudentIdArray);
          $studentIds2=implode(',',$uniqueStudentIdArray);
       
         //check for these students in student table whether these students belongs to
         //this placement drive
         $studentFoundArray=$studentManager->checkStudents($placementDriveId,$studentIds2);
         if($studentFoundArray[0]['found']!=$uniqueCount){
           die('Some of the selected students does not belong to this placement drive');
         } 
       }
        
       if($eliArray[0]['individualInterview']==1){
           if($studentString3==''){
               die('Information regarding interview individual missing');
           }
           $studentArray3=explode(',',$studentString3);
           $studentArray3=array_values(array_unique($studentArray3));
           $studentIds3='';
           $cnt=count($studentArray3);
           if($cnt!=$cnt1){
               die('Mismatched student information');
           }
           for($i=0;$i<$cnt;$i++){
            if($studentIds3!=''){
              $studentIds3 .=',';
            }
            $student=explode('_',$studentArray3[$i]);
            if(trim($student[0])==''){
              die('Student Information Missing');
            }
          
            if(trim($student[1])!='1' and trim($student[1])!='0'){
              die('Student Status Information Missing');
            }
          
            $studentIds3 .=trim($student[0]);
          }
          $uniqueStudentIdArray=array_unique(explode(',',$studentIds3));
          $uniqueCount=count($uniqueStudentIdArray);
          $studentIds3=implode(',',$uniqueStudentIdArray);
       
         //check for these students in student table whether these students belongs to
         //this placement drive
         $studentFoundArray=$studentManager->checkStudents($placementDriveId,$studentIds3);
         if($studentFoundArray[0]['found']!=$uniqueCount){
           die('Some of the selected students does not belong to this placement drive');
         }
       }
       
       
       
       //now delete data corresponding to these students
       $ret=$studentManager->deleteStudentResults($placementDriveId,$studentIds);
       if($ret==false){
           die(FAILURE);
       }
       
       //now make fresh insert for students who are selected i.e,checked
       $studentArr2=array();
       $insertString='';
       for($i=0;$i<$cnt1;$i++){
         $student=explode('_',$studentArray4[$i]);
         if(in_array(trim($student[0]),$studentArr2)){
             continue;
         }
         else{
             $studentArr2[]=trim($student[0]);
         }
         
         if($insertString!=''){
             $insertString .=',';
         }
         if($eliArray[0]['isTest']==1){
             $studentTest=explode('_',$studentArray1[$i]);
             $test=trim($studentTest[1]);
         }
         else{
             $test='NULL';
         }
         
         if($eliArray[0]['groupDiscussion']==1){
             $studentGD=explode('_',$studentArray2[$i]);
             $gd=trim($studentGD[1]);
         }
         else{
             $gd='NULL';
         }
         
         if($eliArray[0]['individualInterview']==1){
             $studentIntv=explode('_',$studentArray3[$i]);
             $intv=trim($studentIntv[1]);
         }
         else{
             $intv='NULL';
         }
         
         $insertString .= " ( $placementDriveId, ".trim($student[0]).",".trim($student[1]).",$test,$gd,$intv ) ";
       }
      
       if($insertString!=''){
          $ret=$studentManager->insertStudentResult($insertString);
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