<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Jaineesh
// Created on : 07.07.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/TimeTableManager.inc.php");
	$timeTableManager = TimeTableManager::getInstance();

	$timeTabelLabelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['degree']);
	$subjectId=trim($REQUEST_DATA['subject']);
    
    if($timeTabelLabelId=='' or $classId==''){
        echo 'Required Parameters Missing';
        die;
    }

	if($labelId == 0) {
		$timeTableArray = $timeTableManager->getAllTimeTable($conditions,$orderBy= 'timeTableLabelId');
		$timeTabelLabelIds = UtilityManager::makeCSList($timeTableArray,'timeTableLabelId');
		$conditions = " AND ttc.timeTableLabelId IN ($timeTabelLabelIds)";
		$classArray = $timeTableManager->getTimeTableAllClasses($conditions);
		$timeTableClassList = UtilityManager::makeCSList($classArray,'classId');
		if($classId == 0) {
			$conditions = " AND tt.classId IN ($timeTableClassList)";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($conditions,$orderBy='employeeName');
		}
		else {
			$conditions = " AND tt.classId = $classId";
			$timeTableTeacherArray = $timeTableManager->getTimeTableClassTeacher($conditions,$orderBy='employeeName');
		}
	}

    if(is_array($timeTableTeacherArray) and count($timeTableTeacherArray)){
        echo json_encode($timeTableTeacherArray);
    }
    else{
        echo 0; 
    }
// $History: initClassAttendanceSubjects.php $
?>