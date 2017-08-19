<?php
//-------------------------------------------------------
//  This File contains code for fetching attendance set slab
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

	$attendanceSetSlabId = $REQUEST_DATA['attendanceSetSlab'];
	if (empty($attendanceSetSlabId)) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_SLAB_NOT_SELECTED;
		die;
	}

	$attendanceSlabSetIdCntArray = $transferMarksManager->checkAttendanceSetId($attendanceSetSlabId,SLABS);
	$cnt = $attendanceSlabSetIdCntArray[0]['cnt'];
	if ($cnt == 0) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo ATTENDANCE_SET_SLAB_DOES_NOT_EXISTS;
		die;
	}

	$attSlabSetIdDetailsArray = $transferMarksManager->getAttendanceSlabDetails($attendanceSetSlabId);
	$transferMarksManager->setTransferProcessRunning(false);
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode(array('lecturePercentArr' => $attSlabSetIdDetailsArray));

// for VSS
//$History: getAttendanceSetSlabDetails.php $
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