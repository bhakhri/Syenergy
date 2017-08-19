<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Ajinder Singh
// Created on : (30-09-2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$classId = $REQUEST_DATA['classId'];
if (!$classId) {
	echo 'please select class';
	die;
}

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();
$subjectArray = $timeTableManager->getClassAllSubjects($classId);
/*
foreach($subjectArray as $subjectRecord) {
	$hasParentCategory = $subjectRecord['hasParentCategory'];
	if ($hasParentCategory == '1' or $hasParentCategory == 1) {
		$subjectArray = $timeTableManager->getClassSubjectsWithOtherSubjects($classId);
	}
}
*/
$groupsArray = $timeTableManager->getClassGroups($classId);
$combinedArray = array('subjects'=>$subjectArray, 'groups'=>$groupsArray);
echo json_encode($combinedArray);

// $History: getClassSubjectsGroups.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/03/09   Time: 4:05p
//Updated in $/LeapCC/Library/TimeTable
//done changes for
//1. fetching groups based on subjects
//2. showing mba subjects.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:14p
//Created in $/LeapCC/Library/TimeTable
//file added for class based time table
//




?>