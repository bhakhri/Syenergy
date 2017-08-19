<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE user_role table
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

echo "<title>Run This File Only Once.......ONCE...</title>";

echo  "<h1>Run This File Only Once.......ONCE...</h1>";

//die(ACCESS_DENIED);

/*
drop table if exists `aa`;
create table `aa` as 
select 
      distinct feedbackMappingId , classId,subjectId,groupId
from 
      feedbackadv_survey_answer
where classId is not null; 
*/


function dropTable(){
  $query="drop table if exists `aa`";
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
}

function createTable(){
  $query="create table `aa` as select distinct feedbackMappingId , classId,subjectId,groupId from feedbackadv_survey_answer where classId is not null";
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);  
}

function fetch1(){
    $query="SELECT DISTINCT CONCAT_WS('~',classId,groupId,subjectId) as con FROM feedbackadv_teacher_mapping";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

function fetch2($con){
    $query="SELECT DISTINCT feedbackMappingId FROM aa WHERE CONCAT_WS('~',classId,groupId,subjectId) IN ('$con')";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

function fetch3($feedbackMappingId){
    $query="SELECT DISTINCT feedbackSurveyId FROM feedbackadv_survey_mapping WHERE feedbackMappingId=$feedbackMappingId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

function update($feedbackSurveyId,$con){
    $query="UPDATE feedbackadv_teacher_mapping SET feedbackSurveyId=$feedbackSurveyId WHERE CONCAT_WS('~',classId,groupId,subjectId) IN ('$con')";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}
    
if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
   $ret=dropTable();
   if($ret==false){
       die(DATA_UNSAVED);
   }
   
   $ret=createTable(); //create tmeporarily table
   if($ret==false){
       die(DATA_UNSAVED);
   }
    
  $array1=fetch1(); //fetch from teacher_mapping
  $cnt1=count($array1);
  if($cnt1>0 and is_array($array1)){
    for($i=0;$i<$cnt1;$i++){
       $con=$array1[$i]['con']; 
       $array2=fetch2($con); //fetch from answer table
       $cnt2=count($array2);
       if($cnt2>0 and count($array2)){
           for($j=0;$j<$cnt2;$j++){
               $mappingId=$array2[$j]['feedbackMappingId'];
               $array3=fetch3($mappingId); //fetch from mapping table
               $surveyId=$array3[0]['feedbackSurveyId'];
               if($surveyId==''){
                   die('Survey Id not found');
               }
               $ret=update($surveyId,$con); //update teacher_mapping table
               if($ret==false){
                   die(DATA_UNSAVED);
               }
               echo ($i+1).'Survey Id Updated for '.$con.'<br/>';
           }
       }
       else{
           //die('nothing to copy !!!');
       } 
        
    }  
  }
  else{
      die('nothing to copy !!!');
  }
  
  $ret=dropTable();
  if($ret==false){
       die(DATA_UNSAVED);
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

?>