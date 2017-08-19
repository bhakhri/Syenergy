<?php
//-------------------------------------------------------
//  This File contains details of test types
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

	if ($transferMarksManager->getCurrentProcess() != 'showClassSubjects') {
		echo INVALID_PROCESS;
		die;
	}

	$labelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['class1'];
	$subjectId = $REQUEST_DATA['subjectId'];

	$transferMarksManager->validateTimeTableClass($labelId, $classId);
	$transferMarksManager->checkClassSubject($classId, $subjectId);

	$testTypeDetailsArray = $transferMarksManager->getTestTypeDetails($classId, $subjectId);
	
	$subjectArray = $transferMarksManager->getSubjectCode($subjectId);
	
	$evNonAttArray = $transferMarksManager->getEvCriteriaNonAtt();
	
	$evAttArray = $transferMarksManager->getEvCriteriaAtt();
	
	$otherSubjects = $transferMarksManager->getClassOtherSubjects($classId, $subjectId);

	$resultArray = 	array('subjectArray' => $subjectArray, 'testTypeDetails'=>$testTypeDetailsArray, 'evNonAttArray'=>$evNonAttArray, 'evAttArray' => $evAttArray, 'otherSubjects' => $otherSubjects);

	$transferMarksManager->storeTransferMarksManager($transferMarksManager);
	echo json_encode($resultArray);


// for VSS
//$History: getTestTypeDetails.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Library/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//






?>