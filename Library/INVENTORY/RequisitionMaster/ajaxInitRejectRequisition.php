<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW Requisition
// Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApprovedRequisitionMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();

	$requisitionId = $REQUEST_DATA['requisitionId'];
	
	if($requisitionId != '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$requisitionMasterStatus = $requisitionManager->updateRejectedRequisition($requisitionId);
			if($requisitionMasterStatus == false) {
				$errorMessage = FAILURE;
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
	
// $History: $
//
?>