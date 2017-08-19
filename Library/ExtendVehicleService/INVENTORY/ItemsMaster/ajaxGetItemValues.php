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
define('MODULE','ItemsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['itemId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $foundArray = ItemsManager::getInstance()->getItem('AND itemId="'.$REQUEST_DATA['itemId'].'"');
	//get the mapping
   // $foundArray2= ItemsManager::getInstance()->getItsemSupplierMapping(' WHERE itemId="'.$REQUEST_DATA['itemId'].'"');
    
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
// $History: ajaxGetItemValues.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/24/10    Time: 9:51a
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//initial check in
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 14/09/09   Time: 14:30
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Corrected logic
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 14/09/09   Time: 13:10
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Removed "Item-Supplier" mapping from "Items Master" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 14:32
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Integrated Inventory Management with Leap
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:37
//Created in $/Leap/Source/Library/INVENTORY/ItemsMaster
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/09   Time: 13:13
//Updated in $/Leap/Source/Library/ItemsMaster
//Updated items master by adding item category and supplier fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/04/09   Time: 17:34
//Created in $/Leap/Source/Library/ItemsMaster
//Create "Items Master" module
?>