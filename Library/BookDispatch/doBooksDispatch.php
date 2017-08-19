<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','BookDispatch');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']);
$students=trim($REQUEST_DATA['studentIds']);

$notSelectedStudentIds = trim($REQUEST_DATA['notSelectedStudentIds']);

if($classId==''){
    die('Required Parameters Missing');
}

require_once(MODEL_PATH . "/BookDispatchManager.inc.php");
$bookMgr = BookDispatchManager::getInstance();


if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
  $userId=$sessionHandler->getSessionVariable('UserId');
  
  //change the status
  $ret=$bookMgr->deleteBookDispatch($classId,BOOK_PACKED);
  if($ret==false){
      die(FAILURE);
  }
  
  //now do the packing
  if($students!=''){
    $ret = $bookMgr->doBookDispatch($classId,$students,BOOK_DISPACHED);
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