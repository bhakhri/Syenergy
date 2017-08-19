<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE ITEM CATEGORY LIST
//
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
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['invDepttId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
    $inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();

    $foundArray = $inventoryDeptartmentManager->getInventoryDepartment(' AND invd.invDepttId="'.$REQUEST_DATA['invDepttId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>