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

	$startDate=trim($REQUEST_DATA['startDate']);
	$endDate=trim($REQUEST_DATA['endDate']);

	$timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
	$employeeId=trim($REQUEST_DATA['employeeId']);

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


	//************************
	//for daily attendance
	//************************
	if($REQUEST_DATA['type']==2 and trim($REQUEST_DATA['forDate'])!='' and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!=''){ 
		$dateArr=explode("-",$REQUEST_DATA['forDate']);
		$daysofWeek= date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
		if($daysofWeek==0){ $daysofWeek=7;} //we consider sunday as 7
		if($roleId==2){
			$timeTableLabelTypeConditions='';
			if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
				$timeTableLabelTypeConditions=' AND t.fromDate ="'.$REQUEST_DATA['forDate'].'"';
			}
			$foundArray = TeacherManager::getInstance()->getTeacherAllOptions(" AND daysOfWeek=".$daysofWeek,$startDate,$endDate,'','',$timeTableLabelTypeConditions);
		}
		else {
			if($adminTimeTableTypeFlag==0){
			$foundArray = TeacherManager::getInstance()->getTeacherAllOptions(" AND daysOfWeek=".$daysofWeek,$startDate,$endDate,$timeTableLabelId,$employeeId);
			}
			else{
				$foundArray = TeacherManager::getInstance()->getTeacherAllOptions(" AND daysOfWeek=".$daysofWeek,$startDate,$endDate,$timeTableLabelId,$employeeId,$timeTableConditions);
			}
		}
	}

	//*****************************
	//for bulk attendance
	//*****************************

	else if($REQUEST_DATA['type']==1 and trim($REQUEST_DATA['startDate'])!='' and trim($REQUEST_DATA['endDate'])!=''){ 
		$foundArray = TeacherManager::getInstance()->getTeacherSchedule(' ',$startDate,$endDate);
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

// $History: ajaxGetAllOptions.php $
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
//User: Dipanjan     Date: 20/10/09   Time: 18:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added code for "Time table adjustment"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/06/09   Time: 19:01
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//added file for teacher attendance options
?>