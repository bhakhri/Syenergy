<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO all options(class,subject,group) corresponding to a particular date for a teacher 
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
    
    if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= ''  and trim($REQUEST_DATA['timeTableLabelId'])!= ''  ){
     $startDate=date('Y-m-d');
     $endDate=date('Y-m-d');
     $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);

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
      $timeTableLabelTypeConditions .=' AND t.timeTableLabelId='.$timeTableLabelId;
      $foundArray = TeacherManager::getInstance()->getAdjustedSubjectGroup(trim($REQUEST_DATA['subjectId']),trim($REQUEST_DATA['classId']),$startDate,$endDate,' ',$timeTableLabelId,'',$timeTableLabelTypeConditions);
     }
     else{
      //*******************************************  
       $foundArray = TeacherManager::getInstance()->getTimeTableClassSubjectGroup(' AND c.classId='.trim($REQUEST_DATA['classId']).' AND ttc.timeTableLabelId='.$timeTableLabelId);  
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