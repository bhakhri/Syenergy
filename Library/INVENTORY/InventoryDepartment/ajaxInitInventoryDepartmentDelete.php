<?php
//-------------------------------------------------------
// Purpose: To delete item category detail
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryDeptartment');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['invDepttId']) || trim($REQUEST_DATA['invDepttId']) == '') {
        $errorMessage = 'Invalid Inventory Department';
    }

    if (trim($errorMessage) == '') {
			require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
			$inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();
            $recordArray = $inventoryDeptartmentManager->checkInItemsMaster($REQUEST_DATA['invDepttId']);
				if ($recordArray[0]['found'] > 0) {
					echo DEPENDENCY_CONSTRAINT;
					die;
				}
			$recordIssueArray = $inventoryDeptartmentManager->checkInIssueItemsMaster($REQUEST_DATA['invDepttId']);
				if ($recordIssueArray[0]['found'] > 0) {
					echo DEPENDENCY_CONSTRAINT;
					die;
				}
				else {
					if($inventoryDeptartmentManager->deleteInventoryDepartmentIncharge($REQUEST_DATA['invDepttId'])) {
						if($inventoryDeptartmentManager->deleteInventoryDepartment($REQUEST_DATA['invDepttId'])) {
							echo DELETE;
							die;
						}
					}
        }
    }
    else {
        echo $errorMessage;
    }
?>