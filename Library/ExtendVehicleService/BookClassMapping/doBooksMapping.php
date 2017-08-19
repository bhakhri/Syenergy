<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','BookClassMapping');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']);
$books=trim($REQUEST_DATA['bookIds']);

$notSelectedBookIds = trim($REQUEST_DATA['notSelectedBookIds']);

if($classId==''){
    die('Required Parameters Missing');
}

require_once(MODEL_PATH . "/BookClassMappingManager.inc.php");
$bookMgr = BookClassMappingManager::getInstance();

//check for usage in issuing table
if($notSelectedBookIds!=''){
   $bookArray=explode(',',$notSelectedBookIds);
   $cnt=count($bookArray);
   $searchString='';
   for($i=0;$i<$cnt;$i++){
      if(trim($bookArray[$i])==''){
          die('Book id is missing');
      } 
      if($searchString!=''){
          $searchString .=',';
      }
      $searchString .="'".$classId.'~'.trim($bookArray[$i])."'";
   }
   
   $bookUsageArray=$bookMgr->checkBookIssuedOrNot($searchString);
   if(is_array($bookUsageArray) and count($bookUsageArray)>0){
      $bookIds=UtilityManager::makeCSList($bookUsageArray,'bookId');
      //now find book no.s of these books and show it to users
      $notSelectedBookArray=$bookMgr->getBooksList($bookIds);
      if(is_array($notSelectedBookArray) and count($notSelectedBookArray)>0){
          $errorString='';
          foreach($notSelectedBookArray as $key=>$val){
              if($errorString!=''){
                  $errorString .=" \n";
              }
              $errorString .=($key+1).'. '.$notSelectedBookArray[$key]['bookNo'];
          }
          die("These books can be de-allocated as they are already issued to students : \n".$errorString);
      }
   }
}

if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
  $userId=$sessionHandler->getSessionVariable('UserId');
  
  //delete all mappings corresponding to this class
  $ret=$bookMgr->deleteBookClassMapping($classId);
  if($ret==false){
      die(FAILURE);
  }
  
  if($books!=''){
      $bookArray=explode(',',$books);
      $cnt=count($bookArray);
      $insertString='';
      for($i=0;$i<$cnt;$i++){
          if(trim($bookArray[$i])==''){
              die('Book id is missing');
          }
          if($insertString!=''){
              $insertString .=',';
          }
          $insertString .=" ( $classId,".trim($bookArray[$i]).",$userId ) ";
      }
      //do the mapping
      $ret = $bookMgr->insertMappingData($insertString);
      if($ret==false){
          die(FAILURE);
      }
  }
    
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
    die(SUCCESS);
  }
  else {
   die(FAILURE);
  }
}
else {
  die(FAILURE);
}

    
?>