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
require_once(INVENTORY_MODEL_PATH . "/ReceiveManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ReceiveMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['orderId']) || trim($REQUEST_DATA['orderId']) == '') {
        echo INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER;
        die;
    }
        
    $receiveManager =  ReceiveManager::getInstance();
    
    //**************PREVENT DELETING OF RECORDS if stock updated is 1****************//
     $foundArray3=$receiveManager->checkStockUpdated(trim($REQUEST_DATA['orderId']));
     if($foundArray3[0]['stockUpdated']==1){
         echo 'You cannot delete this record';
         die;
     }
    //**************PREVENT DELETING OF RECORDS if stock updated is 1****************//

  //starting transaction  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //first delete receive order details
      $r1=$receiveManager->deleteReceiveOrderDetails($REQUEST_DATA['orderId']);
      if($r1===false){
        echo FAILURE;
        die;  
      }
      
      //then delete receive order 
      $r2=$receiveManager->deleteReceiveOrder($REQUEST_DATA['orderId']);
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
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:53
//Created in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Created module "Order Receive Master"
?>