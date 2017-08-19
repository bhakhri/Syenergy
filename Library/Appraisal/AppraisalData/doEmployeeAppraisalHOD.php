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
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')!=1){
   die(ACCESS_DENIED);
}
define('MODULE','EmployeeAppraisal');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$employeeToBeAppraised = $sessionHandler->getSessionVariable('EmployeeToBeAppraised');
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

	$hodString=trim($REQUEST_DATA['hodString']);
	$reviwerString=trim($REQUEST_DATA['reviwerString']);

	$scoregained= trim($REQUEST_DATA['scoregained']);
	$dutiesweekend=trim($REQUEST_DATA['dutiesweekend']);
	$extsupreintendent=trim($REQUEST_DATA['extsupreintendent']);
	$copychecked=trim($REQUEST_DATA['copychecked']);
	$dutiesexternal=trim($REQUEST_DATA['dutiesexternal']);
	$dutiesinternal=trim($REQUEST_DATA['dutiesinternal']);


if($hodString=='' or $reviwerString==''){
    echo 'Required Parameters Missing';
    die;
}

require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();

$employeeId=$sessionHandler->getSessionVariable('EmployeeId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');
$forEmployeeId=$sessionHandler->getSessionVariable('EmployeeToBeAppraised');

if(SystemDatabaseManager::getInstance()->startTransaction()) {
   
   //checking for appraisal data
   $appraisalDataArray=$appDataManager->getAppraisalFormData($forEmployeeId,'HAVING disabledQuestions=1 ');
   $cnt=count($appraisalDataArray);
   if($cnt>1){
       $appraisalIds=UtilityManager::makeCSList($appraisalDataArray,'appraisalId');
       //create an array
       $appArray=array();
       $appSelfArray=array();
       foreach($appraisalDataArray as $app){
          $appArray[$app['appraisalId']]=$app['appraisalWeightage'];
          if($app['selfEvaluation']==''){
              $app['selfEvaluation']=0;
          }
          $appSelfArray[$app['appraisalId']]=$app['selfEvaluation'];
       }
       

       $hodAppArray=explode(',',$hodString);
       $cnt1=count($hodAppArray);
       if($cnt!=$cnt1){
           echo 'Mismatched Questions Count';
           die;
       }
       
       $insertString='';
       for($i=0;$i<$cnt1;$i++){
           $valArr=explode('_',$hodAppArray[$i]);
           $appraisalId=trim($valArr[0]);
           $hodAppraisal=trim($valArr[1]);
           if($hodAppraisal==''){
               echo 'Please enter value';
               die;
           }
           if(!is_numeric($hodAppraisal)){
               echo 'Enter numeric value';
               die;
           }
           if(!array_key_exists($appraisalId,$appArray)){
              echo 'Mismatched Question Found';
              die; 
           }
           //checking for max. weightage
           if($hodAppraisal>$appArray[$appraisalId]){
               echo 'Maximum value for this field is '.$appArray[$appraisalId];
               die; 
           }
           
           if($insertString!=''){
              $insertString .=',';  
           }
           $insertString .= " ( $forEmployeeId , $appraisalId,$appSelfArray[$appraisalId],$hodAppraisal,$sessionId,$employeeId ) ";
       }
	
	  //  Compatibility TAB

	  $foundArray =  $appDataManager->getCompatibilityData($employeeToBeAppraised,$sessionId);
	  if($foundArray[0]['cnt']>0) {
   	     $ret=$appDataManager->updateAppraisalCompatibilityData($employeeToBeAppraised,$scoregained,$dutiesweekend,$extsupreintendent,
															 $copychecked,$dutiesexternal,$dutiesinternal);
	  }
	  else {
  		 $ret=$appDataManager->insertAppraisalCompatibilityData($employeeToBeAppraised,$scoregained,$dutiesweekend,$extsupreintendent,
															 $copychecked,$dutiesexternal,$dutiesinternal);
	  }
	  if($ret==false){
 	     echo FAILURE;
	     die;
	  }

	   
      if($insertString!=''){ 
          //now delete previous self appraisal data 
          $ret=$appDataManager->deleteSelfAppraisalData($forEmployeeId,$sessionId);
          if($ret==false){
           echo FAILURE;
           die;
          }
          
          $ret=$appDataManager->insertSelfAppraisalDataFromHOD($insertString);
          if($ret==false){
           echo FAILURE;
           die;
          }
          
      }
   }
   
  //data updation for reviewer part
   if($reviwerString!=''){
      $reviwerInsertString ='';
      $reviwerArray=explode(',',$reviwerString);
      $rCnt=count($reviwerArray);
      $grandTotal=0;
      for($i=0;$i<$rCnt;$i++){
         if(trim($reviwerArray[$i])==''){
             die('Please enter value');
         }
         if(!is_numeric(trim($reviwerArray[$i]))){
             die('Enter numeric value');
         }
         if(trim($reviwerArray[$i])>5){
             die('Maximum value for this field is 5');
         }
         if($reviwerInsertString!=''){
             $reviwerInsertString .=',';
         }
         $reviwerInsertString .="'".add_slashes(trim($reviwerArray[$i]))."'";
         $grandTotal +=trim($reviwerArray[$i]);
      }
      
      if($reviwerInsertString!=''){
         $reviwerInsertString =" ( $employeeId,$forEmployeeId,$sessionId,".$reviwerInsertString.",$grandTotal,0 ) " ;
      }
      
      $ret=$appDataManager->deleteEmployeeReviewerData($forEmployeeId,$sessionId);
      if($ret==false){
        die(FAILURE);
      }
      if($reviwerInsertString!=''){
         $ret=$appDataManager->insertEmployeeReviewerDateFromHOD($reviwerInsertString); 
         if($ret==false){
          die(FAILURE);
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
// $History: ajaxGetValues.php $
?>