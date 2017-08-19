<?php
//-------------------------------------------------------
//  This File contains subjects and test types related to a class
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
	
	if (false == TransferMarksManager::getInstance()->fetchTransferMarksManager()) {
		$transferMarksManager = TransferMarksManager::getInstance();
	}
	else {
		$transferMarksManager = TransferMarksManager::getInstance()->fetchTransferMarksManager();
	}

	$transferProcessRunning = $transferMarksManager->getTransferProcessRunning();
	if ($transferProcessRunning == true) {
		echo TRANSFER_PROCESS_ALREADY_RUNNING_IN_SAME_SESSION;
		die;
	}

	$transferMarksManager->setTransferProcessRunning(true);


	$labelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['class1'];

	$timeTableClassesArray = $transferMarksManager->getTimeTableClasses($labelId);
	$timeTableClassesArray = explode(',', UtilityManager::makeCSList($timeTableClassesArray, 'classId'));
	if (!in_array($classId, $timeTableClassesArray)) {
		echo INVALID_CLASS;
		die;
	}
	$transferSubjectsArray = $transferMarksManager->getTransferSubjects();
	$subjectsArray = $transferMarksManager->getClassSubjectsTestTypes($classId);
	$resultArray = array('subjects'=>$subjectsArray, 'transferSubjects' => $transferSubjectsArray);
	$transferMarksManager->unsetAllValues();
	$transferMarksManager->resetTimeTableClass($labelId, $classId);
	$transferMarksManager->setCurrentProcess('showClassSubjects');
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode($resultArray);

// for VSS
//$History: getClassSubjectsTestTypes.php $
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