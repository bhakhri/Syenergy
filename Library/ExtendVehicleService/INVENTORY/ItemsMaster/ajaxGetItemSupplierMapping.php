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
define('MODULE','ItemsSupplierMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['itemCode'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    
    //check item existence
    $foundArray1 = ItemsManager::getInstance()->getItem(' AND itemCode="'.add_slashes(trim($REQUEST_DATA['itemCode'])).'"');
    if(!is_array($foundArray1) or count($foundArray1)<=0){
        echo -1;
        die;
    }
    
    $foundArray = ItemsManager::getInstance()->getItemSupplierMapping(' AND i.itemCode="'.add_slashes(trim($REQUEST_DATA['itemCode'])).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
          echo json_encode($foundArray).'!~!~!'.$foundArray1[0]['itemId'];
    }
    else {
        echo '0!~!~!'.$foundArray1[0]['itemId'] ;
    }
}
else{
    echo 0;
}
// $History: ajaxGetItemSupplierMapping.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/24/10    Time: 9:51a
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//initial check in
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:07
//Created in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Created Item & Supplier Mapping module
?>