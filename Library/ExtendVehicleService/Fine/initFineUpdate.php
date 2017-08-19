<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Jaineesh
// Created on : (03.07.09)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineList');
define('ACCESS','add');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/FineManager.inc.php");
$fineManager = FineManager::getInstance();

$errorMessage ='';

    if (trim($errorMessage) == '') {

		$statusUpdate	= $REQUEST_DATA['statusUpdate'];
		$appproveReason	= $REQUEST_DATA['appproveReason'];

		$returnStatus = $fineManager->updateFine($statusUpdate,$REQUEST_DATA['chb'],$appproveReason);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
        echo $errorMessage;
    }
?>