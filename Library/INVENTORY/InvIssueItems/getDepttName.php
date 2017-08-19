<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','InvIssueItems');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$invDepttId = $REQUEST_DATA['invDepttId'];
    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();
	if ($invDepttId != '') {
		$nonIssueDepttArray = $itemsManager->getInvNonIssueDepttData($invDepttId);
		if(count($nonIssueDepttArray) > 0 && is_array($nonIssueDepttArray)) {
			echo json_encode($nonIssueDepttArray);
		}
		else {
			echo 0;
		}
	}

// $History: getDepttName.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:07a
//Created in $/Leap/Source/Library/INVENTORY/InvIssueItems
//new files for inventory issue items
//
//
?>