<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO all options(class,subject,group) corresponding to a particular date for a teacher 
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
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
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    
    if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!=''){
     $startDate=trim($REQUEST_DATA['startDate']);
     $endDate=trim($REQUEST_DATA['endDate']);
     $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
     $employeeId=trim($REQUEST_DATA['employeeId']);
     /* 
     //Calculating days of week between from and to dates
     $sDate1=explode('-',$startDate);
     $sDate3=explode('-',$endDate);
     $from_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
     $to_date=gregoriantojd($sDate3[1], $sDate3[2], $sDate3[0]);
     $diff=$to_date - $from_date; 
     $daysOfWeekString='';
     for($i=0;$i<$diff;$i++){
        $calDate  = date('w',mktime(0, 0, 0, $sDate1[1]  , $sDate1[2]+$i, $sDate1[0]));
        if($calDate==0){
            $calDate=7; //as we consider sunday as 7
        }
        if($daysOfWeekString!=''){
            $daysOfWeekString.=',';
        }
        $daysOfWeekString .=$calDate;
     }
     if($diff==0){
        $daysOfWeekString=date('w',mktime(0, 0, 0, $sDate1[1]  , $sDate1[2], $sDate1[0]));;
     }
     $daysOfWeekString= implode(',',array_unique(explode(',',$daysOfWeekString)));
     */
     
     //*************Get adjusted data for groups*************
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
      $foundArray = TeacherManager::getInstance()->getAdjustedSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'],$startDate,$endDate,' ','','',$timeTableLabelTypeConditions);
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
      //*******************************************  
       $foundArray = TeacherManager::getInstance()->getAdjustedSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'],$startDate,$endDate,' ',$timeTableLabelId,$employeeId,$timeTableConditions);  
     }
    }
    else{
        echo 0;
        die;
    }

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetAdjustedGroup.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/04/10   Time: 11:33
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in "Attendance" and "Test" module in admin end for
//DAILY_TIMETABLE issues
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/10/09   Time: 17:56
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added code for "Time table adjustment"
?>