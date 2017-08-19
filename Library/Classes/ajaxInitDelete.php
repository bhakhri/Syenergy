<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
//apply transactions.
    if (!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '') {
        echo CLASS_NOT_EXIST;
		die;
    }
	require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classManager = ClassesManager::getInstance();

	$foundArray = $classManager->countStudentsInRelatedClasses($REQUEST_DATA['classId']);
	$count = $foundArray[0]['count'];
	if ($count > 0) {
		echo DELETE_ERROR_STUDENTS_EXIST;
		die;
	}
	$foundArray = $classManager->countTimeTableMappingInRelatedClasses($REQUEST_DATA['classId']);
	$count = $foundArray[0]['count'];
	if ($count > 0) {
		echo DELETE_ERROR_TIME_TABLE_MAPPED;
		die;
	}
	$relatedClassesArray = $classManager->getAllRelatedClasses($REQUEST_DATA['classId']);
	$classIdList = UtilityManager::makeCSList($relatedClassesArray, 'classId');

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$return = $classManager->deleteClassSubjectsInTransaction($classIdList);
		if ($return == false) {
			echo DELETE_ERROR_CLASS_SUBJECT_MAPPED;
			die;
		}

		$return = $classManager->deleteClassGroupsInTransaction($classIdList);
		if ($return == false) {
			echo DELETE_ERROR_CLASS_GROUP_MAPPED;
			die;
		}
		$return = $classManager->deleteClassInTransaction($classIdList);
		if ($return == false) {
			echo CLASS_DELETION_ERROR;
			die;
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo DELETE;
			die;
		}
		else {
			echo FAILURE_WHILE_COMMITTING_TRANSACTION;
			die;
		}
	}
	else {
		echo FAILURE_WHILE_STARTING_TRANSACTION;
		die;
	}
   
// $History: ajaxInitDelete.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/26/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Classes
//done the common messaging
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:42p
//Updated in $/Leap/Source/Library/Classes
//file modified and applied group deletion check
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:39p
//Created in $/Leap/Source/Library/Classes
//file added for deleting class
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>