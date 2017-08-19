<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transaction
define('MANAGEMENT_ACCESS',1);
define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
$fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();

$userId=$sessionHandler->getSessionVariable('UserId');  
$instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
$sessionId=$sessionHandler->getSessionVariable('SessionId'); 

$classId='Null';
$roleId=4;
$visibleFrom='Null';
$visibleTo='Null';

$insQuery=""; 

//first check whether these employees give feedback or not.if yes then do not perform any action
$timeTableId=trim($REQUEST_DATA['timeTableLabelId']);
$labelId=trim($REQUEST_DATA['labelId']);
$catId=trim($REQUEST_DATA['catId']);
$questionSetId=trim($REQUEST_DATA['questionSetId']);
$feedBackTypeFlag=trim($REQUEST_DATA['feedBackTypeFlag']);
if($feedBackTypeFlag==4){
    $classId=trim($REQUEST_DATA['selectedClassId']); 
    $subjectIds=explode(',',trim($REQUEST_DATA['selectedSubjectIds']));
}

if($feedBackTypeFlag==4){
 if($classId=='' or trim($REQUEST_DATA['selectedSubjectIds'])==''){
    echo 'Required Parameters Missing';
    die;
 }   
}

if($timeTableId=='' or $labelId=='' or $catId=='' or $questionSetId=='' or $feedBackTypeFlag==''){
    echo 'Required Parameters Missing';
    die;
}

$userIds=explode(",",trim($REQUEST_DATA['student']));
$cnt=count($userIds);
$excludedUsers=-1;
$excludedUserIdArray =array();
$operationFl=0;


