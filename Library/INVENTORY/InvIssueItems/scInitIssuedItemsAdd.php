<?php
//-------------------------------------------------------
// Purpose: to design the layout for add unfreeze to class
//
// Author : Jaineesh
// Created on : 02.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InvIssueItems');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
$issueItemsManager = IssueItemsManager::getInstance();

$errorMessage ='';

$store  = $REQUEST_DATA['store'];
$chb  = $REQUEST_DATA['chb'];
$itemCategory  = $REQUEST_DATA['itemCategory'];
$itemId  = $REQUEST_DATA['itemName'];
$issuedTo = $REQUEST_DATA['issuedTo'];
$issuedDate = $REQUEST_DATA['issuedDate'];
$issuedItemStatus = $REQUEST_DATA['issuedItemStatus'];

foreach($chb as $subItemId) {
	$querySeprator = '';
	if($insertValues!=''){
		$querySeprator = ",";
	}
	$insertValues .= "$querySeprator('".$subItemId."')";
}

//echo($insertValue);
//die('line'.__LINE__);

if($issuedItemStatus == '') {
	echo SELECT_ISSUED_STATUS;
	die;
}

if($issuedItemStatus == 2) {
	if($issuedTo == '') {
		echo SELECT_ISSUED_TO;
		die;
	}
}

if($issuedItemStatus == 3) {
	if($issuedTo == '') {
		echo SELECT_TRANSFER_TO;
		die;
	}
}

if($issuedItemStatus == 4) {
	if($issuedTo == '') {
		echo SELECT_RETURN_TO;
		die;
	}
}

$getIssueDate = $issueItemsManager->getIssueDate($store,$insertValues);
$getDate = $getIssueDate[0]['stockInDate'];

if($issuedDate < $getDate) {
	echo DATE_NOT_LESS;
	die;
}

$getItemsIssueDate = $issueItemsManager->getIssuedItemsDate($store,$insertValues);
$getIssueMinDate = $getItemsIssueDate[0]['issueMinDate'];
$getIssueMaxDate = $getItemsIssueDate[0]['issueMaxDate'];

if(($issuedDate < $getIssueMinDate) OR ($issuedDate < $getIssueMaxDate)) {
	echo DATE_NOT_LESS;
	die;
}


$checkIssueStatus = $issueItemsManager->checkItemsIssueStatus($store);

// 1 -> is using for Issuing Authority/Transferred
// 4 -> is using for Returned the items

if ($checkIssueStatus[0]['depttType'] == 1) {

	$returnAvailableItems = $issueItemsManager->checkAvailableIssueItems($store);
	if($returnAvailableItems[0]['totalRecords'] == 0) {
		if($issuedItemStatus == 3 ) {
			echo CANT_TRANSFER_ITEMS;
			die;
		}
	}
		
	$returnItems = $issueItemsManager->checkReturnedStatus($store,$issuedTo);
	if($returnItems[0]['totalRecords'] == 0 ) {
		if($issuedItemStatus == 4 ) {
			echo CANT_RETURN_ITEMS;
			die;
		}
	}
	if($returnItems[0]['totalRecords'] >= 1 ) {
		if($issuedItemStatus == 2 ) {
			echo CANT_ISSUED_ITEMS;
			die;
		}
	}

}

//die('line'.__LINE__);

// 2 -> is using for Issuing Authority
// 3 -> is using for Transferred the Items

if ($checkIssueStatus[0]['depttType'] == 2) {
	$returnAvailableItems = $issueItemsManager->checkAvailableIssueItems($store);
	if($returnAvailableItems[0]['totalRecords'] == 1) {
		if($issuedItemStatus == 4 ) {
			echo CANT_RETURN_ITEMS;
			die;
		}
	}
	if($returnAvailableItems[0]['totalRecords'] == 0) {
		$returnItems = $issueItemsManager->checkReturnedStatus($store,$issuedTo);
			//echo($returnItems[0]['totalRecords']);
			if($returnItems[0]['totalRecords'] == 0) {
				if($issuedItemStatus == 4 ) {
					echo CANT_RETURN_ITEMS;
					die;
				}
			}
			if($returnItems[0]['totalRecords'] > 0) {
				if($issuedItemStatus == 2 ) {
					echo CANT_ISSUED_ITEMS;
					die;
				}
			}
	}
	if($issuedItemStatus == 3) {
		echo CANT_TRANSFER_ITEMS;
		die;
	}

}

// 3 -> is using for End user
// 3 -> is using for Transferred the Items

if ($checkIssueStatus[0]['depttType'] == 3) {
	if($issuedItemStatus == 2) {
		echo CANT_ISSUED_ITEMS;
		die;
	}
	if($issuedItemStatus == 3) {
		echo CANT_TRANSFER_ITEMS;
		die;
	}

	$returnItems = $issueItemsManager->checkReturnedStatus($store,$issuedTo);
	if($returnItems[0]['totalRecords'] == 0 ) {
		echo CANT_RETURN_ITEMS;
		die;
	}
}


/////////////// CHECK ISSUED ITEM STATUS ///////////////

// 2 -> IS USING FOR ISSUED
// 3 -> IS USING FOR TRANSFERRED
// 4 -> IS USING FOR RETURNED

/////////////////////////////////////////////////////////
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	if($issuedItemStatus == 3) {
		$updateStockItems = $issueItemsManager->updateStockItems($issuedDate);
		if($updateStockItems===false){
			echo FAILURE;
			die;
		}
		$insertStockItems = $issueItemsManager->insertStockItems($itemCategory,$itemId,$issuedTo,$issuedDate);
		if($insertStockItems===false){
			echo FAILURE;
			die;
		}
	}
	else {
		//if($returnIssueItems[0]['totalRecords'] > 0) {
			if($issuedItemStatus == 4) {
				$stockItems = $issueItemsManager->updateReturnedStockItemsStatus($issuedTo);
				if($stockItems===false){
					echo FAILURE;
					die;
				}

				$updateIssueItems = $issueItemsManager->updateIssueItemsStatus($issuedDate,$store,$issuedTo);
				if($updateIssueItems===false){
					echo FAILURE;
					die;
				}
			}
		//}
		else {
			$issueItems = $issueItemsManager->insertIssueItems($issuedTo,$store,$issuedDate);
			if($issueItems===false){
				echo FAILURE;
				die;
			}

			$stockItems = $issueItemsManager->updateStockItemsStatus($issuedItemStatus,$store);
			if($stockItems===false){
				echo FAILURE;
				die;
			}
		}
	}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			if($issuedItemStatus == 2) {
				echo ISSUE_SUCCESSFULLY;
				die;
			}
			if($issuedItemStatus == 3) {
				echo TRANSFERRED_SUCCESSFULLY;
				die;
			}
			if($issuedItemStatus == 4) {
				echo RETURNED_SUCCESSFULLY;
				die;
			}
		}
		 else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
		die;
}

// $History: scInitIssuedItemsAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:07a
//Created in $/Leap/Source/Library/INVENTORY/InvIssueItems
//new files for inventory issue items
//
?>