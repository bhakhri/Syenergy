<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
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
    
if(trim($REQUEST_DATA['classId'] ) != '' AND trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $startDate=date('Y-m-d');
    $endDate=date('Y-m-d');
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);

    $daysofWeekCond='';
    
    if($roleId==2){
      $timeTableLabelTypeConditions='';
      $date=date('Y-m-d');
      if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
        if(trim($REQUEST_DATA['moduleName'])=='DailyAttendance'){  
          $timeTableLabelTypeConditions=' AND t.fromDate ="'.$startDate.'"';  
        }
        else if(trim($REQUEST_DATA['moduleName'])=='ClassWiseAttendanceList'){  
          $timeTableLabelTypeConditions=' AND t.fromDate BETWEEN "'.$startDate.'" AND "'.$endDate.'"';  
        }
        else{
          $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';
        }
      }
      $timeTableLabelTypeConditions .=' AND t.timeTableLabelId='.$timeTableLabelId;
      $foundArray = TeacherManager::getInstance()->getTeacherAdjustedSubject(' AND c.classId='.trim($REQUEST_DATA['classId']).$daysofWeekCond,$startDate,$endDate,$timeTableLabelId,'',$timeTableLabelTypeConditions);
    }
    else{
      //*******************************************  
      $foundArray = TeacherManager::getInstance()->getTimeTableClassSubject(' AND c.classId='.trim($REQUEST_DATA['classId']).' AND ttc.timeTableLabelId='.$timeTableLabelId);  
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetAdjustedSubject.php $
?>