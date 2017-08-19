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
define('MODULE','ItemsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['itemCategoryId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $foundArray = ItemsManager::getInstance()->getCategory(' WHERE itemCategoryId="'.$REQUEST_DATA['itemCategoryId'].'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ) {
       echo json_encode($foundArray[0]);
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