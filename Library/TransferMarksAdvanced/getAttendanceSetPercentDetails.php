<?php
//-------------------------------------------------------
//  This File contains code for fecthing attendance percent set details
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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


	$attendanceSetPercentId = $REQUEST_DATA['attendanceSetPercent'];
	if (empty($attendanceSetPercentId)) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_PERCENT_NOT_SELECTED;
		die;
	}

	$attendancePercentSetIdCntArray = $transferMarksManager->checkAttendanceSetId($attendanceSetPercentId,PERCENTAGES);
	$cnt = $attendancePercentSetIdCntArray[0]['cnt'];
	if ($cnt == 0) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_PERCENT_DOES_NOT_EXISTS;
		die;
	}

	$attPerSetIdDetailsArray = $transferMarksManager->getAttendancePercentDetails($attendanceSetPercentId);
	$transferMarksManager->setTransferProcessRunning(false);
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode(array('attendancePercentArr' => $attPerSetIdDetailsArray));

// for VSS
//$History: getAttendanceSetPercentDetails.php $
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