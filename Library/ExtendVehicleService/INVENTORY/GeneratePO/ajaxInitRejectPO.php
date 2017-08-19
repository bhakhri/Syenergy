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
define('MODULE','InventoryGeneratePO');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
	$poManager = POManager::getInstance();
	
	$poId = $REQUEST_DATA['poId'];
	
	if($poId != '') {
		$poStatusArray= $poManager->poStatus($poId);
			 if($poStatusArray[0]['status'] != 1){
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$updatePOStatus = $poManager->rejectPOTrans($poId);
					if($updatePOStatus == false) {
						echo FAILURE;
						die;
					}
					$updatePOMaster = $poManager->rejectPOMaster($poId);
					if($updatePOStatus == false) {
						echo FAILURE;
						die;
					}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
						echo SUCCESS;
						die;
					}
					else{
						echo FAILURE;
						die;
					}
				}
				else {
					echo FAILURE;
					die;
				}
		}
		else{
			echo APPROVED_PO_CANCELLED ;
			die;
		}
	}
	
// $History: $
//
?>