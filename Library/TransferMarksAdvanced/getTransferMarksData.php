<?php
//-------------------------------------------------------
//  This File contains code for fetching data for transfer marks
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


	if ($transferMarksManager->getCurrentProcess() == 'showClassSubjects') {
		$classSubjectsArray = $transferMarksManager->getClassSubjects($classId);
		$dbSubjectsArray = explode(',',UtilityManager::makeCSList($classSubjectsArray,'subjectId'));

		if (!is_array($REQUEST_DATA['classSubjects'])) {
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
			$testTypeDetailsArray = $transferMarksManager->getTestTypeDetails($classId, $subjectId);
		}
		$transferMarksManager->setTransferSubjects($REQUEST_DATA['classSubjects']);
	}
	$transferMarksManager->setCurrentProcess('transferMarks');

	$transferSubjects = implode(',', $transferMarksManager->getTransferSubjects());
	$transferSubjectDetailsArray = $transferMarksManager->getClassSubjectsTestTypes($classId, " AND b.subjectId IN ($transferSubjects)");
	$resultArray = array('subjects'=>$transferSubjectDetailsArray);
	$transferMarksManager->setTransferProcessRunning(false);
	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode($resultArray);

// for VSS
//$History: getTransferMarksData.php $
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