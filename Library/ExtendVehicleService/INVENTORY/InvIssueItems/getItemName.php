<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','InvIssueItems');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$itemCategoryId = $REQUEST_DATA['itemCategoryId'];
    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();
	if ($itemCategoryId != '') {
		$itemNameArray = $itemsManager->getItemDetail($itemCategoryId);
		if(count($itemNameArray) > 0 && is_array($itemNameArray)) {
			echo json_encode($itemNameArray);
		}
		else {
			echo 0;
		}
	}

// $History: getItemName.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:07a
//Created in $/Leap/Source/Library/INVENTORY/InvIssueItems
//new files for inventory issue items
//
//
?>