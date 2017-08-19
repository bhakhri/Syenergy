<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW DESIGNATION
// Author : Jaineesh
// Created on : (02 Aug 2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	//print_r($REQUEST_DATA);
	//die;
	$poNo = $REQUEST_DATA['poNo'];
	$partyId = $REQUEST_DATA['partyCode'];
	$totalAmount = $REQUEST_DATA['totalAmount'];
	$itemCategoryCount = count($REQUEST_DATA['itemCategoryType']);
	$vat =$REQUEST_DATA['vat'];
	$discount=$REQUEST_DATA['discount'];
	$grandTotal=$REQUEST_DATA['grandTotal'];
	$aditionalCharges =$REQUEST_DATA['aditionalCharges'];

	
	$i = 1;

	if($itemCategoryCount == '') {
		echo "Please Select a row by Add Rows";
		die;
	}

	if($REQUEST_DATA['partyCode'] == '') {
		echo SELECT_PARTY_CODE;
		die;
	}

	for($j=0;$j<$itemCategoryCount;$j++) {
		if(trim($REQUEST_DATA['indentType'][$j]) == '') {
			echo "Please Select Indent No. at Sr. No.".$i; 
			die;
		}
		
		if(trim($REQUEST_DATA['itemCategoryType'][$j]) == '') {
			echo "Please Select Item Category at Sr. No.".$i; 
			die;
		}

		if(trim($REQUEST_DATA['item'][$j]) == '') {
			echo "Please Select Item Code at Sr. No.".$i; 
			die;
		}
		
		if(trim($REQUEST_DATA['qty'][$j]) == '') {
			echo "Please Enter Quantity Required at Sr. No.".$i;
			die;
		}

		if(trim($REQUEST_DATA['rate'][$j]) == '') {
			echo "Please Enter Rate at Sr. No.".$i;
			die;
		}

	/*	if (!is_numeric($REQUEST_DATA['qty'][$j]) or ($REQUEST_DATA['qty'][$j] < 1) or ($REQUEST_DATA['qty'][$j] > 999999.99) or ($REQUEST_DATA['qty'][$j] == 0)) {
			echo "Invalid Quantity Required at Sr. No.".$i;
			die;
		}

		if (!is_numeric($REQUEST_DATA['rate'][$j]) or ($REQUEST_DATA['rate'][$j] < 1) or ($REQUEST_DATA['rate'][$j] > 999999.99) or ($REQUEST_DATA['rate'][$j] == 0)) {
			echo "Invalid Quantity Required at Sr. No.".$i;
			die;
		}  */


		for($s = $j+1; $s < $itemCategoryCount; $s++) {
			$k=$s+1;
			if($REQUEST_DATA['indentType'][$j] == $REQUEST_DATA['indentType'][$s] && $REQUEST_DATA['itemCategoryType'][$j] == $REQUEST_DATA['itemCategoryType'][$s] && $REQUEST_DATA['item'][$j] == $REQUEST_DATA['item'][$s]) {
				echo "Items cannot be duplicated for same indent no., item category and item code at Sr. No.".$k;
				die;
			}
		}
		$i++;
	}
	
	if (!is_numeric($discount) or ($discount > 999999.99)) {
			echo "Invalid discount";
			die;
	}
	if($discount > $totalAmount){
		echo "discount can't be greater than total amount";
		die;
	}
	if (!is_numeric($aditionalCharges) or ($aditionalCharges > 999999.99)) {
			echo "Invalid Aditional Charges";
			die;
	}
	if (!is_numeric($vat) or ($vat > 999999.99)) {
			echo "Invalid VAT";
			die;
	}
/*	if (!is_numeric($grandTotal) or ($grandTotal < 1) or ($grandTotal > 999999.99)) {
			echo "Invalid Grand Total";
			die;
	}
	if (!is_numeric($totalAmount) or ($totalAmount < 1) or ($totalAmount > 999999.99)) {
			echo "Invalid Total Amount";
			die;
	}  */

	if (trim($errorMessage) == '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
		require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
		$poManager = POManager::getInstance();
		$foundArray = $poManager->getPOData(' WHERE LCASE(poNo)= "'.add_slashes(trim(strtolower($REQUEST_DATA['poNo']))).'"');
			if(trim($foundArray[0]['poNo'])=='') {  //DUPLICATE CHECK
				$returnStatus = $poManager->addPO();
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
				$poId=SystemDatabaseManager::getInstance()->lastInsertId();
				
				if($itemCategoryCount > 0 ) {
					for($j = 0; $j < $itemCategoryCount; $j++) {
						$indentId = $REQUEST_DATA['indentType'][$j];
						$poItemCategory = $REQUEST_DATA['itemCategoryType'][$j];
						$poItem = addslashes($REQUEST_DATA['item'][$j]);
						$poQuantity = addslashes($REQUEST_DATA['qty'][$j]);
						$poRate = addslashes($REQUEST_DATA['rate'][$j]);
						$poAmount = addslashes($REQUEST_DATA['amount'][$j]);

						$indentTransStatus = $poManager->updateIndentTrans($indentId,$poItemCategory,$poItem,$poId);
						if($indentTransStatus == false) {
							echo FAILURE;
							die;
						}

						$pendingPoCountArray = $poManager->countPendingPO($indentId);
						$pendingPoCount = $pendingPoCountArray[0]['cnt'];
						if($pendingPoCount == 0) {
							$indentMasterStatus = $poManager->updateIndentMaster($indentId);
							if($indentMasterStatus == false) {
								echo FAILURE;
								die;
							}
						}
						if(!empty($str)) {
							$str .= ',';
						}
						$str .= "($poId, $indentId, $poItem, $poItemCategory, $poQuantity, $poRate, $poAmount)";
					}
					
					$poTransStatus = $poManager->addPOTrans($str);
					if($poTransStatus == false) {
						echo FAILURE;
						die;
					}
				}
			}
			else {
				if(trim(strtolower($foundArray[0]['poNo']))==trim(strtolower($REQUEST_DATA['poNo']))) {
					echo PO_ALREADY_EXIST;
					die;
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
		else {
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