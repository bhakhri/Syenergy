<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
define('MODULE','DutyLeavesAdvanced');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

$adminTasksManager = AdminTasksManager::getInstance();
$timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
$classId=trim($REQUEST_DATA['classId']);
$subjectId=trim($REQUEST_DATA['subjectId']);
$groupId=trim($REQUEST_DATA['groupId']);
$rollNos=trim($REQUEST_DATA['studentRollNo']);
$employeeId=trim($REQUEST_DATA['employeeId']);

if($timeTableLabelId==''){
    echo 'Required Parameters Missing';
    die;
}
$filter = ' AND ttc.timeTableLabelId='.$timeTableLabelId;
if($classId!='' and $classId!=0){
    $filter .=' AND c.classId='.$classId;
}
if($subjectId!='' and $subjectId!=0){
    $filter .=' AND sc.subjectId='.$subjectId;
}
if($groupId!='' and $groupId!=0){
    $filter .=' AND g.groupId='.$groupId;
}
if($employeeId!='' and $employeeId!=0){
    $filter .=' AND t.employeeId='.$employeeId;
}
if($rollNos!=''){
    $rollNoArray=explode(',',$rollNos);
    $cnt=count($rollNoArray);
    $rollNoString='';
    for($i=0;$i<$cnt;$i++){
        if($rollNoString!=''){
            $rollNoString .=',';
        }
        $rollNoString .="'".add_slashes(trim($rollNoArray[$i]))."'";
    }
    if($rollNoString!=''){
     $filter .=" AND s.rollNo IN (".$rollNoString.")";
    }
}

$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;  
    

$userId=$sessionHandler->getSessionVariable('UserId');
$dutyLeaves=explode(',',trim($REQUEST_DATA['dutyLeaves']));
$comments=explode('!~@~!',trim($REQUEST_DATA['comments']));
$ids=explode(',',$REQUEST_DATA['ids']);
$count=count($ids);
if($count!=count($dutyLeaves)){
    echo 'Invalid data';
    die;
}
/*
if($count!=count($comments)){
    echo 'Invalid data';
    die;
}
*/


$studentRecordArray   = $adminTasksManager->getStudentDutyLeaveList($filter,$limit);
$cnt=count($studentRecordArray);

//**********************input data validation***********************
$deleteStr='';
for($i=0;$i<$count;$i++){
    if(trim($dutyLeaves[$i])==''){
        echo EMPTY_DUTY_LEAVE;
        die;
    }
    if(!is_numeric(trim($dutyLeaves[$i]))){
        echo ENTER_DUTY_LEAVE_IN_NUMERIC;
        die;
    }
   $id=explode('~!~',$ids[$i]);
   if(count($id)!=5){
     echo 'Invalid data';
     die;  
   }
/*******Check for condition (dl+attended<=deliverd)******/ 
  $studentId=$id[0];
  $classId=$id[1];
  $subjectId=$id[3];
  $groupId=$id[2];
  $dl=trim($dutyLeaves[$i]);
  for($k=0;$k<$cnt;$k++){
     if(($studentId==$studentRecordArray[$k]['studentId']) and ($classId==$studentRecordArray[$k]['classId']) and ($subjectId==$studentRecordArray[$k]['subjectId']) and ($groupId==$studentRecordArray[$k]['groupId'])){
        if( ( $studentRecordArray[$k]['attended'] + $dl ) > $studentRecordArray[$k]['delivered']){
            echo DUTY_LEAVE_RESTRICTION;
            die;
        }
     }
  }
/*******Check for condition (dl+attended<=deliverd)******/ 
  if($deleteStr!=''){
      $deleteStr .=',';
  }
  $deleteStr .="'".$ids[$i]."'";
}
if($deleteStr==''){
    echo 'Incomplete Data';
    die;
}

if($employeeId==0){
    $employeeId='Null';
}
//*********************input data validation************************

    

//****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    

        //delete existing duty leaves 
        $ret1=$adminTasksManager->deleteDutyLeavesAdvanced($deleteStr);
        if($ret1===false){
            echo FAILURE;
            die;
        }
        $date=date('Y-m-d');
        $query='';
        for($i=0;$i<$count;$i++){
            $id=explode('~!~',$ids[$i]);
            $timeTableLabelId=$id[4];
            $studentId=$id[0];
            $classId=$id[1];
            $subjectId=$id[3];
            $groupId=$id[2];
            $dutyLeave=trim($dutyLeaves[$i]);
            if($query!=''){
                $query .=',';
            }
            $query .=" ( $timeTableLabelId, $studentId, $dutyLeave , '".$date."','".add_slashes(trim($comments[$i]))."', $classId, $subjectId, $groupId, $employeeId, $userId )";
        }
        
        if($query!=''){
            //insert duty leaves
            $ret2=$adminTasksManager->insertDutyLeavesAdvanced($query);
            if($ret2===false){
                echo FAILURE;
                die;
            }
        }
       else{
           echo FAILURE;
           die;
       } 

      //*****************************COMMIT TRANSACTION************************* 
         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo   SUCCESS;
            die;
         }
         else {
            echo FAILURE;
            die;
         }
      
    }
    else{
          echo FAILURE;
          die;
    }   
// $History: doStudentDutyLeaveAdvanced.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/11/09   Time: 16:41
//Updated in $/LeapCC/Library/AdminTasks
//Corrected bug in duty leaves module
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 19/11/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//corrected request parameter position
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/11/09   Time: 12:53
//Updated in $/LeapCC/Library/AdminTasks
//Completed/modified "Duty Leaves" module in admin section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:14
//Created in $/LeapCC/Library/AdminTasks
//Modified Duty Leaves module in admin section
?>