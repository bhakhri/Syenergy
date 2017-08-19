<?php
//-------------------------------------------------------
// Purpose: to design the layout for add role to class
//
// Author : Jaineesh
// Created on : (28.09.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleToInventory');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(INVENTORY_MODEL_PATH . "/InventoryMappingManager.inc.php");
$inventoryMappingManager = InventoryMappingManager::getInstance();


$errorMessage ='';

$roleId = $REQUEST_DATA['roleId'];

if (trim($errorMessage) == '') {
	$filter = " AND r.roleId =".$roleId;
	$employeeRecordArray = $inventoryMappingManager->getEmployeeList($filter,'','');
	$cnt = count($employeeRecordArray);
	$j = 1;
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		for($i=0;$i<$cnt;$i++) {
			$employeeId = $employeeRecordArray[$i]['employeeId'];
			$employeeName = $employeeRecordArray[$i]['employeeName'];
			$userId = $employeeRecordArray[$i]['userId'];
			$mappingRoleId = $REQUEST_DATA['role_'.$employeeId];
			$mappingUserId = $REQUEST_DATA['user_'.$employeeId];
			$deleteRoleToInventory = $inventoryMappingManager->deleteRoleMappingValues($roleId,$userId);
				if($deleteRoleToInventory===false) {
					echo FAILURE;
					die;
				}
			if($mappingRoleId != '' ) {
				if($mappingUserId == '') {
					echo 'Select User against Employee Name '.$employeeName.' at Sr. No. '.$j;
					die;
				}
			$insertRoleToInventory = $inventoryMappingManager->insertRoleMappingValues($roleId,$userId,$mappingRoleId,$mappingUserId);
				if($insertRoleToInventory===false) {
					echo FAILURE;
					die;
				}
			}
			$j++;
		}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo MAPPED_SUCCESS;
			die;
		}
		else {
			echo FAILURE;
		}
	}
	else {
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