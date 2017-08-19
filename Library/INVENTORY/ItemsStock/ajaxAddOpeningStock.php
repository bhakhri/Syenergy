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
define('MODULE','OpeningStock');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
$itemsManager = ItemsManager::getInstance();
$sessionId=$sessionHandler->getSessionVariable('SessionId');
$errorMessage ='';

//print_r($REQUEST_DATA);
//die;
$itemCategoryId = $REQUEST_DATA['categoryCode'];
$stockDate = $REQUEST_DATA['stockDate'];
$opening = 0;


$filter = "WHERE im.itemCategoryId = $itemCategoryId";
$itemRecordArray = $itemsManager->getParticularItemData($filter);
$cnt = count($itemRecordArray);
$j = 1;
if (trim($errorMessage) == '') {
	require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		for($i=0;$i<$cnt;$i++) {
			$itemId = $itemRecordArray[$i]['itemId'];
			$getQty = 'qty_'.$itemRecordArray[$i]['itemId'];
			$qty = $REQUEST_DATA[$getQty];
			if($qty == '') {
				echo "Enter quantity at Sr. No.".$j;
				die;
			}
			if($qty != '') {
				if (!is_numeric($qty) or ($qty < 1) or ($qty > 999999.99)) {
					echo "Invalid quantity at Sr. No.".$j;
				    die;
				}
			}
			if(!empty($str)) {
				$str .= ',';
			}
			if(!empty($val)) {
				$val .= ',';
			}

			$str .= "($itemCategoryId,$itemId,'$stockDate',$qty,$opening)";
			$val .= "($itemCategoryId,$itemId,$qty,$sessionId)";
			$j++;
		}
		//die('line'.__LINE__);
		$returnDeleteStatus = $itemsManager->deleteStock($itemCategoryId);
		if($returnDeleteStatus == false) {
			echo FAILURE;
			die;
		}

		$returnStatus = $itemsManager->addOpeningStock($str);
		$addItem = $itemsManager->addTotalItemStock($val);
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