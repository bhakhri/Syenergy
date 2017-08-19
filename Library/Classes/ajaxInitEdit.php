<?php
//
//  This File calls Edit Function used in adding Bank Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(BL_PATH . '/Classes/doAllValidations.php');
require_once(MODEL_PATH . "/ClassesManager.inc.php");

$classManager = ClassesManager::getInstance();

if (isset($REQUEST_DATA['classId']) and trim($REQUEST_DATA['classId']) != '') {
	$classId = $REQUEST_DATA['classId'];
	$studyPeriodArray = $classManager->getClassStudyPeriods($classId);
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($studyPeriodArray as $studyPeriodRecord) {
			$studyPeriodId = $studyPeriodRecord['studyPeriodId'];
			$isActive = $REQUEST_DATA["sp_".$studyPeriodId];
			$relatedClassArray = $classManager->getRelatedClassStudyPeriod($classId, $studyPeriodId);
			$thisClassId = $relatedClassArray[0]['classId'];
			$return = $classManager->updateClassInTransaction($thisClassId, $isActive);
			if ($return == false) {
				echo ERROR_WHILE_UPDATING_CLASS;
				die;
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
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
else {
	echo CLASS_NOT_EXIST;
}
//$History: ajaxInitEdit.php $	
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 6:05p
//Updated in $/LeapCC/Library/Classes
//updated code for class updation
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
//User: Admin        Date: 8/05/08    Time: 6:40p
//Created in $/Leap/Source/Library/Classes
//file added for editing a class, the classes are deleted first and then
//new ones are added
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>