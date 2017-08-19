<?php
//-------------------------------------------------------
// Purpose: To generate new item code
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
    
    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $gCode = POManager::getInstance()->generatePOCode();
    echo $gCode;
// $History: $
//
?>