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
require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','IssueConsumableItems');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

        
$itemsManager = IssueItemsManager::getInstance();
	//check whether this record is issued or not
	//if issued then it can not be deleted
if($REQUEST_DATA['invConsumableIssuedId'] != '') {  
  //starting transaction  
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //first delete indent details
      $getQuantityStatus=$itemsManager->getQuantity(add_slashes(trim($REQUEST_DATA['invConsumableIssuedId'])));
	  $itemQuantity = $getQuantityStatus[0]['itemQuantity'];
	  $itemId = $getQuantityStatus[0]['itemId'];
      if($getQuantityStatus===false){
        echo FAILURE;
        die;  
      }

	  $getAvailableQuantityStatus=$itemsManager->getAvailableQuantity($itemId);
	  $availableQuantity = $getAvailableQuantityStatus[0]['availableQty'];
	  $gettingQuantity = $itemQuantity + $availableQuantity;
      if($getAvailableQuantityStatus===false){
        echo FAILURE;
        die;  
      }

	  $updateConsumableStatus=$itemsManager->updateConsumableItemsStatus($REQUEST_DATA['invConsumableIssuedId']);
      if($updateConsumableStatus===false){
        echo FAILURE;
        die;  
      }

	  $updateConsumableQuantity = $itemsManager->updateConsumableItemsQuantity($gettingQuantity,$itemId);
      if($updateConsumableQuantity === false){
        echo FAILURE;
        die;  
      }
      
   //now commit transaction   
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo CANCEL_ITEMS;
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
}
   
// $History: ajaxCancelConsumableItems.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:08a
//Created in $/Leap/Source/Library/INVENTORY/IssueConsumableItems
//new files for issue consumable items
//
//
?>