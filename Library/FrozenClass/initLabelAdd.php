<?php
//-------------------------------------------------------
// Purpose: to design the layout for add unfreeze to class
//
// Author : Jaineesh
// Created on : 02.07.09
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FrozenTimeTableToClass');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/FrozenClassManager.inc.php");
$frozenClassManager  = FrozenClassManager::getInstance();

$errorMessage ='';

//    if (trim($errorMessage) == '') {

$chb  = $REQUEST_DATA['chb'];
foreach($chb as $classId) {
	$checkTotalMarksTransferredArray = $frozenClassManager->getClassMarksTransferred($classId);
	$checkClassArray = $frozenClassManager->getClass($classId);
	$className = $checkClassArray[0]['className'];
	if ($checkTotalMarksTransferredArray[0]['cnt'] != 0 AND $checkTotalMarksTransferredArray[0]['cnt'] != '' ) {
		echo ERROR_MARKS_NOT_CALCULATED." \n for all subjects of the class ".$className;
		die;
	}
}


if(SystemDatabaseManager::getInstance()->startTransaction()) {

		$updateFreezeClass   = $frozenClassManager->updateFreezeClass();
		if($updateFreezeClass===false){
			echo FAILURE;
			die;
		}
		$returnStatus   = $frozenClassManager->insertFreezeUnfreezeLog();
		if($returnStatus===false){
			echo FAILURE;
			die;
		}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo FROZEN_SUCCESS;
			die;
		}
		 else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
		die;
}

// $History: initLabelAdd.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/28/09   Time: 5:54p
//Updated in $/LeapCC/Library/FrozenClass
//done changes for checking if external marks for all subjects have been
//transferred or not.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/17/09   Time: 5:24p
//Updated in $/LeapCC/Library/FrozenClass
//Change in coding during class has been frozen if no marks has been
//transferred of class.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:49p
//Created in $/LeapCC/Library/FrozenClass
//put new ajax files for time table to class
//
?>