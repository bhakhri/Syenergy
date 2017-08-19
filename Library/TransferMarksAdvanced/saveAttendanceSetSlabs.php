<?php
//-------------------------------------------------------
//  This File contains code for saving attendance set slabs
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

	$transferMarksManager->setCurrentProcess('attendanceMarksSlabSave');


	$attendanceSetSlab = $REQUEST_DATA['attendanceSetSlab'];
	$attendanceSetSlabName = $REQUEST_DATA['attendanceSetSlabName'];

	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

	if (empty($attendanceSetSlab)) {
		echo ATTENDANCE_SET_SLAB_NOT_SELECTED;
		die;
	}
	elseif ($attendanceSetSlab == 'CNP') {
		if (empty($attendanceSetSlabName)) {
			echo ATTENDANCE_SET_SLAB_NAME_NOT_ENTERED;
			die;
		}

		$attendanceSetSlabName = add_slashes(trim($attendanceSetSlabName));
		$attendanceSetSlabNameCntArray = $transferMarksManager->checkAttendanceSetName($attendanceSetSlabName);
		$attendanceSetSlabNameCnt = $attendanceSetSlabNameCntArray[0]['cnt'];
		if ($attendanceSetSlabNameCnt > 0) {
			echo ATTENDANCE_SET_SLAB_NAME_ALREADY_EXISTS;
			die;
		}

		$transferMarksManager->validateAttendanceSetSlabData();

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$return = $transferMarksManager->addAttendanceSetInTransaction($attendanceSetSlabName, SLABS);
			if ($return == false) {
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
				die;
			}
			$attendanceSetIdArray = $transferMarksManager->getMaxAttendanceSetId(SLABS);
			$attendanceSetId = $attendanceSetIdArray[0]['attendanceSetId'];
			if (empty($attendanceSetId)) {
				echo FAILURE;
				die;
			}
			$lectureDeliveredArray = $REQUEST_DATA['lectureDelivered'];
			$lectureAttendedFromArray = $REQUEST_DATA['lectureAttendedFrom'];
			$lectureAttendedToArray = $REQUEST_DATA['lectureAttendedTo'];
			$marksScoredArray = $REQUEST_DATA['marksScored'];

			$insertStr = '';
			foreach($lectureDeliveredArray as $key => $lectureDelivered) {
				$lAFrom = $lectureAttendedFromArray[$key];
				$lATo = $lectureAttendedToArray[$key];
				$marksScored = $marksScoredArray[$key];
				while ($lAFrom <= $lATo) {
					if ($insertStr != '') {
						$insertStr .= ',';
					}
					$insertStr .= "($lectureDelivered, $lAFrom, $marksScored, $labelId, $instituteId, $attendanceSetId)";
					$lAFrom++;
				}
			}
			$return = $transferMarksManager->addAttendanceMarksSlabsInTransaction($insertStr);
			if ($return == false) {
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
				die;
			}

			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				$transferMarksManager->storeTransferMarksManager($transferMarksManager);
				echo SUCCESS;
				die;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}
	}
	else {
		if (!empty($attendanceSetSlabName)) {
			echo ATTENDANCE_SET_SLAB_NAME_NOT_REQUIRED;
			die;
		}
		$attendanceSetSlabName = add_slashes(trim($attendanceSetSlabName));

		$conditions = " AND attendanceSetId != '$attendanceSetSlab' ";

		$attendanceSetSlabNameCntArray = $transferMarksManager->checkAttendanceSetName($attendanceSetSlabName, $conditions);

		$attendanceSetSlabNameCnt = $attendanceSetSlabNameCntArray[0]['cnt'];
		if ($attendanceSetSlabNameCnt > 0) {
			echo ATTENDANCE_SET_SLAB_NAME_ALREADY_EXISTS;
			die;
		}
		$transferMarksManager->validateAttendanceSetSlabData();
		$checkAttendanceSetIdArray = $transferMarksManager->checkAttendanceSet($attendanceSetSlab, SLABS);
		$checkAttendanceSetId = $checkAttendanceSetIdArray[0]['cnt'];
		if ($checkAttendanceSetId == 0) {
			echo ATTENDANCE_SET_NOT_RELATED_TO_SLABS;
			die;
		}

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$lectureDeliveredArray = $REQUEST_DATA['lectureDelivered'];
			$lectureAttendedFromArray = $REQUEST_DATA['lectureAttendedFrom'];
			$lectureAttendedToArray = $REQUEST_DATA['lectureAttendedTo'];
			$marksScoredArray = $REQUEST_DATA['marksScored'];

			$maxMarks = max($marksScoredArray);

			$classSubjectArray = $transferMarksManager->checkAttendanceSetClassSubjects($attendanceSetSlab);
			if ($classSubjectArray[0]['classId'] != '') {
				foreach($classSubjectArray as $record) {
					$classId = $record['classId'];
					$subjectId = $record['subjectId'];
					$subjectAttendanceMarksArray = $transferMarksManager->getAttendanceTypeMarks($classId, $subjectId,SLABS);
					$subjectAttendanceMarks = $subjectAttendanceMarksArray[0]['weightageAmount'];
					if ($maxMarks > $subjectAttendanceMarks) {
						$subjectCodeArray = $transferMarksManager->getSubjectCode($subjectId);
						$subjectCode = $subjectCodeArray[0]['subjectCode'];
						echo ATTENDANCE_SET_MARKS_.$maxMarks._CAN_NOT_BE_GREATER_THAN_TEST_TYPE_MARKS_.$subjectAttendanceMarks._FOR_SUBJECT_.$subjectCode;
						die;
					}
				}
			}

			$return = $transferMarksManager->deleteAttendanceMarksSlabInTransaction($attendanceSetSlab);
			if ($return == false) {
				echo FAILURE_WHILE_REMOVING_OLD_ATTENDANCE_SET_MARKS;
				die;
			}


			$insertStr = '';
			foreach($lectureDeliveredArray as $key => $lectureDelivered) {
				$lAFrom = $lectureAttendedFromArray[$key];
				$lATo = $lectureAttendedToArray[$key];
				$marksScored = $marksScoredArray[$key];
				while ($lAFrom <= $lATo) {
					if ($insertStr != '') {
						$insertStr .= ',';
					}
					$insertStr .= "($lectureDelivered, $lAFrom, $marksScored, $labelId, $instituteId, $attendanceSetSlab)";
					$lAFrom++;
				}
			}
			$return = $transferMarksManager->addAttendanceMarksSlabsInTransaction($insertStr);
			if ($return == false) {
				echo ERROR_WHILE_ADDING_ATTENDANCE_SET;
				die;
			}

			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				$transferMarksManager->storeTransferMarksManager($transferMarksManager);
				echo SUCCESS;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}
	}


// for VSS
//$History: saveAttendanceSetSlabs.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Library/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//








?>