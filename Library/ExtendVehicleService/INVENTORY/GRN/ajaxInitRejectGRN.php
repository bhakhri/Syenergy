<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW Requisition
// Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGRN');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();
	
	$grnId = $REQUEST_DATA['grnId'];
	
	if($grnId != '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$cancelledStatus = $grnManager->cancelledGRN($grnId);
			if($cancelledStatus == false) {
				echo FAILURE;
				die;
			}

			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo GRN_CANCEL;
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