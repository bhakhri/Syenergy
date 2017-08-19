<?php
//-------------------------------------------------------
//  This File contains code for saving attendance set percent
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

	$transferMarksManager->setCurrentProcess('attendanceMarksPercentSave');
	$attendanceSetPercent = $REQUEST_DATA['attendanceSetPercent'];
	$attendanceSetPercentName = $REQUEST_DATA['attendanceSetPercentName'];

	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	if (empty($attendanceSetPercent)) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_PERCENT_NOT_SELECTED;
		die;
	}
	elseif ($attendanceSetPercent == 'CNP') {
		if (empty($attendanceSetPercentName)) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ATTENDANCE_SET_PERCENT_NAME_NOT_ENTERED;
			die;
		}

		$attendanceSetPercentName = add_slashes(trim($attendanceSetPercentName));
		$attendanceSetPercentNameCntArray = $transferMarksManager->checkAttendanceSetName($attendanceSetPercentName);
		$attendanceSetPercentNameCnt = $attendanceSetPercentNameCntArray[0]['cnt'];
		if ($attendanceSetPercentNameCnt > 0) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ATTENDANCE_SET_PERCENT_NAME_ALREADY_EXISTS;
			die;
		}

		$transferMarksManager->validateAttendanceSetPercentData();

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$return = $transferMarksManager->addAttendanceSetInTransaction($attendanceSetPercentName, PERCENTAGES);
			if ($return == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
				die;
			}
			$attendanceSetIdArray = $transferMarksManager->getMaxAttendanceSetId(PERCENTAGES);
			$attendanceSetId = $attendanceSetIdArray[0]['attendanceSetId'];
			if (empty($attendanceSetId)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}
			$postFromArray = $REQUEST_DATA['percentFrom'];
			$percentToArray = $REQUEST_DATA['percentTo'];
			$marksScoredArray = $REQUEST_DATA['marksScored'];

			$insertStr = '';
			foreach($postFromArray as $key => $from) {
				if ($insertStr != '') {
					$insertStr .= ',';
				}
				$to = $percentToArray[$key];
				$marksScored = $marksScoredArray[$key];
				$insertStr .= "($from, $to, $marksScored, $labelId, $instituteId, $attendanceSetId)";
			}
			$return = $transferMarksManager->addAttendanceMarksInTransaction($insertStr);
			if ($return == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				$transferMarksManager->setTransferProcessRunning(false);
				$transferMarksManager->storeTransferMarksManager($transferMarksManager);
				echo SUCCESS;
				die;
			}
			else {
				echo FAILURE;
				die;
			}
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
		if (!empty($attendanceSetPercentName)) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ATTENDANCE_SET_PERCENT_NAME_NOT_REQUIRED;
			die;
		}
		$attendanceSetPercentName = add_slashes(trim($attendanceSetPercentName));

		$conditions = " AND attendanceSetId != '$attendanceSetPercent' ";

		$attendanceSetPercentNameCntArray = $transferMarksManager->checkAttendanceSetName($attendanceSetPercentName, $conditions);

		$attendanceSetPercentNameCnt = $attendanceSetPercentNameCntArray[0]['cnt'];
		if ($attendanceSetPercentNameCnt > 0) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ATTENDANCE_SET_PERCENT_NAME_ALREADY_EXISTS;
			die;
		}
		$transferMarksManager->validateAttendanceSetPercentData();
		$checkAttendanceSetIdArray = $transferMarksManager->checkAttendanceSet($attendanceSetPercent, PERCENTAGES);
		$checkAttendanceSetId = $checkAttendanceSetIdArray[0]['cnt'];
		if ($checkAttendanceSetId == 0) {
			$transferMarksManager->setTransferProcessRunning(false);
			echo ATTENDANCE_SET_NOT_RELATED_TO_PERCENTAGES;
			die;
		}

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$postFromArray = $REQUEST_DATA['percentFrom'];
			$percentToArray = $REQUEST_DATA['percentTo'];
			$marksScoredArray = $REQUEST_DATA['marksScored'];

			$maxMarks = max($marksScoredArray);

			$classSubjectArray = $transferMarksManager->checkAttendanceSetClassSubjects($attendanceSetPercent);
			if ($classSubjectArray[0]['classId'] != '') {
				foreach($classSubjectArray as $record) {
					$classId = $record['classId'];
					$subjectId = $record['subjectId'];
					$subjectAttendanceMarksArray = $transferMarksManager->getAttendanceTypeMarks($classId, $subjectId,PERCENTAGES);
					$subjectAttendanceMarks = $subjectAttendanceMarksArray[0]['weightageAmount'];
					if ($maxMarks > $subjectAttendanceMarks) {
						$subjectCodeArray = $transferMarksManager->getSubjectCode($subjectId);
						$subjectCode = $subjectCodeArray[0]['subjectCode'];
						echo ATTENDANCE_SET_MARKS_.$maxMarks._CAN_NOT_BE_GREATER_THAN_TEST_TYPE_MARKS_.$subjectAttendanceMarks._FOR_SUBJECT_.$subjectCode;
						die;
					}
				}
			}


			$return = $transferMarksManager->deleteAttendanceMarksInTransaction($attendanceSetPercent);
			if ($return == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE_WHILE_REMOVING_OLD_ATTENDANCE_SET_MARKS;
				die;
			}


			$insertStr = '';
			foreach($postFromArray as $key => $from) {
				if ($insertStr != '') {
					$insertStr .= ',';
				}
				$to = $percentToArray[$key];
				$marksScored = $marksScoredArray[$key];
				$insertStr .= "($from, $to, $marksScored, $labelId, $instituteId, $attendanceSetPercent)";
			}
			$return = $transferMarksManager->addAttendanceMarksInTransaction($insertStr);
			if ($return == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
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
	}

// for VSS
//$History: saveAttendanceSetPercent.php $
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