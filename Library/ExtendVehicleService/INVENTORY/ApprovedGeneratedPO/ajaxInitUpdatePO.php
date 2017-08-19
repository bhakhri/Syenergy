<?php
//-------------------------------------------------------
// THIS FILE IS USED TO UPDATE EDITED GENERATED PO
// Created on : (08 Dec 2010 )
// Copyright 2008-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApproveGeneratedPO');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   $itemRateCount = count($REQUEST_DATA['rate']);
  /* echo '<pre>';
   print_r($REQUEST_DATA);
   die;
   */
	
   $poId=$REQUEST_DATA['poId'];
   $i=1;
   for($j=0;$j<$itemCategoryTypeCount;$j++) {
	   if(trim($REQUEST_DATA['quantityRequired'][$j]) == '') {
			echo "Please Enter Quantity Required at SR.NO.".$i; 
			die;
		}
		if(trim($REQUEST_DATA['rate'][$j]) == '') {
			echo "Please Enter rate SR.NO.".$i; 
			die;
		}
	/*	if (!is_numeric($REQUEST_DATA['quantityRequired'][$j]) or ($REQUEST_DATA['quantityRequired'][$j] < 1) or ($REQUEST_DATA['quantityRequired'][$j] > 999999.99) or ($REQUEST_DATA['quantityRequired'][$j] == 0)) {
			echo "Invalid Quantity Required at Sr. No.".$i;
			die;
		}

		if (!is_numeric($REQUEST_DATA['rate'][$j]) or ($REQUEST_DATA['rate'][$j] < 1) or ($REQUEST_DATA['rate'][$j] > 999999.99) or ($REQUEST_DATA['rate'][$j] == 0)) {
			echo "Invalid Quantity Required at Sr. No.".$i;
			die;
		}
		$i++; */
   }


   if(!isset($REQUEST_DATA['partyCode']) || trim($REQUEST_DATA['partyCode']) == '') {
        echo "SELECT_Party_CODE";
		die;
   }
   if (!is_numeric($REQUEST_DATA['Discount'])  or ($REQUEST_DATA['Discount'] > 999999.99)) {
			echo "Invalid Discount";
			die;
	}
   if (!is_numeric($REQUEST_DATA['vat']) or ($REQUEST_DATA['vat'] < 0) or($REQUEST_DATA['vat'] > 999999.99)) {
			echo "Invalid Vat";
			die;
	}
   if (!is_numeric($REQUEST_DATA['aditionalCharges']) or  ($REQUEST_DATA['aditionalCharges'] > 999999.99))  {
			echo "Invalid aditionalCharges";
			die;
	}

	if($REQUEST_DATA['Discount'] >= $REQUEST_DATA['totalAmount']){
		echo "Invalid Discount";
		die;
	}


	if(SystemDatabaseManager::getInstance()->startTransaction()) {
        require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
		$poManager = POManager::getInstance();
		$partyId = $REQUEST_DATA['partyCode'];
		$discount = $REQUEST_DATA['Discount'];
		$vat = $REQUEST_DATA['vat'];
		$aditonalCharges = $REQUEST_DATA['aditionalCharges'];
		$grandtotal = $REQUEST_DATA['totalAmount'];
		$netAmount = $REQUEST_DATA['grandTotal'];
		$updatePOMasterStatus = $poManager->updatePOMaster($poId,$partyId,$discount,$aditonalCharges,$vat,$grandtotal,$netAmount);
		if($updatePOMasterStatus == false) {
			echo FAILURE;
			die;
		}
	
		if($itemRateCount > 0){
			for($i=0;$i<$itemRateCount;$i++){
				$itemId=$REQUEST_DATA['itemId'][$i];
				$rate=$REQUEST_DATA['rate'][$i];
				$amount=$REQUEST_DATA['amount'][$i];
				$quantityRequired = $REQUEST_DATA['quantityRequired'][$i];
				$updatePOTrans = $poManager->UpdatePOTrans($poId,$itemId,$rate,$quantityRequired,$amount);
				if($updatePOTrans == false) {
					echo FAILURE;
					die;
				}
			}
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
?>