<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
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
if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '' and trim($REQUEST_DATA['groupId'])!= '' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!='' and trim($REQUEST_DATA['forDate'])!=''){
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $dateArr=explode("-",$REQUEST_DATA['forDate']);
    $daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
    if($daysofWeek==0){ $daysofWeek=7;} //we consider sunday as 7
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
    //$foundArray = TeacherManager::getInstance()->getTeacherPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId']);
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
        $foundArray=TeacherManager::getInstance()->getAllPeriods();
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
        $foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId'],$startDate,$endDate,'','',$timeTableLabelTypeConditions);
       }
       else{
         if($adminTimeTableTypeFlag==0){
           $foundArray=TeacherManager::getInstance()->getAllPeriods();
         }
         else{  
           $foundArray = TeacherManager::getInstance()->getTeacherAdjustedPeriod(" AND daysOfWeek=".$daysofWeek." AND c.classId=".$REQUEST_DATA['classId']." AND t.subjectId=".$REQUEST_DATA['subjectId']." AND t.groupId=".$REQUEST_DATA['groupId'],$startDate,$endDate,$timeTableLabelId,$employeeId,$timeTableConditions);  
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
// $History: ajaxGetAdjustedPeriodNames.php $
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
//User: Dipanjan     Date: 16/11/09   Time: 15:43
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated logic to fetch periods when loosely coupled attendance is ON
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/10/09   Time: 17:56
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added code for "Time table adjustment"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/29/08    Time: 3:19p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/05/08    Time: 3:01p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>