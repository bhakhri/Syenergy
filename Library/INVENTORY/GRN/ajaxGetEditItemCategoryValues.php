<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : DB
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['poId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();
    
	$itemArray = $grnManager->getEditItemCategory(' AND igt.poId='.$REQUEST_DATA['poId']);
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