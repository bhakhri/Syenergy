<?php
//-------------------------------------------------------
//  This File contains code to save test types
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
	$subjectId = $REQUEST_DATA['currentSubjectHidden'];
	$transferMarksManager->validateTimeTableClass($labelId, $classId);
	$transferMarksManager->checkClassSubject($classId, $subjectId);

	$classDetailsArray = $transferMarksManager->getClassDetails($classId);
	$universityId = $classDetailsArray[0]['universityId'];
	$degreeId = $classDetailsArray[0]['degreeId'];
	$branchId = $classDetailsArray[0]['branchId'];
	$studyPeriodId = $classDetailsArray[0]['studyPeriodId'];
	$instituteId = $classDetailsArray[0]['instituteId'];
	$className = $classDetailsArray[0]['className'];
	
	$transferMarksManager->checkIfInArray($subjectId, $REQUEST_DATA['copyEvTo']);

	$testTypeDetailsArray = $transferMarksManager->getTestTypeDetails($classId, $subjectId);

	$evNonAttArray = $transferMarksManager->getEvCriteriaNonAtt();
	$evNonAttValArray = explode(',', UtilityManager::makeCSList($evNonAttArray,'evaluationCriteriaId'));

	$evAttArray = $transferMarksManager->getEvCriteriaAtt();
	$evAttValArray = explode(',', UtilityManager::makeCSList($evAttArray,'evaluationCriteriaId'));

	foreach($testTypeDetailsArray as $testTypeDetailRecord) {
		$testTypeCategoryId = $testTypeDetailRecord['testTypeCategoryId'];
		$testTypeName = $testTypeDetailRecord['testTypeName'];
		$examType = $testTypeDetailRecord['examType'];
		$isAttendanceCategory = $testTypeDetailRecord['isAttendanceCategory'];
		$evaluationCriteriaId = $testTypeDetailRecord['evaluationCriteriaId'];
		$cnt = $testTypeDetailRecord['cnt'];
		$weightageAmount = $testTypeDetailRecord['weightageAmount'];
		
		if (!isset($REQUEST_DATA['ttc_' . $testTypeCategoryId])) {
			echo TESTTYPE_DATA_NOT_FOUND_FOR_.$testTypeName;
			die;
		}
		if (!isset($REQUEST_DATA['cnt_' . $testTypeCategoryId])) {
			echo CNT_DATA_NOT_FOUND_FOR_.$testTypeName;
			die;
		}
		if (!isset($REQUEST_DATA['wtg_' . $testTypeCategoryId])) {
			echo WEIGHTAGE_DATA_NOT_FOUND_FOR_.$testTypeName;
			die;
		}

		$postTestTypeCategoryEv = $REQUEST_DATA['ttc_' . $testTypeCategoryId];
		if (empty($postTestTypeCategoryEv)) {
			continue;
		}

		if ($isAttendanceCategory) {
			if (!in_array($postTestTypeCategoryEv, $evAttValArray)) {
				echo INVALID_CRITERIA_FOR_.$testTypeName;
				die;
			}
			$postCnt = $REQUEST_DATA['cnt_' . $testTypeCategoryId];
			if (!empty($postCnt)) {
				echo CNT_NOT_REQUIRED_FOR_.$testTypeName;
				die;
			}
		}
		else {
			if (!in_array($postTestTypeCategoryEv, $evNonAttValArray)) {
				echo INVALID_CRITERIA_FOR_.$testTypeName;
				die;
			}
			if ($postTestTypeCategoryEv == 1 || $postTestTypeCategoryEv == 8 ) {
				$postCnt = $REQUEST_DATA['cnt_' . $testTypeCategoryId];
				if (empty($postCnt) or !is_numeric($postCnt) or $postCnt < 1) {
					echo ENTER_CNT_FOR_.$testTypeName;
					die;
				}
			}
			else {
				$postCnt = $REQUEST_DATA['cnt_' . $testTypeCategoryId];
				if (!empty($postCnt)) {
					echo CNT_NOT_REQUIRED_FOR_.$testTypeName;
					die;
				}
			}
		}
		$postWtg = $REQUEST_DATA['wtg_' . $testTypeCategoryId];
		if (empty($postWtg) or !is_numeric($postWtg) or $postWtg < 1) {
			echo ENTER_WEIGHTAGE_FOR_.$testTypeName;
			die;
		}
	}

	$otherSubjects = $transferMarksManager->getClassOtherSubjects($classId, $subjectId);
	$otherSubjectArray = array();

	foreach($otherSubjects as $otherSubjectRecord) {
		$otherSubjectArray[] = $otherSubjectRecord['subjectId'];
	}

	if (is_array($REQUEST_DATA['copyEvTo'])) {
		foreach($REQUEST_DATA['copyEvTo'] as $key => $subjectId) {	# SUBJECT ID IS OVERWRITTEN HERE
			if (!in_array($subjectId, $otherSubjectArray)) {
				echo INVALID_SUBJECT_FOUND_FOR_COPYING;
				die;
			}
		}
	}

	if (is_array($REQUEST_DATA['copyEvTo'])) {
		$testTypesSubjectsArray = $REQUEST_DATA['copyEvTo'];
	}
	else {
		$testTypesSubjectsArray = array();
	}

	$testTypesSubjectsArray[] = $REQUEST_DATA['currentSubjectHidden']; # AS SUBJECT ID IS OVERWRITTEN 

	$testTypeSubjectList = implode(',', $testTypesSubjectsArray);

	$subjectArray = $transferMarksManager->getSubjectCode($testTypeSubjectList);


	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($subjectArray as $subjectRecord) {
			$insertStr = '';
			$subjectId = $subjectRecord['subjectId'];
			$subjectCode = $subjectRecord['subjectCode'];
			$subjectTypeId = $subjectRecord['subjectTypeId'];

			$returnStatus = $transferMarksManager->deleteTotalTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}
			$returnStatus = $transferMarksManager->deleteTestTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}

			$return = $transferMarksManager->deleteTestTypesInTransaction($universityId, $degreeId, $branchId, $studyPeriodId, $labelId, $instituteId, $subjectId);
			if ($return == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo ERROR_WHILE_DELETING_OLD_TEST_TYPES_FOR_.$subjectCode;
				die;
			}
			
			foreach($testTypeDetailsArray as $testTypeDetailRecord) {
				$testTypeCategoryId = $testTypeDetailRecord['testTypeCategoryId'];
				$testTypeName = $testTypeDetailRecord['testTypeName'];
				$examType = $testTypeDetailRecord['examType'];
				$isAttendanceCategory = $testTypeDetailRecord['isAttendanceCategory'];
				$evaluationCriteriaId = $testTypeDetailRecord['evaluationCriteriaId'];
				$cnt = $testTypeDetailRecord['cnt'];
				$weightageAmount = $testTypeDetailRecord['weightageAmount'];

				$postWtg = $REQUEST_DATA['wtg_' . $testTypeCategoryId];
				$postTestTypeCategoryEv = $REQUEST_DATA['ttc_' . $testTypeCategoryId];
				$postCnt = $REQUEST_DATA['cnt_' . $testTypeCategoryId];

				if (empty($postTestTypeCategoryEv)) {
					continue;
				}

				$conductingAuthority = 0;
				if ($examType == 'C') {
					$conductingAuthority = 2;
				}
				else if ($examType == 'PC') {
					if ($isAttendanceCategory == 0) {
						$conductingAuthority = 1;
					}
					else if ($isAttendanceCategory == 1) {
						$conductingAuthority = 3;
					}
				}
				if (!empty($insertStr)) {
					$insertStr .= ', ';
				}
				$saveTestTypeName = $subjectCode . '#' . $testTypeName . '#' . $className;
				$saveTestTypeCode = $saveTestTypeName;
				$saveTestTypeAbbr = $saveTestTypeName;
				$insertStr .= "('$saveTestTypeName', '$saveTestTypeCode', '$saveTestTypeAbbr', '$universityId', '$degreeId', '$branchId', '$postWtg', '0', '$subjectId', '$studyPeriodId', '$postTestTypeCategoryEv', '$postCnt', 0, '$subjectTypeId', '$conductingAuthority', '$labelId', '$testTypeCategoryId', '$instituteId', $classId)";
			}
			if ($insertStr != '') {#SAVING TEST TYPES FOR EACH SUBJECT RATHER THAN FOR ALL SUBJECTS AT ONCE TO SIMPLYFY PROCESS
				$return = $transferMarksManager->addTestTypesInTransaction($insertStr);
				if ($return == false) {
					echo ERROR_WHILE_ADDING_TEST_TYPES_FOR_.$subjectCode;
					die;
				}
			}
		}
		#STORING SELECTED SUBJECTS, TO SHOW THEM SELECTED
		$classSubjectsArray = $transferMarksManager->getClassSubjects($classId);
		$dbSubjectsArray = explode(',',UtilityManager::makeCSList($classSubjectsArray,'subjectId'));


		if (is_array($REQUEST_DATA['classSubjects'])) {
			foreach($REQUEST_DATA['classSubjects'] as $key => $subjectId) {
				if (!in_array($subjectId, $dbSubjectsArray)) {
					echo INVALID_SUBJECTS;
					die;
				}
			}
			$transferMarksManager->setTransferSubjects($REQUEST_DATA['classSubjects']);
		}
		#/STORING SELECTED SUBJECTS, TO SHOW THEM SELECTED
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			$transferMarksManager->setTransferProcessRunning(false);
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

// for VSS
//$History: saveTestTypes.php $
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