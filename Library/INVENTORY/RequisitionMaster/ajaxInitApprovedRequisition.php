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
define('MODULE','ApprovedRequisitionMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

	require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();

	$requisitionId = $REQUEST_DATA['requisitionId'];
	if($requisitionId != '') {
		$approvedRequisitionDetailArray = $requisitionManager->getRequisitionDetail("AND irt.requisitionId=".$requisitionId." AND irm.requisitionStatus =1");
		$cnt = count($approvedRequisitionDetailArray);
	}
	$x=0;
	$i=1;
	if($cnt > 0) {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			for($m=0;$m<$cnt;$m++) {
				$itemRequisitionId = 'requisitionTransId_'.$m;
				$requisitionTransId = $REQUEST_DATA[$itemRequisitionId];

				$itemQuantity = 'quantityRequired_'.$m;
				$itemQuantityRequired = $REQUEST_DATA[$itemQuantity];

				if (!is_numeric($itemQuantityRequired) or ($itemQuantityRequired < 1) or ($itemQuantityRequired > 999999.99)) {
					echo "Invalid Quantity Required at Sr. No.".$i;
					die;
				}

				$requisitionTransStatus = $requisitionManager->updateApprovedRequisitionTrans($requisitionTransId,$itemQuantityRequired);
				if($requisitionTransStatus == false) {
					$errorMessage = FAILURE;
				}
				$i++;
			}
			$requisitionMasterStatus = $requisitionManager->updateApprovedRequisitionMaster($requisitionId);
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