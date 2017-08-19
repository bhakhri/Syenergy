<?php
//-------------------------------------------------------
// Purpose: to design the layout for Add Freeze to Class
//
// Author : Jaineesh
// Created on : 02.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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

if(SystemDatabaseManager::getInstance()->startTransaction()) {

		$updateFreezeClass   = $frozenClassManager->updateUnfreezeClasses();
		if($updateFreezeClass===false){
			echo FAILURE;
			die;
		}
		$returnStatus   = $frozenClassManager->insertUnfreezeLog();
		if($returnStatus===false){
			echo FAILURE;
			die;
		}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo UNFROZEN_SUCCESS;
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

// $History: initUnfreezeAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:49p
//Created in $/LeapCC/Library/FrozenClass
//put new ajax files for time table to class
//
?>