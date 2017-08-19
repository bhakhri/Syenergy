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
$roleId=2;
$visibleFrom='Null';
$visibleTo='Null';

$insQuery=""; 

//first check whether these employees give feedback or not.if yes then do not perform any action
$timeTableId=trim($REQUEST_DATA['timeTableLabelId']);
$labelId=trim($REQUEST_DATA['labelId']);
$catId=trim($REQUEST_DATA['catId']);
$questionSetId=trim($REQUEST_DATA['questionSetId']);

if($timeTableId=='' or $labelId=='' or $catId=='' or $questionSetId==''){
    echo 'Required Parameters Missing';
    die;
}



$excludedUsers=-1;
$excludedUserIdArray =array();
$operationFl=0;

//get mappingId and userId from survey_answer table
$mappingId=-1;
$retArray=$fbManager->getGivenFeedbackQuestions($labelId,$catId,$questionSetId,$roleId);
if(count($retArray)>0 and is_array($retArray)){
    $mappingId=$retArray[0]['feedbackMappingId'];
    $excludedUserIdArray =explode(',',UtilityManager::makeCSList($retArray,'userId'));
    if(count($excludedUserIdArray)>0){
       $excludedUsers .=','.implode(',',$excludedUserIdArray); //no delete operation will be performaed on these users
       $operationFl=1;
    }
}
 $allEmployees=trim($REQUEST_DATA['employee']);
/* if($allEmployees==''){
       die(NO_EMPLOYEE_SELECTED);
   }*/
 $userIds=explode(",",trim($REQUEST_DATA['employee']));
 $cnt=count($userIds);
 
 //start the transaction
 if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
   //first delete from visible_to_user table
   $ret=$fbManager->deleteAssignedUsersData($allEmployees,$labelId,$catId,$questionSetId,$roleId,$excludedUsers);
   if($ret==false){
       echo FAILURE;
       die;
   }
   
   //then delete data from mapping table(IF its not used in answer table)
   if($mappingId==-1){
         $ret=$fbManager->deleteMappingData($labelId,$catId,$questionSetId,$roleId);
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
        $insQuery .= " ( $mappingId,$userIds[$i],$roleId ) ";
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

// $History: ajaxAssignEmployee.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>