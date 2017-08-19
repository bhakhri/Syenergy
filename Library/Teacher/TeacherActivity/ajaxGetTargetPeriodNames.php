<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);  
}
UtilityManager::headerNoCache();
    
//if(trim($REQUEST_DATA['forDate'] ) != '') {
if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '' and trim($REQUEST_DATA['groupId'])!= '' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!='' and trim($REQUEST_DATA['forDate'])!='' and trim($REQUEST_DATA['sourcePeriodId'])!=''){
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $dateArr=explode("-",$REQUEST_DATA['forDate']);
    $daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
    if($daysofWeek==0){ $daysofWeek=7;} //we consider sunday as 7
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);
    $sourcePeriodId=trim($REQUEST_DATA['sourcePeriodId']);
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
    $looselyCoupled=$sessionHandler->getSessionVariable('LOOSELY_COUPLED_ATTENDANCE');
    
    $adminTimeTableTypeFlag=0;
    if($roleId!=2){
        //*******find time table label type**********
        $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
        if($timeTableLabelId==''){
          echo 'Required Parameters Missing';
          die;
        }
        $timeTableRecordArray=TeacherManager::getInstance()->getTimeTableLabelType($timeTableLabelId);
        $timeTableConditions='';
        $date=date('Y-m-d');
        if($timeTableRecordArray[0]['timeTableType']==DAILY_TIMETABLE){
          $adminTimeTableTypeFlag=1;  
          $timeTableConditions =' AND t.fromDate ="'.$startDate.'"'; //as this file is used for daily attendance only from admin end   
        }
      //*******************************************
    }
    
    //whethere attendance is loosely couple with time table
    //then daysOfWeek condition does not apply      
    if($looselyCoupled==0 and $sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==WEEKLY_TIMETABLE and $roleId==2 ){
        //$foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriod(" ",$startDate,$endDate);
        $foundArray=TeacherManager::getInstance()->getAllPeriods(' AND p.periodId!='.$sourcePeriodId);
    }
    else{
       if($roleId==2){
         $timeTableLabelTypeConditions='';
         $date=date('Y-m-d');
         if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
          if(trim($REQUEST_DATA['moduleName'])!='DailyAttendance'){  
            $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';
          }
         else{
          $timeTableLabelTypeConditions=' AND t.fromDate ="'.$startDate.'"';  
         }
        } 
        
        $targetCondition = " AND t.classId = '".$REQUEST_DATA['classId']."' AND  p.periodId!= '".$sourcePeriodId."'";
        $foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriodNew($targetCondition);
        /*
        $foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId']." AND p.periodId!=".$sourcePeriodId,$startDate,$endDate,'','',$timeTableLabelTypeConditions);
        */
       }
       else{
         if($adminTimeTableTypeFlag==0){
           $foundArray=TeacherManager::getInstance()->getAllPeriods(' AND p.periodId!='.$sourcePeriodId);
         }
         else{  
           $foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId']." AND p.periodId!=".$sourcePeriodId,$startDate,$endDate,$timeTableLabelId,$employeeId,$timeTableConditions);
         }
       }
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
?>