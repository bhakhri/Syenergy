<?php
//-------------------------------------------------------
//  This File is used for fetching Teacher for 
//
//
// Author :Jaineesh
// Created on : 30.09.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoleToInventory');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/InventoryMappingManager.inc.php");
    $inventoryMappingManager = InventoryMappingManager::getInstance();
	$roleId = $REQUEST_DATA['roleId'];
	$userArray = $inventoryMappingManager->getUserData(" WHERE u.roleId = ".$roleId);
	echo json_encode($userArray);

// $History: $
//
//
?>