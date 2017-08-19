<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['itemCode'] ) != '' and trim($REQUEST_DATA['supplierId'])!='') {
    require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
    $foundArray = OrderManager::getInstance()->getItemName(' AND i.itemCode="'.add_slashes(trim($REQUEST_DATA['itemCode'])).'" AND iis.supplierId="'.add_slashes(trim($REQUEST_DATA['supplierId'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxGetItemName.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 14:32
//Updated in $/Leap/Source/Library/INVENTORY/OrderMaster
//Integrated Inventory Management with Leap
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:37
//Created in $/Leap/Source/Library/INVENTORY/OrderMaster
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:47
//Created in $/Leap/Source/Library/OrderMaster
//Added files for "Order Master" module
?>