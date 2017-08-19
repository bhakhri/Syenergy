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
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");

    if(trim($REQUEST_DATA['employeeId'])!='' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!=''){ //for daily attendance
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);
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

     //*******find time table label type**********
      $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
      if($timeTableLabelId==''){
          echo 'Required Parameters Missing';
          die;
      }
      $timeTableRecordArray=AdminTasksManager::getInstance()->getTimeTableLabelType($timeTableLabelId);
      $timeTableConditions='';
      $date=date('Y-m-d');
      if($timeTableRecordArray[0]['timeTableType']==DAILY_TIMETABLE){
        $timeTableConditions =' AND t.fromDate <="'.$date.'"';
      }
     //*******************************************

     //*************Get adjusted data for class*************
     $foundArray = AdminTasksManager::getInstance()->getTeacherAdjustedClass($startDate,$endDate,$timeTableConditions);
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

// $History: ajaxGetAdjustedClass.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 20/04/10   Time: 11:33
//Updated in $/LeapCC/Library/AdminTasks
//Made changes in "Attendance" and "Test" module in admin end for
//DAILY_TIMETABLE issues
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/10/09   Time: 17:55
//Created in $/LeapCC/Library/AdminTasks
//Added code changes for "Time table adjustment"
?>