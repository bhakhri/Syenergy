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

//print_r($REQUEST_DATA);
//die;
    if(!isset($REQUEST_DATA['itemCategoryId']) || trim($REQUEST_DATA['itemCategoryId']) == '') {
        $errorMessage .= SELECT_CATEGORY_CODE. "\n";
    }
    if ($errorMessage == '' && !isset($REQUEST_DATA['itemName']) || trim($REQUEST_DATA['itemName']) == '') {
        $errorMessage .= ENTER_ITEM_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['itemCode']) || trim($REQUEST_DATA['itemCode']) == '')) {
        $errorMessage .= ENTER_ITEM_CODE. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['reorderLevel']) || trim($REQUEST_DATA['reorderLevel']) == '')) {
        $errorMessage .= ENTER_REORDER_LEVEL. "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['unit']) || trim($REQUEST_DATA['unit']) == '')) {
        $errorMessage .= SELECT_UNIT. "\n";
    }
    
	//$availableQty = $REQUEST_DATA['availableQty'];
	//$itemPrefix = $REQUEST_DATA['itemPrefix'];

	if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
        //$foundArray=ItemsManager::getInstance()->getItem(' AND (LCASE(im.itemName) ="'.add_slashes(trim(strtolower($REQUEST_DATA['itemName']))).'" OR LCASE(im.itemCode) ="'.add_slashes(trim(strtolower($REQUEST_DATA['itemCode']))).'") AND im.itemCategoryId = "'.$REQUEST_DATA['itemCategoryId'].'"');
		$foundArray=ItemsManager::getInstance()->getItem(' AND (LCASE(im.itemName) ="'.add_slashes(trim(strtolower($REQUEST_DATA['itemName']))).'" OR LCASE(im.itemCode) ="'.add_slashes(trim(strtolower($REQUEST_DATA['itemCode']))).'")');
       //we will calculate new item code in server side also
       if(SystemDatabaseManager::getInstance()->startTransaction()) { 
		 if(trim($foundArray[0]['itemCode'])=='') {  //DUPLICATE CHECK  
			$returnStatus = ItemsManager::getInstance()->addItem();
			if($returnStatus === false) {
				echo FAILURE;
				die;
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
			else {
				 if(strtolower($foundArray[0]['itemName'])==trim(strtolower($REQUEST_DATA['itemName']))) {
					echo ITEM_NAME_ALREADY_EXIST;
					die;
				}
				else if(strtolower($foundArray[0]['itemCode'])==trim(strtolower($REQUEST_DATA['itemCode']))) {
					echo ITEM_CODE_ALREADY_EXIST;
					die;
				}
				
			}
	   }
	   else{
		   echo FAILURE;
		   die;
	   }
 	 }
    else {
        echo $errorMessage;
    }
// $History: $    
//
?>