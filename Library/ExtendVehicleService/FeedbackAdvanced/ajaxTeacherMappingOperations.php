<?php
//------------------------------------------------------------------
// THIS FILE IS USED TO ADD/EDIT/DELETE AN ADV. FEEDBACK CATEGORY
// Author : Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transactions
$opMode=trim($REQUEST_DATA['modeName']);
if($opMode==1){
  $opName='add';
}
else if($opMode==2){
  $opName='edit';  
}
else if($opMode==3){
  $opName='delete';  
}
else{
  echo TECHNICAL_PROBLEM;
  die;
}
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS',$opName);                                   

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if($opMode!=3){//if it is not delete operation
        if ($errorMessage == '' && (!isset($REQUEST_DATA['timeTableLabelId']) || trim($REQUEST_DATA['timeTableLabelId']) == '')) {
            $errorMessage .= SELECT_TIME_TABLE."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['surveyId']) || trim($REQUEST_DATA['surveyId']) == '')) {
            $errorMessage .= SELECT_SURVEY."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '')) {
            $errorMessage .= SELECT_CLASS."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['groupId']) || trim($REQUEST_DATA['groupId']) == '')) {
            $errorMessage .= SELECT_GROUP."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectId']) || trim($REQUEST_DATA['subjectId']) == '')) {
            $errorMessage .= SELECT_SUBJECT."\n";  
        }
        if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeIds']) || trim($REQUEST_DATA['employeeIds']) == '')) {
            $errorMessage .= SELECT_EMPLOYEE."\n";  
        }

        
       // $labelId=trim($REQUEST_DATA['labelId']);
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        $surveyId=trim($REQUEST_DATA['surveyId']);	
        $classId=trim($REQUEST_DATA['classId']);
        $groupId=trim($REQUEST_DATA['groupId']);
        $subjectId=trim($REQUEST_DATA['subjectId']);
        $employeeIds=trim($REQUEST_DATA['employeeIds']);
    }
	if (empty($surveyId)) {
		echo "This record pertains to old data, and can not be deleted";
		die;
	}
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
        $fbMgr=FeedBackTeacherMappingManager::getInstance();
        if($opMode==2 or $opMode==3){
            $mappingId=trim($REQUEST_DATA['mappingId']);
            if($mappingId==''){
                echo 'Invalid Teacher Mapping';
                die;
            }
        }
        
 $excludedEmps='';
        
        //************for addition*********
        if($opMode==1){
            if(SystemDatabaseManager::getInstance()->startTransaction()) {
              $usage=$fbMgr->getClassSubjectUsage($classId,$subjectId,$surveyId);
              $excludedEmps='-1';
              if($usage[0]['cnt']!=0){
                  echo "Data cannot be added/edited/deleted as feedback corresponding\nto this survey,class and subject has been given";
                        die;
              }  
              /*
              //fetch usage of class->subject->group from answer table
              $usage=$fbMgr->getClassSubjectGroupUsage($classId,$subjectId,$groupId);
			  $excludedEmps='-1';
              if($usage[0]['cnt']!=0){
                  echo "Data cannot be added/edited/deleted as feedback corresponding\nto this class,subject and group has been given";
                        die;
              }
              */
              //delete previous mapping
              $ret=$fbMgr->deletePreviousTeacherMapping($timeTableLabelId,$surveyId,$classId,$groupId,$subjectId,$excludedEmps);
              if($ret==false){
                 echo FAILURE;
                 die; 
              }
              //now make fresh insert
              $employees=explode(',',$employeeIds);
              $empCnt=count($employees);
              $insertString='';
              for($i=0;$i<$empCnt;$i++){
                  if(trim($employees[$i])==''){
                      continue;
                  }
                  if($insertString != ''){
                      $insertString .=' , ';
                  }
                  $insertString .=" ( $timeTableLabelId,$surveyId, $classId, $groupId, $subjectId, $employees[$i] ) ";
              }
              if($insertString!=''){
                  $ret=$fbMgr->doTeacherMapping($insertString);
                  if($ret==false){
                      echo FAILURE;
                      die;
                  }
              }
              else{
                echo FAILURE;
                die;  
              }
              
              //die('testing');
              
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
        }
        //************addition operation ends*********
        
        
        //************for editing*********
        if($opMode==2){
            
            if(SystemDatabaseManager::getInstance()->startTransaction()) {
              /******CHECK FOR USAGE OF THIS VALUES IN FEEDBACK(need to be done)*****/  
              /*  
              //delete previous mapping
              $ret=$fbMgr->deletePreviousTeacherMapping($timeTableLabelId,$classId,$groupId,$subjectId);
              if($ret==false){
                 echo FAILURE;
                 die; 
              }
              */
              $foundArray=$fbMgr->fetchMappedClassSubjectGroupEmployee($mappingId);

              if(is_array($foundArray) and count($foundArray)>0){
                  //fetch class,subject,group and employees
                  $deleteString='';
                  $concatString='';
                  $cnt=count($foundArray);
                  for($i=0;$i<$cnt;$i++){
                      if($concatString!=''){
                          $concatString .=",";
                      }
                      //$concatString .="'".$foundArray[$i]['classId']."~".$foundArray[$i]['subjectId']."~".$foundArray[$i]['groupId']."'";
                      $concatString .="'".$foundArray[$i]['classId']."~".$foundArray[$i]['subjectId']."'";
                  }
                  //$usage=$fbMgr->getClassSubjectGroupUsageConcatenated($concatString);
                  $usage=$fbMgr->getClassSubjectUsageConcatenated($concatString,$surveyId);
                  if($usage[0]['cnt']!=0){
                        echo "Data cannot be added/edited/deleted as feedback corresponding\nto this survey,class and subject has been given";
                        die;
                  }
                  for($i=0;$i<$cnt;$i++){
                      if($deleteString!=''){
                          $deleteString .=" , ";
                      }
                      $deleteString .="'".$foundArray[$i]['timeTableLabelId']."~".$foundArray[$i]['classId']."~".$foundArray[$i]['groupId']."~".$foundArray[$i]['subjectId']."~".$surveyId."'";
                  }
              }
              else{
                 echo 'Invalid Teacher Mapping';
                 die;  
              }
              //delete previous mapping
              $ret=$fbMgr->deleteTeacherMapping($deleteString);
              if($ret==false){
                 echo FAILURE;
                 die; 
              }
              
              //now make fresh insert
              $employees=explode(',',$employeeIds);
              $empCnt=count($employees);
              $insertString='';
              for($i=0;$i<$empCnt;$i++){
                  if(trim($employees[$i])==''){
                      continue;
                  }
                  if($insertString != ''){
                      $insertString .=' , ';
                  }
                  $insertString .=" ( $timeTableLabelId,$surveyId, $classId, $groupId, $subjectId, $employees[$i] ) ";
              }
              
              if($insertString!=''){
                  $ret=$fbMgr->doTeacherMapping($insertString);
                  if($ret==false){
                      echo FAILURE;
                      die;
                  }
              }
              else{
                echo FAILURE;
                die;  
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
        }
        //************editing operation ends*********
        
        //************for deleting*********
        if($opMode==3){
           if(SystemDatabaseManager::getInstance()->startTransaction()) {
           /******CHECK FOR USAGE OF THIS VALUES IN FEEDBACK(need to be done)*****/
              $foundArray=$fbMgr->fetchMappedClassSubjectGroupEmployee($mappingId);
              if(is_array($foundArray) and count($foundArray)>0){
                  //fetch class,subject,group and employees
                  $deleteString='';
                  $concatString='';
                  $cnt=count($foundArray);
                  
                  for($i=0;$i<$cnt;$i++){
                      if($concatString!=''){
                          $concatString .=",";
                      }
                      //$concatString .="'".$foundArray[$i]['classId']."~".$foundArray[$i]['subjectId']."~".$foundArray[$i]['groupId']."'";
                      $concatString .="'".$foundArray[$i]['classId']."~".$foundArray[$i]['subjectId']."'";
                  }
                  //$usage=$fbMgr->getClassSubjectGroupUsageConcatenated($concatString);
                  $usage=$fbMgr->getClassSubjectUsageConcatenated($concatString,$surveyId);
                  if($usage[0]['cnt']!=0){
                        echo "Data cannot be added/edited/deleted as feedback corresponding\nto this survey,class and subject has been given";
                        die;
                  }
                  
                  for($i=0;$i<$cnt;$i++){
                      if($deleteString!=''){
                          $deleteString .=" , ";
                      }
                      $deleteString .="'".$foundArray[$i]['timeTableLabelId']."~".$foundArray[$i]['classId']."~".$foundArray[$i]['groupId']."~".$foundArray[$i]['subjectId']."'";
                  }
              }
              else{
                 echo 'Invalid Teacher Mapping';
                 die;  
              }
              //delete previous mapping
              $ret=$fbMgr->deleteTeacherMapping($deleteString);
              if($ret==false){
                 echo FAILURE;
                 die; 
              }
              
           
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
               echo DELETE;
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
        }
        //************for deleting*********
       
       //if add/edit/delete operation fails
        echo TECHNICAL_PROBLEM;
        die; 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxTeacherMappingOperations.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/02/10   Time: 17:10
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Added the check : If combination of class,subject and group are used in
//answer table then this mapping cannot not be changed/deleted
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/02/10    Time: 16:33
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Class->Group->Subject->Teacher" mapping module for "Adv.
//Feedback Modules"
?>