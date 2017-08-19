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
define('MODULE','BookPack');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']);
$students=trim($REQUEST_DATA['studentIds']);

$notSelectedStudentIds = trim($REQUEST_DATA['notSelectedStudentIds']);

if($classId==''){
    die('Required Parameters Missing');
}

require_once(MODEL_PATH . "/BookPackManager.inc.php");
$bookMgr = BookPackManager::getInstance();

//check for usage in issuing table
if($notSelectedStudentIds!=''){
   $studentArray=explode(',',$notSelectedStudentIds);
   $cnt=count($studentArray);
   $searchString='';
   for($i=0;$i<$cnt;$i++){
      if(trim($studentArray[$i])==''){
          die('Student id is missing');
      } 
      if($searchString!=''){
          $searchString .=',';
      }
      $searchString .="'".$classId.'~'.trim($studentArray[$i])."'";
   }
   
   $bookUsageArray=$bookMgr->checkBookDispatchedOrNot($searchString);
   if(is_array($bookUsageArray) and count($bookUsageArray)>0){
      $studentIds=UtilityManager::makeCSList($bookUsageArray,'studentId');
      //now find student name and roll no of these students and show it to users
      $notSelectedStudentArray=$bookMgr->getStudentList($studentIds);
      if(is_array($notSelectedStudentArray) and count($notSelectedStudentArray)>0){
          $errorString='';
          foreach($notSelectedStudentArray as $key=>$val){
              if($errorString!=''){
                  $errorString .=" \n";
              }
              if(trim($notSelectedStudentArray[$key]['rollNo'])==''){
                  $notSelectedStudentArray[$key]['rollNo']=NOT_APPLICABLE_STRING;
              }
              $errorString .=($key+1).". ".$notSelectedStudentArray[$key]['studentName']." (".$notSelectedStudentArray[$key]['rollNo'].")";
          }
          die("These student can be de-allocated as books are already dispatched to them : \n".$errorString);
      }
   }
}


if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
  $userId=$sessionHandler->getSessionVariable('UserId');
  
  //change the status
  $ret=$bookMgr->deleteBookPacking($classId,BOOK_ISSUED);
  if($ret==false){
      die(FAILURE);
  }
  
  //now do the packing
  if($students!=''){
    $ret = $bookMgr->doBookPacking($classId,$students,BOOK_PACKED);
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