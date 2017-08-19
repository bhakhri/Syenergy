<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD An Order
// Author : Dipanjan Bhattacharjee
// Created on : (02.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','OrderMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$orderManager = OrderManager::getInstance();

    $errStr='';
    //to check for invalid user input
    function checkEmpty($item,$key){
        global $errStr;
        if(trim($item)==''){
           if($errStr==''){ 
            //$errStr =($key+1);
            $errStr =$key;
           }
           else{
               //$errStr .='@'.($key+1);
               $errStr .='@'.$key;
           }
        }
    }
    
    //to add single quote
    function addSingleQuote(&$item,$key){
        $item="'".add_slashes($item)."'";
    }

    //data sanitize
    function sanitizeData(&$item,$key){
        $item=preg_replace("/[\n\r]/","",strval(trim($item)));
    }

    function makeString($array) {
        $newArray = Array();
        foreach ($array as $key => $value) {
            $newArray[] = strval(strtoupper(trim($value)));
        }
        return $newArray;
    }
    
    $errorMessage ='';
    if (!isset($REQUEST_DATA['supplierId']) || trim($REQUEST_DATA['supplierId']) == '') {
        echo SELECT_SUPPLIER."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['orderDate']) || trim($REQUEST_DATA['orderDate']) == '')) {
        echo SELECT_ORDER_DATE."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['itemCodes']) || trim($REQUEST_DATA['itemCodes']) == '')) {
        echo ENTER_ITEM_CODE."\n";
        die;  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['itemQuantity']) || trim($REQUEST_DATA['itemQuantity']) == '')) {
        echo ENTER_ITEM_QUANTITY."\n";
        die;  
    }
    
    
    
    //*********checking for invalid item code**********//
    $itemCodes=explode(',',trim($REQUEST_DATA['itemCodes']));
    $itemQty=explode(',',trim($REQUEST_DATA['itemQuantity']));
    
    if(count($itemCodes)!=count($itemQty)){
        echo 'Invalid items and quantity';
        die;
    }
    //can not add more than 20 items at a time
    if(count($itemCodes)>20 or count($itemQty)>20){
        echo "You cannot add more than 20 items at a time";
        die;
    }
    
    array_walk($itemCodes,'sanitizeData');
    
    //check for correct inputs
     array_walk($itemCodes,'checkEmpty');
     if($errStr!=''){
            echo INVALID_ITEM_CODE.'~!~'.$errStr;
            die;
     }
     
    array_walk($itemQty,'sanitizeData');
    
    //check for correct inputs
    array_walk($itemQty,'checkEmpty');
    if($errStr!=''){
            echo BLANK_QUANTITY.'~!~'.$errStr;
            die;
    }
    $c=count($itemQty);
    for($i=0;$i<$c;$i++){
        if(!is_numeric($itemQty[$i]) or trim($itemQty[$i])<=0 ){
            echo INVALID_QUANTITY.'~!~'.$itemQty[$i];
            die;
        }
    } 
    //check for duplicate vale
    if(count($itemCodes)!=count(array_unique($itemCodes))){
        echo DUPLICATE_ITEM_CODE.'~!~'.implode('@',array_diff_assoc($itemCodes,array_unique($itemCodes)));
        die;
    }
    
    $ex1=$itemCodes;
    //add single quote
    array_walk($ex1,'addSingleQuote');
    $ex2=implode(",",$ex1);
   
    //check for correct item code input
    $ret=$orderManager->getItemName(' AND i.itemCode IN ('.$ex2.') AND iis.supplierId='.trim($REQUEST_DATA['supplierId']));
    
    
    $fl=1;
    $r=count($ret);
    $retArray='';
    for($i=0;$i<$r;$i++){
       if($retArray==''){
           $retArray=trim($ret[$i]['itemCode']);
       }
       else{
           $retArray .=' ,'.trim($ret[$i]['itemCode']);
       }
    }
   
   $retArray=explode(',',$retArray);
   $courseCodes = makeString($itemCodes);
   $retArray = makeString($retArray);
   $errStr = array_diff($itemCodes,$retArray);
   if(count($errStr)>0 and is_array($errStr)){
      echo INVALID_ITEM_CODE.'~!~'.implode('@',$errStr);
      die; 
   }
   
 

    
  //starting transaction
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
    //generate new order no
    $orderNo= $orderManager->generateOrderNo();
    if(trim($orderNo)==''){
       echo FAILURE;
       die;  
    }
   
   //check for duplicate order no
    $found=$orderManager->checkDuplicateOrderNo($orderNo);
    if($found[0]['found']>0){
        echo DUPLICATE_ORDER_NO;
        die;
    }
   
   //add the order
   $r=$orderManager->addOrder($orderNo,trim($REQUEST_DATA['orderDate']),trim($REQUEST_DATA['supplierId']),$REQUEST_DATA['dispatched'],$REQUEST_DATA['dispatchDate']);
   if($r===false){
     echo FAILURE;
     die;       
   }
   //get the last insert id
   $orderId=SystemDatabaseManager::getInstance()->lastInsertId();
   
   $insStr='';
   $count=count($itemCodes);
   for($i=0;$i<$count;$i++){
       if($insStr!=''){
           $insStr .=' , ';
       }
      
      $ic=$itemCodes[$i];
      foreach($ret as $val){
          if($val['itemCode']==$ic){
            $ic=$val['itemId'];
            break;
          }
      } 
      $insStr .=" ( $orderId,".$ic.",".add_slashes(intval(abs($itemQty[$i]))) ." ) ";
   }
   if($insStr!=''){
     //add the order details
     $r=$orderManager->addOrderDetails($insStr);
     if($r===false){
         echo FAILURE;
         die;
     }
   }
   else{
     echo FAILURE;
     die;
   }
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
     echo SUCCESS.'~!~'.$orderId;
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
    
// $History: addPurchaseOrder.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/09/09   Time: 10:56
//Updated in $/Leap/Source/Library/INVENTORY/OrderMaster
//Corrected add/edit code during order no entry and corrected interface
//path in print file
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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:04
//Updated in $/Leap/Source/Library/OrderMaster
//Order Master Updated : User can not add more than 20 items at  time
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:47
//Created in $/Leap/Source/Library/OrderMaster
//Added files for "Order Master" module
?>