<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Room Type LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (19.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['roomTypeId'] ) != '') {
    require_once(MODEL_PATH . "/RoomTypeManager.inc.php");
    $foundArray = RoomTypeManager::getInstance()->getRoomType(' WHERE roomTypeId="'.$REQUEST_DATA['roomTypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>