<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE user_role table
// Author : Ajinder Singh
// Created on : (30-6-2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');


//ALTER TABLE `test_type` ADD `classId` INT NOT NULL AFTER `branchId` ;

if(SystemDatabaseManager::getInstance()->startTransaction()) {

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$testTypeArray = $studentManager->getTestTypeClasses();
	if ($testTypeArray[0]['testTypeId'] != '') {
		foreach($testTypeArray as $record) {
			$testTypeId = $record['testTypeId'];
			$classId = $record['classId'];
			$return = $studentManager->updateClassIdInTransaction($testTypeId, $classId);
			if ($return == false) {
				echo ERROR_WHILE_UPDATING_CLASS;
				die;
			}
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


?>