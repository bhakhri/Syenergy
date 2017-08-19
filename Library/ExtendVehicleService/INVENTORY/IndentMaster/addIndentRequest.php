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
require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','IndentMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$indentManager = IndentManager::getInstance();

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
    if ($errorMessage == '' && (!isset($REQUEST_DATA['indentDate']) || trim($REQUEST_DATA['indentDate']) == '')) {
        echo SELECT_INDENT_DATE."\n";
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
    
    //server side check for date validation
    $serverDate=explode('-',date('Y-m-d'));
    $sDate0=$serverDate[0].$serverDate[1].$serverDate[2];
    $sDate1=explode('-',trim($REQUEST_DATA['indentDate']));
    $sDate2=$sDate1[0].$sDate1[1].$sDate1[2];
    $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
    $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
    if($start_date-$end_date>0){
        echo INDENT_DATE_VALIDATION;
        die;
    }
    $indentDate=trim($REQUEST_DATA['indentDate']);
    
    
    
    
    //*********checking for invalid item code**********//
    $itemCodes=explode(',',trim($REQUEST_DATA['itemCodes']));
    $itemQty=explode(',',trim($REQUEST_DATA['itemQuantity']));
    
    if(count($itemCodes)!=count($itemQty)){
        echo 'Invalid items and quantity';
        die;
    }
    //can not add more than 20 items at a time
    if(count($itemCodes)>20 or count($itemQty)>20){
        echo "You cannot request more than 20 items at a time";
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
    $ret=$indentManager->getItemName(' AND i.itemCode IN ('.$ex2.')' );
    
    
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
   $itemCodes = makeString($itemCodes);
   $retArray = makeString($retArray);
   $errStr = array_diff($itemCodes,$retArray);
   if(count($errStr)>0 and is_array($errStr)){
      echo INVALID_ITEM_CODE.'~!~'.implode('@',$errStr);
      die; 
   }
   
  $requestedByUserId=$sessionHandler->getSessionVariable('UserId');
  $requestedToEmployee=add_slashes(trim($REQUEST_DATA['employeeCode']));
  if($requestedToEmployee==''){
      echo ENTER_REQUESTED_TO_EMPLOYEE;
      die;
  }
  
  //check whether the reuested by is an employee or not
  $empArray1=$indentManager->checkEmployee(' AND u.userId='.$requestedByUserId);
  if($empArray1[0]['userId']==''){
      echo "No employee record exists corresponding to you.\nPlease contact the administrator";
      die;
  }
  $empArray2=$indentManager->checkEmployee(' AND e.employeeCode = "'.$requestedToEmployee.'"');
  if($empArray2[0]['userId']==''){
      echo "No employee record exists corresponding to selected employee code.\nPlease contact the administrator";
      die;
  }
  //one emplyee can not assign to himself/herself
  if($empArray2[0]['userId']==$requestedByUserId){
      echo 'You cannot request to yourself';
      die;
  }
  $requestedToUserId=$empArray2[0]['userId'];
   

    
  //starting transaction
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
    //generate new indent no
    $indentNo= $indentManager->generateIndentNo();
    if(trim($indentNo)==''){
       echo FAILURE;
       die;  
    }
    
    //check for duplicate indent no
    $found=$indentManager->checkDuplicateIndentNo($indentNo);
    if($found[0]['found']>0){
        echo DUPLICATE_INDENT_NO;
        die;
    }
   
   
   //add the indent
   $r=$indentManager->addIndent($indentNo,$indentDate,$requestedToUserId,$requestedByUserId,add_slashes(trim($REQUEST_DATA['comments'])));
   if($r===false){
     echo FAILURE;
     die;       
   }
   //get the last insert id
   $indentId=SystemDatabaseManager::getInstance()->lastInsertId();
   
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
      $insStr .=" ( $indentId,".$ic.",".add_slashes(intval(abs($itemQty[$i]))) ." ) ";
   }
   if($insStr!=''){
     //add the indent details
     $r=$indentManager->addIndentDetails($insStr);
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
    
// $History: addIndentRequest.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>