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
require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','IndentMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['indentId']) || trim($REQUEST_DATA['indentId']) == '') {
        echo INDENT_NOT_EXIST;
        die;
    }
        
    $indentManager =  IndentManager::getInstance();
    //check whether this record is issued or not
    //if issued then it can not be deleted
    $recordArray=$indentManager->checkIssueedIndent(add_slashes(trim($REQUEST_DATA['indentId'])));
    if($recordArray[0]['found']>0){
        echo INDENT_DELETE_RESTRICTION;
        die;
    }
    

  
  //starting transaction  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //first delete indent details
      $r1=$indentManager->deleteIndentDetails(add_slashes(trim($REQUEST_DATA['indentId'])));
      if($r1===false){
        echo FAILURE;
        die;  
      }
      
      //then delete indent 
      $r2=$indentManager->deleteIndent(add_slashes(trim($REQUEST_DATA['indentId'])));
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
   
// $History: ajaxDeleteIndentRequest.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>