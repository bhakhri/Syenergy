<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudentsAdvanced');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$process = add_slashes(trim($REQUEST_DATA['process']));
if ($process == 'promote') {
	//promoteClass has to be an array
	if (!is_array($REQUEST_DATA['promoteClass'])) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	if (isset($REQUEST_DATA['copyPrivileges'])) {
		if (!is_array($REQUEST_DATA['copyPrivileges'])) {
			echo INVALID_DETAILS_FOUND;
			die;
		}
		else {
			if (!is_array($REQUEST_DATA['copyGroups'])) {
				echo INVALID_DETAILS_FOUND;
				die;
			}
			foreach($REQUEST_DATA['copyPrivileges'] as $classId) {
				if (!in_array($classId, $REQUEST_DATA['copyGroups'])) {
					echo INVALID_DETAILS_FOUND;
					die;
				}
			}
		}
	}
	if (isset($REQUEST_DATA['copyGroups'])) {
		if (!is_array($REQUEST_DATA['copyGroups'])) {
			echo INVALID_DETAILS_FOUND;
		}
		else {
			foreach($REQUEST_DATA['copyGroups'] as $classId) {
				if (!in_array($classId, $REQUEST_DATA['promoteClass'])) {
					echo INVALID_DETAILS_FOUND;
					die;
				}
			}
		}
	}
	require_once(BL_PATH . '/PromoteStudentsAdvanced/getClassesReadyForPromotion.php');
	$validClassesListArray = explode(',', UtilityManager::makeCSList($validClassesArray, 'classId'));
	foreach($REQUEST_DATA['promoteClass'] as $classId) {
		if (!in_array($classId, $validClassesListArray)) {
			echo INVALID_CLASS_FOUND_FOR_PROMOTION;
			die;
		}
	}
	$promoteClassArray = $REQUEST_DATA['promoteClass'];

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(MODEL_PATH . "/GroupManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $groupManager = GroupManager::getInstance();
	 $callGroupCopyCodeFile = true;

	require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classManager = ClassesManager::getInstance();

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($promoteClassArray as $classId) {
			$isLastClass = false;
			$classIdArray = $studentManager->getNextClassId($classId);
			$nextClassId = 0;
			$isActive = 0;
			if (isset($classIdArray[0]['classId']) and !empty($classIdArray[0]['classId'])) {
				$nextClassId = $classIdArray[0]['classId'];
				$isActive = $classIdArray[0]['isActive'];
			}
			if (empty($nextClassId)) {
				# TO CREATE STUDY PERIODS WITH 99999 PERIOD VALUE
				$return = $classManager->checkCopyPeriodicity();
				if ($return == false) {
					echo FAILURE;
					die;
				}
				$nextClassIdArray = $classManager->getAlumniClassId($classId);
				$nextClassId = $nextClassIdArray[0]['classId'];
				if (empty($nextClassId)) {
					$alumniClassArray = $classManager->getLastClassDetails($classId);
					if (count($alumniClassArray) != 1) {
						echo MORE_THAN_ONE_ALUMNI_CLASS_FOUND;
						die;
					}
					$return = $classManager->createAlumniClassInTransaction($alumniClassArray[0]);
					if ($return == false) {
						echo ERROR_WHILE_CREATING_ALUMNI_CLASS;
						die;
					}
				}
				$nextClassIdArray = $classManager->getAlumniClassId($classId);
				$nextClassId = $nextClassIdArray[0]['classId'];
				$isLastClass = true;
			}

			if (empty($nextClassId)) {
				echo ERROR_WHILE_CREATING_ALUMNI_CLASS;
				die;
			}


			$return = $studentManager->promoteClassStudentsInTransaction($nextClassId, $classId);
			if ($return == false) {
				echo FAILURE_WHILE_PROMOTING_STUDENTS;
				die;
			}
			if ($nextClassId != 0 and $isActive == 2) {
				$return = $studentManager->makeClassActiveInTransaction($nextClassId);
				if ($return == false) {
					echo FAILURE_WHILE_MAKING_NEW_CLASS_ACTIVE;
					die;
				}
			}
			$return = $studentManager->makeClassPastInTransaction($classId);
			if ($return == false) {
				echo FAILURE_WHILE_MAKING_OLD_CLASS_PAST;
				die;
			}

			# CODE FOR COPYING GROUPS
			if (is_array($REQUEST_DATA['copyGroups'])) {
				if (in_array($classId, $REQUEST_DATA['copyGroups'])) {
					$oldClassId = $classId;
					$newClassId = $nextClassId;
					if ($isLastClass == false) {
						require(BL_PATH . '/Group/ajaxCopyGroupsCode.php');		######	DO NOT MAKE IT REQUIRE_ONCE
					}
					# CODE FOR COPYING GROUP PRIVILEGES
					if (is_array($REQUEST_DATA['copyPrivileges'])) {
						if (in_array($classId, $REQUEST_DATA['copyPrivileges'])) {
							$callGroupPrivilegesCopyCodeFile = true;
							if ($isLastClass == false) {
								require(BL_PATH . '/Group/ajaxCopyGroupPrivilegesCode.php');		######	DO NOT MAKE IT REQUIRE_ONCE
							}
						}
					}
				}
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo STUDENTS_PROMOTED;
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
else if ($process == 'classSubjects') {
	require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
	$subjectToClassManager = SubjectToClassManager::getInstance();
	$classId		= $REQUEST_DATA['classId'];
	if (empty($classId) or !is_numeric($classId)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	$returnStatus   = $subjectToClassManager->insertSubToClass($classId);
	if($returnStatus == false) {
		echo FAILURE;
	}
	else {
		echo SUCCESS;
	}
}
else if ($process == 'timeTableToClass') {
}
else {
	echo INVALID_DETAILS_FOUND;
	die;
}

// $History: promoteStudentsAdvanced.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/01/10    Time: 2:28p
//Updated in $/LeapCC/Library/PromoteStudentsAdvanced
//done changes for code of making Alumni Class.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities
//


?>