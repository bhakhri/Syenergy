<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD/Edit An Order Receive Details
// Author : Dipanjan Bhattacharjee
// Created on : (02.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/ReceiveManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ReceiveMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $receiveManager = ReceiveManager::getInstance();
    
    $errorMessage ='';
    if (!isset($REQUEST_DATA['orderId']) || trim($REQUEST_DATA['orderId']) == '') {
        echo ENTER_ORDER_NO."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['itemsReceived']) || trim($REQUEST_DATA['itemsReceived']) == '')) {
        echo ITEM_INFO_MISSING."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['receiveDate']) || trim($REQUEST_DATA['receiveDate']) == '')) {
        echo ENTER_RECEIVE_DATE."\n";
        die;  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['taxAmount']) || trim($REQUEST_DATA['taxAmount']) == '')) {
        echo ENTER_TAX_AMOUNT."\n";
        die;  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['totalAmount']) || trim($REQUEST_DATA['totalAmount']) == '')) {
        echo ENTER_TOTAL_AMOUNT."\n";
        die;  
    } 
    
    $itemsReceived=explode(',',trim($REQUEST_DATA['itemsReceived']));
    $itemsPrice=explode(',',trim($REQUEST_DATA['priceStr']));
    
    $orderId=trim($REQUEST_DATA['orderId']);
    
    //**************PREVENT EDITING OF RECORDS if stock updated is 1****************//
     $foundArray3=$receiveManager->checkStockUpdated($orderId);
     if($foundArray3[0]['stockUpdated']==1){
         echo 'You cannot edit this record';
         die;
     }
    //**************PREVENT EDITING OF RECORDS if stock updated is 1****************//
    
    
    //get the info corresponding to this order id
    $foundArray=$receiveManager->getOrderDetailIds(' AND io.orderId='.$orderId);
    
    
    //check if this orderId exists and dispatched=1
    if(!is_array($foundArray) or count($foundArray)==0){
        echo INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER;
        die;
    }
    
    //check this itemIds against item_master table
    $itemIds=UtilityManager::makeCSList($foundArray,'itemId');
    $foundArray2=$receiveManager->checkItemIds($itemIds);
    if(!is_array($foundArray2) or count($foundArray2)==0){
        echo INVALID_ITEM_RECORD;
        die;
    }
    $itemIds2=explode(',',UtilityManager::makeCSList($foundArray2,'itemId'));
    $itemIds1=array_unique(explode(',',$itemIds));
    if( (count($itemIds1)!=count($itemIds2)) or (count(array_diff($itemIds1,$itemIds2))>0) ){
       echo INVALID_ITEM_RECORD;
       die; 
    }
    
   $count=count($foundArray);
   //check if the no of items from database and front end matches
   if($count!=count($itemsReceived)){
      echo INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER;
      die;
   }
   
   $serverDate=explode('-',date('Y-m-d'));
   $sDate1=explode('-',$REQUEST_DATA['receiveDate']);
   $sDate2=explode('-',$foundArray[0]['dispatchDate']);
   $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
   
    //check if received date is bigger than server date
    $end_date=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
    if(($start_date-$end_date)>0){
        echo RECEIVE_DATE_VALIDATION2;
        die;
    }
   
   //check if receive date is smaller than dispatched date
   $end_date=gregoriantojd($sDate2[1], $sDate2[2], $sDate2[0]);
   if(($end_date - $start_date)>0){
        echo RECEIVE_DATE_VALIDATION2;
        die;
   }
   
   
   $totalAmt=intval(trim($REQUEST_DATA['totalAmount']));
   $taxAmt=intval(trim($REQUEST_DATA['taxAmount']));
   
   //check if totalAmt <>'' and >0
   if($totalAmt=='' OR $totalAmt<=0){
       echo TOTAL_AMOUNT_VALIDATION1;
       die;
   }
   
   //check if totalAmt=tax+totalPrice
   $price=0;
   for($i=0;$i<$count;$i++){
       if(trim($itemsPrice[$i])==''){
           echo ENTER_PRICE_AMOUNT;
           die;
       }
       if(!is_numeric(trim($itemsPrice[$i]))){
          echo ENTER_PRICE_AMOUNT_IN_NUMERIC;
          die;
       }
       if(trim($itemsPrice[$i])<=0){
           echo ENTER_PRICE_AMOUNT_GREATER_THAN_ZERO;
           die;
       }
       $price +=intval(trim($itemsPrice[$i]));
   }
   
   if($totalAmt !=($taxAmt+$price)){
       echo TOTAL_AMOUNT_VALIDATION2;
       die;
   }
   
   
  $userId=$sessionHandler->getSessionVariable('UserId');
  
  $updateStore=add_slashes(trim($REQUEST_DATA['updateStore']));
  if($updateStore!=1 and $updateStore!=0){
      echo 'Stock updation value is missing or invalid';
      die;
  }
  
  //starting transaction
  if(SystemDatabaseManager::getInstance()->startTransaction()) {

   //first delete entries from receive_order_details table
   $r1=$receiveManager->deleteReceiveOrderDetails($orderId);
   if($r1===false){
       echo FAILURE;
       die;
   }
   
   //now delete entries from receive_order table
   $r2=$receiveManager->deleteReceiveOrder($orderId);
   if($r2===false){
       echo FAILURE;
       die;
   }
   
   //then add in receive_order_table
   $r3=$receiveManager->addReceiveOrder($orderId,$REQUEST_DATA['receiveDate'],$userId,add_slashes($totalAmt),add_slashes($taxAmt),$updateStore,add_slashes(trim($REQUEST_DATA['remarks'])));
   if($r3===false){
       echo FAILURE;
       die;
   }
   
   $insStr='';
   for($i=0;$i<$count;$i++){
       if($insStr!=''){
           $insStr .=' , ';
       }
      if(trim($itemsReceived[$i])==''){
          echo ENTER_ITEMS_RECIVED;
          die;
      }
      if(!is_numeric(trim($itemsReceived[$i]))){
          echo ENTER_NUMERIC_VALUE_FOR_RECEIVED;
          die;
      }
      if(trim($itemsReceived[$i])>$foundArray[$i]['quantity']){
          echo ORDER_RECEIVED_VALIDATION;
          die;
      } 
      $insStr .=" ( $orderId,".$foundArray[$i]['itemId'].",".intval(trim($itemsPrice[$i])).",".add_slashes(intval($foundArray[$i]['quantity'])).",".intval(trim($itemsReceived[$i]))." ) ";
      
     if($updateStore==1){ //if stock updation is 1 then update in items master table
      //update items's available quuantity
      $r5=$receiveManager->updateAvailableQuantityOfItem($foundArray[$i]['itemId'],intval(trim($itemsReceived[$i])));
      if($r5===false){
         echo INVALID_ITEM_RECORD;
         die; 
      }
     }
      
   }
   if($insStr!=''){
     //add the receive order details
     $r4=$receiveManager->addReceiveOrderDetails($insStr);
     if($r4===false){
         echo FAILURE;
         die;
     }
   }
   else{
     echo FAILURE;
     die;
   }
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
     echo SUCCESS;
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
    
// $History: doReceiveOrder.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/09    Time: 15:31
//Updated in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Updated "Receive Order Master" : Added tax ,total amount and item price
//amount fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:53
//Created in $/Leap/Source/Library/INVENTORY/ReceiveMaster
//Created module "Order Receive Master"
?>