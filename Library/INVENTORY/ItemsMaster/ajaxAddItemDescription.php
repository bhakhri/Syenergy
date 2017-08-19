<?php
//-------------------------------------------------------
// Purpose: To add items
//
// Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ItemsMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");

   $categoryCode=$REQUEST_DATA['categoryCode'];
   $itemName=$REQUEST_DATA['itemName'];
   $itemCode=$REQUEST_DATA['itemCode'];
   $reorderLevel = $REQUEST_DATA['reorderLevel'];
   $unit=$REQUEST_DATA['unit'];
   $hiddenBox=$REQUEST_DATA['hiddenBox'];
    
       $filter='';
       if(SystemDatabaseManager::getInstance()->startTransaction()) { 
            for($i=0;$i<count($hiddenBox);$i++){
		     $condition='';
		     if($hiddenBox[$i]=='-1'){
		       $filter ='INSERT';
		     } 
		     else{
		        $filter='UPDATE';
		        $condition ='WHERE itemId ='.$hiddenBox[$i];
		     }
		     $returnStatus = ItemsManager::getInstance()->updateItemDescription($filter,$categoryCode,$itemName[$i],$itemCode[$i],$reorderLevel[$i],$unit[$i],$condition);
			if($returnStatus === false) {
				echo FAILURE;
				die;
			}
              }
	      //****AS WE MOVED THE MAPPING PORTION TO THE NEW MODULE****
	      if(SystemDatabaseManager::getInstance()->commitTransaction()){
		echo SUCCESS;
		die;
	      }
	      else {
		echo FAILURE;
		die;
	      }
       }
?>
