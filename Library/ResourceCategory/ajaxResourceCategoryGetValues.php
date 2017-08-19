<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Resource CATEGORY LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (20.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ResourceCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['resourceTypeId'] ) != '') {
    require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
    $foundArray = ResourceCategoryManager::getInstance()->getResourceCategory(' WHERE resourceTypeId="'.$REQUEST_DATA['resourceTypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>