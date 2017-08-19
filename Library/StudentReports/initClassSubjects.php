<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Nishu Bindal;
// Created on : 6-July-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportManager = StudentReportsManager::getInstance();

	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
	
	if (isset($REQUEST_DATA['labelId'])) {
		$timeTable = $REQUEST_DATA['labelId'];
	}
	elseif (isset($REQUEST_DATA['timeTable'])) {
		$timeTable = $REQUEST_DATA['timeTable'];
	}
	$cond = " AND c.classId = ".$classId ;
	$filter= " DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
	$orderSubjectBy = " classId,  subjectTypeId, subjectCode, subjectId";
	$subjectsArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filter, '', $orderSubjectBy);  
	
	echo json_encode($subjectsArray);

	
	
	
?>
