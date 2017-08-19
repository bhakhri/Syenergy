<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/ReceiveManager.inc.php");
define('MODULE','ReceiveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['orderNo'] ) != '' and trim($REQUEST_DATA['orderNo'])!='') {
    
    $receiveManager =  ReceiveManager::getInstance();
    
    $foundArray = $receiveManager->getOrderDetails(' AND io.orderNo="'.add_slashes(trim($REQUEST_DATA['orderNo'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {
        $foundArray[0]['orderDate']    = UtilityManager::formatDate($foundArray[0]['orderDate']);
        $foundArray[0]['dispatchDate'] = UtilityManager::formatDate($foundArray[0]['dispatchDate']);
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxGetOrderDetails.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:53
//Created in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Created module "Order Receive Master"
?>