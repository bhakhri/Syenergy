<?php
//-------------------------------------------------------
// Purpose: To generate new item code
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
    

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $gCode = ItemsManager::getInstance()->generateItemCode();
    echo $gCode
// $History: ajaxGetNewItemCode.php $
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
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/04/09   Time: 17:34
//Created in $/Leap/Source/Library/ItemsMaster
//Create "Items Master" module
?>