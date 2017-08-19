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
define('MODULE','ItemCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['itemCategoryId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
    $foundArray = ItemCategoryManager::getInstance()->getItemCategory(' WHERE itemCategoryId="'.$REQUEST_DATA['itemCategoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>