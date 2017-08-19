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
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['employeeId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!='') {
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);

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

    //$foundArray = TeacherManager::getInstance()->getTeacherSubject(' AND c.classId='.$REQUEST_DATA['classId']);
    $foundArray = AdminTasksManager::getInstance()->getTeacherAdjustedSubject(' AND c.classId='.$REQUEST_DATA['classId'],$startDate,$endDate,$timeTableConditions);
	if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetAdjustedSubject.php $
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