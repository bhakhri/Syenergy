<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 01-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UpdateTotalMarks');
	define('ACCESS','edit');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
	$rollNo = $REQUEST_DATA['rollNo'];
	$studentIdClassArray = $studentManager->getStudentIdClass($rollNo);
	if (count($studentIdClassArray) == 0) {
		echo INVALID_ROLL_NO;
		die;
	}
	$holdResultClasses = Array();
	foreach($studentIdClassArray as $studentIdClassRecord) {
		$studentId = $studentIdClassRecord['studentId'];
		$classId = $studentIdClassRecord['classId'];
		if (isset($REQUEST_DATA['chk_'.$classId]) and $REQUEST_DATA['chk_'.$classId] == 'on') {
			$holdResultClasses[] = $classId;
		}
	}

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$returnStatus = $studentManager->unholdResult($studentId);
		if ($returnStatus == false) {
			echo FAILURE;
			die;
		}
		if (count($holdResultClasses)) {
			$holdResultClassesStr = implode(",",$holdResultClasses);
			$returnStatus = $studentManager->holdResult($studentId,$holdResultClassesStr);
			if ($returnStatus == false) {
				echo FAILURE;
				die;
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
		}
		else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
	}

?>


<?php
// $History: scSaveHoldUnholdResult.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/01/09    Time: 5:04p
//Created in $/Leap/Source/Library/ScStudent
//file added for hold/unhold result.
//



?>