//get mappingId and userId from survey_answer table
$mappingId=-1;
if($feedBackTypeFlag!=4){
 $retArray=$fbManager->getGivenFeedbackQuestions($labelId,$catId,$questionSetId,$roleId);
}
else{
    $retArray=$fbManager->getGivenFeedbackQuestions($labelId,$catId,$questionSetId,$roleId,$classId,trim($REQUEST_DATA['selectedSubjectIds']));
}
if(count($retArray)>0 and is_array($retArray)){
    $mappingId=$retArray[0]['feedbackMappingId'];
    $excludedUserIdArray =explode(',',UtilityManager::makeCSList($retArray,'userId'));
    if(count($excludedUserIdArray)>0){
       $excludedUsers .=','.implode(',',$excludedUserIdArray); //no delete operation will be performaed on these users
       $operationFl=1;
    }
}
//if class<->subject mapping needed then fetch old mappingId
$oldMappingId=-1;
if($feedBackTypeFlag==4){ 
    //get the old mappingId corresponding to label,cat,questionSet,class and role
    $oldArray=$fbManager->getOldMappingId($labelId,$catId,$questionSetId,$classId,$roleId,trim($REQUEST_DATA['selectedSubjectIds']));
    if(is_array($oldArray) and count($oldArray)>0){
        if($oldArray[0]['feedbackMappingId']!=''){
         $oldMappingId=$oldArray[0]['feedbackMappingId'];
        }
    }
}
 
 //start the transaction
 if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
   $allStudents=trim($REQUEST_DATA['allStudentString']);
   if($allStudents==''){
       die(NO_STUDENT_SELECTED);
   }
   //first delete from visible_to_user table
  if($feedBackTypeFlag!=4){
   $ret=$fbManager->deleteAssignedUsersData($allStudents,$labelId,$catId,$questionSetId,$roleId,$excludedUsers);
  }
  else{
      $ret=$fbManager->deleteAssignedUsersData($allStudents,$labelId,$catId,$questionSetId,$roleId,$excludedUsers,$classId,trim($REQUEST_DATA['selectedSubjectIds']));
  }
  if($ret==false){
       echo FAILURE;
       die;
  }
  
  if($mappingId==-1){
      //check for already existing mapping ids
       if($feedBackTypeFlag!=4){
        $extMappingArray=$fbManager->getExistingMappingId($labelId,$catId,$questionSetId,$roleId);
       }
       else{
        $extMappingArray=$fbManager->getExistingMappingId($labelId,$catId,$questionSetId,$roleId,$classId,trim($REQUEST_DATA['selectedSubjectIds']));
       }
       if(is_array($extMappingArray) and count($extMappingArray)>0){
           $mappingId=$extMappingArray[0]['feedbackMappingId'];
       }
  } 
   
   //then delete data from mapping table(IF its not used in answer table)
   if($mappingId==-1){
       if($feedBackTypeFlag!=4){
         $ret=$fbManager->deleteMappingData($labelId,$catId,$questionSetId,$roleId);
       }
       else{
          $ret=$fbManager->deleteMappingData($labelId,$catId,$questionSetId,$roleId,$classId,trim($REQUEST_DATA['selectedSubjectIds'])); 
       }
       
       if($ret==false){
           echo FAILURE;
           die;
       }
        
      //now insert fresh records into mapping table 
      $ret=$fbManager->insertMappingData($labelId,$catId,$questionSetId,$classId,$roleId,$visibleFrom,$visibleTo);
      if($ret==false){
        echo FAILURE;
        die;
      }
     //get the last inserted id
     $mappingId=SystemDatabaseManager::getInstance()->lastInsertId();
   }
   
   //delete from class<->subject mapping table and then make fresh insert
    if($feedBackTypeFlag==4){
        //delete from class<->subject mapping table
        $ret=$fbManager->deleteClassSubjectMapping($oldMappingId);
        if($ret==false){
           echo FAILURE;
           die; 
        }
        
        //now make fresh insert in class<->subject mapping table
        $subjectInsertString="";
        $subjectCnt=count($subjectIds);
        for($i=0;$i<$subjectCnt;$i++){
            if(trim($subjectIds[$i])==''){
                continue;
            }
            if($subjectInsertString!=""){
                $subjectInsertString .=" , ";
            }
            $subjectInsertString .=" ( $mappingId , $subjectIds[$i], $classId ) ";
        }
        if($subjectInsertString!=""){
            $ret=$fbManager->doClassSubjectMapping($subjectInsertString);
            if($ret==false){
               echo FAILURE;
               die; 
            }
        }
    }
   
  //now insert new records in survey_visble_to_users table
  $insFlag=0;
  if($cnt>0 and is_array($userIds)){
      for($i=0;$i<$cnt;$i++){
          if($userIds[$i]==''){
              continue;
          }
          if(in_array($userIds[$i],$excludedUserIdArray)){
              continue; //do not insert excluded users
          }
        if($insQuery!=''){
            $insQuery .=" , ";
        }
	// Fetch student Id (Start)
	$studentCondition = " userId = '".$userIds[$i]."'";
	$retStudent=$fbManager->getCheckUserStudent($studentCondition);
            if($retStudent==false){
               echo FAILURE;
               die; 
             }
	$studentId = $retStudent[0]['studentId'];
	// End
        $insQuery .= "($mappingId,$userIds[$i],$roleId,$classId,$studentId) ";
        $insFlag=1;
      }
  }
  
  if($insFlag == 1){
      $ret=$fbManager->insertSurveyVisibleToUsersData($insQuery);
      if($ret==false){
         echo FAILURE;
         die;
      }
  }
  
  //insert into feedback student status table
  $userIdString='';
  $newUserIdArray1=array();
  for($i=0;$i<$cnt;$i++){
     if($userIds[$i]==''){
        continue;
     }
     if($userIdString!=''){
         $userIdString .=',';
     }
     $userIdString      .= trim($userIds[$i]);
     $newUserIdArray1[]  = trim($userIds[$i]);
  }
  if($userIdString!=''){
    $studentStatusArray=$fbManager->getFeedbackStudentStatus(' WHERE userId IN ( '.$userIdString.' )');
    //if some/all selected userIds are already in status table
    $newUserIdString='';
    if(is_array($studentStatusArray) and count($studentStatusArray)>0){
       $newUserIdArray2 = array_unique(explode(',',UtilityManager::makeCSList($studentStatusArray,'userId')));
       $newUserIdArray3 = array_diff($newUserIdArray1,array_unique($newUserIdArray2));
       $newUserCnt=count($newUserIdArray3);
       for($i=0;$i<$newUserCnt;$i++){
          if(trim($newUserIdArray3[$i])==''){
              continue;
          }
          if($newUserIdString!=''){
              $newUserIdString .=',';
          }
          $newUserIdString .=" ( ".trim($newUserIdArray3[$i])." , ".FEEDBACK_STUDENT_BLOCKED." )";
       }
    }
    else{
      for($i=0;$i<$cnt;$i++){
         if(trim($userIds[$i])==''){
           continue;
         }
         if($newUserIdString!=''){
           $newUserIdString .=',';
         }
         $newUserIdString .=" ( ".trim($userIds[$i])." , ".FEEDBACK_STUDENT_BLOCKED." )";
       }  
    }
    if($newUserIdString!=''){
       $ret=$fbManager->insertFeedbackStudentStatus($newUserIdString);
       if($ret==false){
           echo FAILURE;
           die;
       }
    }
  }
  
       
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
       echo SUCCESS;
       die;
  }
 else {
   echo FAILURE;
   die;
  }
 }
 else {
  echo FAILURE;
  die;
 }

// $History: ajaxAssignStudent.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:06
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Created the repoort for showing student status for feedbacks
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>
