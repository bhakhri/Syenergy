<?php
////  This File gets  record from the batch Form Table
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BatchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['batchId'] ) != '') {
    require_once(MODEL_PATH . "/BatchManager.inc.php");
    $foundArray = BatchManager::getInstance()->getBatch(' WHERE batchId="'.$REQUEST_DATA['batchId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>