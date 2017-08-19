<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','OrderMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['orderId']) || trim($REQUEST_DATA['orderId']) == '') {
        echo ORDER_NOT_EXIST;
        die;
    }
        
    $orderManager =  OrderManager::getInstance();
    //check whether this record is dispathed or not.
    //if dispatched then it can not be deleted
    $recordArray=$orderManager->getOrder(' WHERE orderId='.$REQUEST_DATA['orderId']);
    if($recordArray[0]['dispatched']==1){
        echo DISPATCHED_DELETE_RESTRICTION;
        die;
    }
  
  //starting transaction  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //first delete order details
      $r1=$orderManager->deleteOrderDetails($REQUEST_DATA['orderId']);
      if($r1===false){
        echo FAILURE;
        die;  
      }
      
      //then delete order 
      $r2=$orderManager->deleteOrder($REQUEST_DATA['orderId']);
      if($r2===false){
        echo FAILURE;
        die;  
      }
   //now commit transaction   
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo DELETE;
        die;
       }
   else {
     echo FAILURE;
     die;
    }
  }
  else {
    echo FAILURE;
    die;
  }
   
// $History: ajaxInitDelete.php $    
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