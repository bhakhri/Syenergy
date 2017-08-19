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
define('MODULE','InventoryGRN');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['poId'] ) != '' && trim($REQUEST_DATA['itemCategoryId'] ) != '' && trim($REQUEST_DATA['itemId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();
    
	$qtyArray = $grnManager->getQtyRate(' AND ipt.poId='.$REQUEST_DATA['poId'].' AND ipt.itemCategoryId='.$REQUEST_DATA['itemCategoryId'].' AND ipt.itemId = '.$REQUEST_DATA['itemId']);
	
    if(is_array($qtyArray) && count($qtyArray)>0 ) {
       echo json_encode($qtyArray);
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