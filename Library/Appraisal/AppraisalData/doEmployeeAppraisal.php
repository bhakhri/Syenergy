<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
   die(ACCESS_DENIED);
}
define('MODULE','AppraisalForm');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if(SHOW_EMPLOYEE_APPRAISAL_FORM!=1){
  die(ACCESS_DENIED);
}
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
 UtilityManager::ifManagementNotLoggedIn();
}
else if($roleId!=1 and $roleId>5){
  UtilityManager::ifNotLoggedIn();    
}
else{
  die(ACCESS_DENIED);
}

UtilityManager::headerNoCache();

$selfString=trim($REQUEST_DATA['selfString']);
$cl=trim($REQUEST_DATA['cl']);
$el=trim($REQUEST_DATA['el']);
$pl=trim($REQUEST_DATA['pl']);
$sl=trim($REQUEST_DATA['sl']);
$lwp=trim($REQUEST_DATA['lwp']);
$lwpC=trim($REQUEST_DATA['lwpC']);
$overAll=add_slashes(trim($REQUEST_DATA['overAll']));

if($cl=='' or $el=='' or $pl=='' or $sl=='' or $lwp=='' or $lwpC=='' or $overAll==''){
    echo 'Data for leave tab is missing';
    die;
}

//checking for leaves
if(!is_numeric($cl) or !is_numeric($el) or !is_numeric($pl) or !is_numeric($sl) or !is_numeric($lwp) or !is_numeric($lwpC)){
    echo 'Enter numeric values';
    die;
}

require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();

$employeeId=$sessionHandler->getSessionVariable('EmployeeId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

if(SystemDatabaseManager::getInstance()->startTransaction()) {
   
   //at first delete leave data..and then make fresh insert.
   $ret=$appDataManager->deleteApprisalLeaveData($employeeId);
   if($ret==false){
       echo FAILURE;
       die;
   }
   
   $ret=$appDataManager->insertAppraisalLeaveData($employeeId,$cl,$el,$pl,$sl,$lwp,$lwpC,$overAll);
   if($ret==false){
       echo FAILURE;
       die;
   }
   
   
   //checking for appraisal data
   $appraisalDataArray=$appDataManager->getAppraisalFormData($employeeId,'HAVING disabledQuestions=1 ');
   $cnt=count($appraisalDataArray);
   if($cnt>1){
       $appraisalIds=UtilityManager::makeCSList($appraisalDataArray,'appraisalId');
       //create an array
       $appArray=array();
       $appHODArray=array();
       foreach($appraisalDataArray as $app){
          $appArray[$app['appraisalId']]=$app['appraisalWeightage'];
          if($app['hodEvaluation']==''){
              $app['hodEvaluation']=0;
          }
          $appHODArray[$app['appraisalId']]=$app['hodEvaluation'];
       }
       
       /*
       $selfAppArray=explode(',',$selfString);
       $cnt1=count($selfAppArray);
       if($cnt!=$cnt1){
           echo 'Mismatched Questions Count';
           die;
       }
       */
       /*
       $insertString='';
       for($i=0;$i<$cnt1;$i++){
           $valArr=explode('_',$selfAppArray[$i]);
           $appraisalId=trim($valArr[0]);
           $selfAppraisal=trim($valArr[1]);
           if($selfAppraisal==''){
               echo 'Please enter value';
               die;
           }
           if(!is_numeric($selfAppraisal)){
               echo 'Enter numeric value';
               die;
           }
           if(!array_key_exists($appraisalId,$appArray)){
              echo 'Mismatched Question Found';
              die; 
           }
           //checking for max. weightage
           if($selfAppraisal>$appArray[$appraisalId]){
               echo 'Maximum value for this field is '.$appArray[$appraisalId];
               die; 
           }
           
           if($insertString!=''){
              $insertString .=',';  
           }
           $insertString .= " ( $employeeId , $appraisalId,$selfAppraisal,$appHODArray[$appraisalId],$sessionId ) ";
           
       }
       
      if($insertString!=''){ 
          //now delete previous self appraisal data 
          $ret=$appDataManager->deleteSelfAppraisalData($employeeId,$sessionId);
          if($ret==false){
           echo FAILURE;
           die;
          }
          
          $ret=$appDataManager->insertSelfAppraisalData($insertString);
          if($ret==false){
           echo FAILURE;
           die;
          }
          
      }
      */
      
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
// $History: ajaxGetValues.php $
?>