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
require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['orderId'] ) != '' and trim($REQUEST_DATA['orderId'])!='') {
    
    $orderManager =  OrderManager::getInstance();
    
    //check whether this record is dispathed or not.
    //if dispatched then it can not be edited
    $recordArray=$orderManager->getOrder(' WHERE orderId='.$REQUEST_DATA['orderId']);
    if($recordArray[0]['dispatched']==1){
        echo DISPATCHED_EDIT_RESTRICTION;
        die;
    }
        
    $foundArray = $orderManager->getOrderDetails(' AND io.orderId="'.add_slashes(trim($REQUEST_DATA['orderId'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {  
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
// $History: ajaxGetValues.php $
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