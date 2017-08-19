<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW Requisition
// Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionIssueMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

	require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
    $issueManager = IssueManager::getInstance();

	$requisitionId = $REQUEST_DATA['requisitionId'];


	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		if($requisitionId != '') {
			$rejectRequisitionStatus = $issueManager->updateRejectedRequisition($requisitionId);
			if($rejectRequisitionStatus == false) {
				echo FAILURE;
				die;
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
	}
	else {
		echo FAILURE;
		die;
	}

	
// $History: $
//
?>