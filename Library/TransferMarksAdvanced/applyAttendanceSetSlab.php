<?php
//-------------------------------------------------------
//  This File contains code for applying attendance set slab
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TransferInternalMarksAdvanced');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
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

	$transferMarksManager->setCurrentProcess('attendanceMarksSlabApply');


	$transferMarksManager->checkTransferAttSlabSubjects($classId, $REQUEST_DATA['attSlabSubjects']);
	$attendanceSetSlabToApply = $REQUEST_DATA['attendanceSetSlabToApply'];

	if (empty($attendanceSetSlabToApply)) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo NO_ATTENDANCE_SET_SELECTED;
		die;
	}

	$attendanceSlabSetIdCntArray = $transferMarksManager->checkAttendanceSetId($attendanceSetSlabToApply,SLABS);
	$cnt = $attendanceSlabSetIdCntArray[0]['cnt'];
	if ($cnt == 0) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_SLAB_DOES_NOT_EXISTS;
		die;
	}

	$attSetPerSubjectArray = $transferMarksManager->getTransferAttSlabSubjectsArray();

	$attendanceSetMaxMarksArray = $transferMarksManager->checkAttendanceSetSlabMaxMarks($attendanceSetSlabToApply);
	$attendanceSetMaxMarks = $attendanceSetMaxMarksArray[0]['marksScored'];

	foreach($attSetPerSubjectArray as $subjectId) {
		$subjectAttendanceMarksArray = $transferMarksManager->getAttendanceTypeMarks($classId, $subjectId,SLABS);
		$subjectAttendanceMarks = $subjectAttendanceMarksArray[0]['weightageAmount'];
		if ($attendanceSetMaxMarks > $subjectAttendanceMarks) {
			$subjectCodeArray = $transferMarksManager->getSubjectCode($subjectId);
			$subjectCode = $subjectCodeArray[0]['subjectCode'];
			echo ATTENDANCE_SET_MARKS_.$attendanceSetMaxMarks._CAN_NOT_BE_GREATER_THAN_TEST_TYPE_MARKS_.$subjectAttendanceMarks._FOR_SUBJECT_.$subjectCode;
			die;
		}
	}

	$attSetSlabSubjectId = implode(',', $transferMarksManager->getTransferAttSlabSubjectsArray());

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$return = $transferMarksManager->updateAttendanceSetPercentSlab($classId, $attSetSlabSubjectId, $attendanceSetSlabToApply);
		if ($return == false) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ERROR_WHILE_UPDATING_ATTENDANCE_SET;
			die;
		}
		$return = $transferMarksManager->updateAttendanceSetPercentSlabOptional($classId, $attSetSlabSubjectId, $attendanceSetSlabToApply);
		if ($return == false) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ERROR_WHILE_UPDATING_ATTENDANCE_SET;
			die;
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			$transferMarksManager->setTransferProcessRunning(false);
			$transferMarksManager->storeTransferMarksManager($transferMarksManager);
			echo SUCCESS;
		}
		else {
			$transferMarksManager->setTransferProcessRunning(false);
			echo FAILURE;
		}
	}
	else {
		$transferMarksManager->setTransferProcessRunning(false);
		echo FAILURE;
	}

// for VSS
//$History: applyAttendanceSetSlab.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 1/04/10    Time: 11:57a
//Updated in $/LeapCC/Library/TransferMarksAdvanced
//applied missing check of data validation
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Library/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//










?>