<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ISSUE ITEMS
// Author : jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
$issueManager = IssueManager::getInstance();
$sno = 1;
$requisitionId = $REQUEST_DATA['requisitionId'];
$checkItemStockArray = $issueManager->checkItemsAvailability($requisitionId);
$cnt = count($checkItemStockArray);
$val = array();
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	$requisitionStatus = ISSUED;
	$itemStatus = ITEM_PENDING;
	for($i=0; $i < $cnt; $i++)  {
		if(is_numeric($checkItemStockArray[$i]['balance']) && is_numeric($checkItemStockArray[$i]['quantityRequired'])) {
			if($checkItemStockArray[$i]['balance'] < $checkItemStockArray[$i]['quantityRequired']) {
			    $requisitionStatus = INCOMPLETE;
				$val[$i] = $checkItemStockArray[$i]['itemName'];
			}
			else {
				$updateItemBalance = $issueManager->reduceItemBalance($checkItemStockArray[$i]['quantityRequired'],$checkItemStockArray[$i]['itemId']);
				if($updateItemBalance == false) {
					echo FAILURE;
					die;
				}
				$itemStatus = ITEM_APPROVED;
				$updateItemStatus = $issueManager->updateItemStatus($itemStatus,$checkItemStockArray[$i]['itemId'],$requisitionId);
				if($updateItemStatus == false) {
					echo FAILURE;
					die;
				}
			}
		}
		else {
			echo "Invalid Quantity Entered";
			die;
		}
	}
	if (count($val)) {
		echo "Following item(s) is/are not available in Store:";
		foreach($val as $itemName) {
			echo "\n".$sno.'. '.$itemName;
			$sno++;
		}
	}
	$approvedRequisitionStatus = $issueManager->updateApprovedRequisitionMaster($requisitionId,$requisitionStatus);
	if($approvedRequisitionStatus == false) {
		echo ''.'~!@~!~@!~'.FAILURE;
		die;
	}
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo ''.'~!@~!~@!~'.SUCCESS;
		die;
	}
	else {
		echo ''.'~!@~!~@!~'.FAILURE;
		die;
	}
}
else {
	echo '~!@~!~@!~'.FAILURE;
	die;
}

// $History: $
//
?>