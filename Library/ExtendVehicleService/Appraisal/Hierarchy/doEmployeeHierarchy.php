<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/Appraisal/HierarchyManager.inc.php");

define('MODULE','EmployeeHierarchy');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(SystemDatabaseManager::getInstance()->startTransaction()) {
   
   
   $superiorEmpId=trim($REQUEST_DATA['supEmployeeId']);
   
   
   $employees=trim($REQUEST_DATA['emp']);
   $allSearchedEmployees=trim($REQUEST_DATA['emp2']);
   if($superiorEmpId==''){
       die(SELECT_SUPERIOR_EMPLOYEE);
   }
   $cnt=0;
   if($employees!='' and $employees!=0){
       $employeeIds=explode(',',$employees);
       $cnt=count($employeeIds);
   }
   $allSearchedemployeeIds='';
   
   $hierarchyManager = HierarchyManager::getInstance();
   $sessionId=$sessionHandler->getSessionVariable('SessionId');
   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   
   //first check cyclick relations (JUST FOR ONE LEVEL ONLY)
   if($cnt>0){
       $cyclickArray=$hierarchyManager->checkCyclickRelations(implode(',',$employeeIds),$superiorEmpId,$instituteId,$sessionId);
       if($cyclickArray[0]['cnt']!=0){
           die(CYCLIC_HIERARCHY_FOUND);
       }
   }
   $cyclicRelnFound=0;   
   if($cnt>0 and is_array($employeeIds)){
       $loopFlag=1;
       $supEmpId=$superiorEmpId;
       $c=0;
       while($loopFlag==1){
          $c++;
          $foundArray=$hierarchyManager->getSuperiorEmployee($supEmpId,$sessionId,$instituteId);
          if(is_array($foundArray) and count($foundArray)>0) {
              $genSupId=$foundArray[0]['superiorEmployeeId'];
              if(in_array($genSupId,$employeeIds)){
                $cyclicRelnFound=1;
                $loopFlag=0;
                break;  
              }
              else{
                 $supEmpId=$genSupId;
                 $loopFlag=1; 
              }
          }
          else{
              $loopFlag=0;
              break;
          }
          
         if($c>10000){
           die('Employee Hierarchy is too long');
         } 
       }
   }
   if($cyclicRelnFound==1){
     die(CYCLIC_HIERARCHY_FOUND);  
   }
   
   //NOW DELETE ALL SUB-ORDINATES CORRESPONDING TO THIS SUPERIOR EMP ID
   $ret=$hierarchyManager->deleteSubordinateEmployees($superiorEmpId,$sessionId,$instituteId,$allSearchedEmployees);
   if($ret==false){
       die(FAILURE);
   }
   
   //now delete mapping of subordinates if exists
   if($cnt>0 and is_array($employeeIds)){
    for($i=0;$i<$cnt;$i++){
     if(trim($employeeIds[$i])==''){
         die(EMPLOYEE_INFO_MISSING);
     }
     $ret=$hierarchyManager->deleteSubordinateEmployeesMapping($employeeIds[$i],$sessionId,$instituteId);
     if($ret==false){
       die(FAILURE);
     }
    }
   }
   
   //now do the mapping
   if($cnt>0 and is_array($employeeIds)){
    $insertString='';   
    for($i=0;$i<$cnt;$i++){
      if($superiorEmpId==$employeeIds[$i]){
          die(SAME_HIERARCHY_ERROR);
      }  
      if($insertString!=''){
          $insertString .=',';
      }
      $insertString .=" ( $superiorEmpId, ".$employeeIds[$i].",$sessionId,$instituteId ) ";
      
      if($i%20==0){ //this is to prevent executing too long insert query,after 20 iterations,one new insert query will be executed
        $ret=$hierarchyManager->doEmployeeHierarchy($insertString);
        if($ret==false){
            die(FAILURE);
        }
        $insertString='';
      }
    }
    if($insertString!=''){
        $ret=$hierarchyManager->doEmployeeHierarchy($insertString);
        if($ret==false){
            die(FAILURE);
        }
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