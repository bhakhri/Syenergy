<?php
//-------------------------------------------------------
// Purpose: To delete hostel detail
//
// Author : DB
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ItemsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
if (!isset($REQUEST_DATA['itemId']) || trim($REQUEST_DATA['itemId']) == '') {
	$errorMessage = INVALID_ITEM_RECORD;
}

if (trim($errorMessage) == '') {
	require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
	$itemsManager =  ItemsManager::getInstance();

	$itemId=add_slashes(trim($REQUEST_DATA['itemId']));

		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if($itemId != '') {
				$deleteItem = $itemsManager->deleteItem($REQUEST_DATA['itemId']);
				if($deleteItem === false) {
					echo FAILURE;
					die;
				}
				if(SystemDatabaseManager::getInstance()->commitTransaction()){
					echo DELETE;
					die;
				}
				else{
					echo FAILURE;
					die;
				}
			}
			else {
				echo INVALID_ITEM;
				die;
			}
		}
		else{
			echo FAILURE;
			die;
		}
	}
	else {
		echo $errorMessage;
	}
    
// $History: $    
//
?>