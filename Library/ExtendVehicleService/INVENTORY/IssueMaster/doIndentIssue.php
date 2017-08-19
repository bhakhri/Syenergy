<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD/Edit An Order Receive Details
// Author : Dipanjan Bhattacharjee
// Created on : (02.09.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','IssueMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $issueManager = IssueManager::getInstance();
    
    $errorMessage ='';
    if (!isset($REQUEST_DATA['indentId']) || trim($REQUEST_DATA['indentId']) == '') {
        echo ENTER_INDENT_NO."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['issuedStr']) || trim($REQUEST_DATA['issuedStr']) == '')) {
        echo ITEM_INFO_MISSING."\n";
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['issueDate']) || trim($REQUEST_DATA['issueDate']) == '')) {
        echo ENTER_ISSUE_DATE."\n";
        die;  
    }
 
    
    $itemsIssued=explode(',',trim($REQUEST_DATA['issuedStr']));
    
    $indentId=add_slashes(trim($REQUEST_DATA['indentId']));
    
    //**************PREVENT EDITING/Duplicattion OF RECORDS ****************//
     $foundArray3=$issueManager->checkIndentId($indentId);
     if($foundArray3[0]['found']>0){
         echo 'It is already issued';
         die;
     }
    //**************PREVENT EDITING/Duplicattion OF RECORDS****************//
    
    
    
    //**************PREVENT Issuing OF RECORDS if the user is not authorised to to****************//
     $userId=$sessionHandler->getSessionVariable('UserId');
     $foundArray4=$issueManager->checkUserPermission($indentId,$userId);
     if($foundArray4[0]['found']==0){
         echo 'You cannot issue this indent';
         die;
     }
    //**************PREVENT Issuing OF RECORDS if the user is not authorised to to****************//
    
    
    //get the info corresponding to this order id
    $foundArray=$issueManager->getIndentDetailIds(' AND irid.indentId="'.$indentId.'"');
    
    
    //check if this orderId exists and dispatched=1
    if(!is_array($foundArray) or count($foundArray)==0){
        echo INVALID_INDENT_NO;
        die;
    }
    
    //check this itemIds against item_master table
    $itemIds=UtilityManager::makeCSList($foundArray,'itemId');
    $foundArray2=$issueManager->checkItemIds($itemIds);
    if(!is_array($foundArray2) or count($foundArray2)==0){
        echo INVALID_ITEM_RECORD;
        die;
    }
    $itemIds2=explode(',',UtilityManager::makeCSList($foundArray2,'itemId'));
    $itemAvailableQuantity=explode(',',UtilityManager::makeCSList($foundArray2,'availableQty'));
    $itemCode=explode(',',UtilityManager::makeCSList($foundArray2,'itemCode'));
    
    $itemIds1=array_unique(explode(',',$itemIds));
    if( (count($itemIds1)!=count($itemIds2)) or (count(array_diff($itemIds1,$itemIds2))>0) ){
       echo INVALID_ITEM_RECORD;
       die; 
    }
    
   $count=count($foundArray);
   //check if the no of items from database and front end matches
   if($count!=count($itemsIssued)){
      echo INVALID_INDENT_NO;
      die;
   }
   
   $serverDate=explode('-',date('Y-m-d'));
   $sDate1=explode('-',$REQUEST_DATA['issueDate']);
   $sDate2=explode('-',$foundArray[0]['dated']);
   $start_date=gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
   
    //check if issue date is bigger than server date
    $end_date=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
    if(($start_date-$end_date)>0){
        echo ISSUE_DATE_VALIDATION2;
        die;
    }
   
   //check if issue date is smaller than indent date
   $end_date=gregoriantojd($sDate2[1], $sDate2[2], $sDate2[0]);
   if(($end_date - $start_date)>0){
        echo ISSUE_DATE_VALIDATION1;
        die;
   }
  
  //************TABLE LOCK CODE GOES HERE***********************//
  //PLUS===>we need to use the check that only requestd to user can issue an indet
  //
  
  for($i=0;$i<$count;$i++){
      //CHECK Items (avilableQty-issuedQty) >= 0
      if( ($itemAvailableQuantity[$i]-$itemsIssued[$i])<0 ){
          echo ITEM_ISSUE_RESTRICTION.'~!~'.$itemCode[$i].'~!~'.$itemAvailableQuantity[$i]; //release lock
          die;
      }
      
      if($foundArray[$i]['quantityRequested']<$itemsIssued[$i]){
         echo ITMES_ISSUED_VALIDATION_WITH_REQUESTED.'~!~'.$itemCode[$i].'~!~'.$foundArray[$i]['quantityRequested']; //release lock
         die;  
      }
  }
   
  
  
  //starting transaction
  if(SystemDatabaseManager::getInstance()->startTransaction()) {

   // add in issue table
   $r3=$issueManager->addIssue($indentId,$REQUEST_DATA['issueDate'],add_slashes(trim($REQUEST_DATA['remarks'])));
   if($r3===false){
       echo FAILURE;
       die;
   }
   
   $insStr='';
   for($i=0;$i<$count;$i++){
       if($insStr!=''){
           $insStr .=' , ';
       }
      if(trim($itemsIssued[$i])==''){
          echo ENTER_ITMES_ISSUED;
          die;
      }
      if(trim($itemsIssued[$i])<0){
          echo ENTER_ITMES_ISSUED_GREATER_THAN_ZERO;
          die;
      }
      if(!is_numeric(trim($itemsIssued[$i]))){
          echo ENTER_ITMES_ISSUED_IN_NUMERIC;
          die;
      }
      $insStr .=" ( $indentId,".$foundArray[$i]['itemId'].",".intval(trim($itemsIssued[$i]))." ) ";
      
      //update items's available quuantity
      $r5=$issueManager->updateAvailableQuantityOfItem($foundArray[$i]['itemId'],intval(trim($itemsIssued[$i])));
      if($r5===false){
         echo INVALID_ITEM_RECORD;
         die; 
      }
   }
   if($insStr!=''){
     //add the issue details
     $r4=$issueManager->addIssueDetails($insStr);
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
    
// $History: doIndentIssue.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/09/09   Time: 18:53
//Created in $/Leap/Source/Library/INVENTORY/IssueMaster
//Created "Issue Master" under inventory management in leap
?>