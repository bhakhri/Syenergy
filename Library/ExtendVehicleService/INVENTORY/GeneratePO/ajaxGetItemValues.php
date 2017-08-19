<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : DB
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGeneratePO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['itemCategoryId'] ) != '' && trim($REQUEST_DATA['indentId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();
    
	$itemArray = $poManager->getItem(' AND iit.itemCategoryId='.$REQUEST_DATA['itemCategoryId'].' AND iit.indentId='.$REQUEST_DATA['indentId']);
	
    if(is_array($itemArray) && count($itemArray)>0 ) {
       echo json_encode($itemArray);
    }
    else {
        echo 0 ;
    }
}
else{
    echo 0;
}
// $History: $
//
?>