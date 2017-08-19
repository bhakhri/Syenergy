<?php
//-------------------------------------------------------
//  This File contains details of attendance marks percent
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TransferInternalMarksAdvanced');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/TransferMarksManager.inc.php");
	$transferMarksManager = TransferMarksManager::getInstance();

	if (false == $transferMarksManager->fetchTransferMarksManager()) {
		echo SOME_ERROR_HAS_OCCURED;
		die;
	}
	$transferMarksManager = $transferMarksManager->fetchTransferMarksManager();

	$labelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['class1'];
	$transferMarksManager->validateTimeTableClass($labelId, $classId);
	$transferMarksManager->checkTimeTableClass($labelId, $classId);

	if ($transferMarksManager->getCurrentProcess() == 'showClassSubjects') {
		$classSubjectsArray = $transferMarksManager->getClassSubjects($classId);
        $dbSubjectsArray = explode(',',UtilityManager::makeCSList($classSubjectsArray,'subjectId'));
        
		if(!is_array($REQUEST_DATA['classSubjects'])) {
		  $transferMarksManager->setTransferProcessRunning(false);
		  echo INVALID_SUBJECTS;
		  die;
		}
		foreach($REQUEST_DATA['classSubjects'] as $key => $subjectId) {
			if (!in_array($subjectId, $dbSubjectsArray)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo INVALID_SUBJECTS;
				die;
			}
		}
		$transferMarksManager->setTransferSubjects($REQUEST_DATA['classSubjects']);
		$postSubjectId = implode(',', $REQUEST_DATA['classSubjects']);
		$percentSubjectsArray = $transferMarksManager->getSubjectsForPercentSlabs($classId, $postSubjectId, PERCENTAGES);
		if (!is_array($percentSubjectsArray) or $percentSubjectsArray[0]['subjectId'] == '') {
			$transferMarksManager->setTransferProcessRunning(false);
			echo NO_SUBJECT_FOUND_FOR_ATTENDANCE_MARKS_PERCENT;
			die;
		}
	}
	elseif ($transferMarksManager->getCurrentProcess() == 'attendanceMarksPercentApply') {
		if (!is_array($REQUEST_DATA['attPerSubjects'])) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo INVALID_SUBJECTS;
			die;
		}
		$classSubjectsArray = $transferMarksManager->getClassSubjects($classId);
		$dbSubjectsArray = explode(',',UtilityManager::makeCSList($classSubjectsArray,'subjectId'));
		foreach($REQUEST_DATA['attPerSubjects'] as $key => $subjectId) {
			if (!in_array($subjectId, $dbSubjectsArray)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo INVALID_SUBJECTS;
				die;
			}
		}
		
		$transferSubjectsArray = $transferMarksManager->getTransferSubjects();
		$postSubjectId = implode(',', $transferSubjectsArray);
		$percentSubjectsArray = $transferMarksManager->getSubjectsForPercentSlabs($classId, $postSubjectId, PERCENTAGES);

		if (!is_array($percentSubjectsArray) or $percentSubjectsArray[0]['subjectId'] == '') {
			$transferMarksManager->setTransferProcessRunning(false);
			echo NO_SUBJECT_FOUND_FOR_ATTENDANCE_MARKS_PERCENT;
			die;
		}
	}
	else {
		$transferSubjectsArray = $transferMarksManager->getTransferSubjects();
		$postSubjectId = implode(',', $transferSubjectsArray);
		$percentSubjectsArray = $transferMarksManager->getSubjectsForPercentSlabs($classId, $postSubjectId, PERCENTAGES);
	}
	
	$transferMarksManager->setCurrentProcess('attendanceMarksPercent');

	$attendanceSetArray = $transferMarksManager->getAttendancePercentSlabs(PERCENTAGES);
	$resultArray = array('subjects' => $percentSubjectsArray, 'attendanceSets'=>$attendanceSetArray);
	$transferMarksManager->setTransferProcessRunning(false);
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode($resultArray);
?>