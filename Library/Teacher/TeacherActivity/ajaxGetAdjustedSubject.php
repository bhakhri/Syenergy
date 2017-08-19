<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    
if(trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!='') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
    $looselyCoupled=$sessionHandler->getSessionVariable('LOOSELY_COUPLED_ATTENDANCE');
    
    
    //if type of attendance is daily
    if(trim($REQUEST_DATA['attendanceType'])==2){
     $dateArr=explode("-",$startDate);
     $daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
      if($daysofWeek==0){ 
          $daysofWeek=7;
      }//we consider sunday as 7
      $daysofWeekCond=' AND daysOfWeek='.$daysofWeek;
      
      //whethere attendance is loosely couple with time table 
      //then daysOfWeek condition does not apply      
      if($looselyCoupled==0 and $sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==WEEKLY_TIMETABLE){
         $daysofWeekCond=''; 
      }
    }
    else{
        $daysofWeekCond='';
    }
    
    //$foundArray = TeacherManager::getInstance()->getTeacherSubject(' AND c.classId='.$REQUEST_DATA['classId']);
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
      $foundArray = TeacherManager::getInstance()->getTeacherAdjustedSubject(' AND c.classId='.$REQUEST_DATA['classId'].$daysofWeekCond,$startDate,$endDate,'','',$timeTableLabelTypeConditions);
    }
    else{

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
          $timeTableConditions =' AND t.fromDate ="'.$startDate.'"'; //as this file is used for daily attendance only from admin end   
        }
		if($looselyCoupled==0 and $timeTableRecordArray[0]['timeTableType']==WEEKLY_TIMETABLE){
         $daysofWeekCond=''; 
        }
      //*******************************************  
      $foundArray = TeacherManager::getInstance()->getTeacherAdjustedSubject(' AND c.classId='.$REQUEST_DATA['classId'].$daysofWeekCond,$startDate,$endDate,$timeTableLabelId,$employeeId,$timeTableConditions);  
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetAdjustedSubject.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/04/10   Time: 11:33
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in "Attendance" and "Test" module in admin end for
//DAILY_TIMETABLE issues
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 16/11/09   Time: 15:03
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated daily attendance module---now admin can choose to whether
//loosely couple subject and periods with time table records or not.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/10/09   Time: 10:35
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001875,00001874
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/10/09   Time: 17:56
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added code for "Time table adjustment"
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Duty Leave" module
?>