<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW DESIGNATION
// Author : Jaineesh
// Created on : (02 Aug 2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGRN');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	//print_r($REQUEST_DATA);
	//die;
	$poNo = $REQUEST_DATA['poNo'];
	$partyId = $REQUEST_DATA['partyCode'];
	$billNo = $REQUEST_DATA['billNo'];
	$billDate = $REQUEST_DATA['billDate'];
	$totalAmount = $REQUEST_DATA['totalAmount'];
	$itemCategoryCount = count($REQUEST_DATA['itemCategoryType']);

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
		if(trim($REQUEST_DATA['poType'][$j]) == '') {
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

		if(trim($REQUEST_DATA['qtyRec'][$j]) == '') {
			echo "Please Enter Quantity Received at Sr. No.".$i;
			die;
		}

		if(trim($REQUEST_DATA['amount'][$j]) == '') {
			echo "Please Enter Amount at Sr. No.".$i;
			die;
		}

		/*if (!is_numeric($REQUEST_DATA['qtyRec'][$j]) or ($REQUEST_DATA['qtyRec'][$j] < 1) or ($REQUEST_DATA['qtyRec'][$j] > 999999.99) or ($REQUEST_DATA['qtyRec'][$j] == 0)) {
			echo "Invalid Quantity Received at Sr. No.".$i;
			die;
		}

		if (!is_numeric($REQUEST_DATA['amount'][$j]) or ($REQUEST_DATA['amount'][$j] < 1) or ($REQUEST_DATA['amount'][$j] > 999999.99) or ($REQUEST_DATA['amount'][$j] == 0)) {
			echo "Invalid Amount Required at Sr. No.".$i;
			die;
		}*/

		for($s = $j+1; $s < $itemCategoryCount; $s++) {
			$k=$s+1;
			if($REQUEST_DATA['poType'][$j] == $REQUEST_DATA['poType'][$s] && $REQUEST_DATA['itemCategoryType'][$j] == $REQUEST_DATA['itemCategoryType'][$s] && $REQUEST_DATA['item'][$j] == $REQUEST_DATA['item'][$s]) {
				echo "Items cannot be duplicated for same PO no., item category and item code at Sr. No.".$k;
				die;
			}
		}
		$i++;
	}

    if (trim($errorMessage) == '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
			$grnManager = GRNManager::getInstance();
			$foundArray = $grnManager->getGRNData(' WHERE LCASE(TRIM(LEADING 0 from billNo))= "'.add_slashes(trim(strtolower(ltrim($REQUEST_DATA['billNo'],"0")))).'" AND partyId = '.$partyId.'');
			if(trim($foundArray[0]['billNo'])=='') {  //DUPLICATE CHECK
			    $type = 1;
				$returnStatus = $grnManager->addGRN();
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
				$billDate = $REQUEST_DATA['billDate'];
				$grnId = SystemDatabaseManager::getInstance()->lastInsertId();
				if($itemCategoryCount > 0 ) {
					for($j = 0; $j < $itemCategoryCount; $j++) {
						$poId = $REQUEST_DATA['poType'][$j];
						$poItemCategory = $REQUEST_DATA['itemCategoryType'][$j];
						$poItem = addslashes($REQUEST_DATA['item'][$j]);
						$grnQuantity = addslashes($REQUEST_DATA['qtyRec'][$j]);
						$poRate = addslashes($REQUEST_DATA['rate'][$j]);
						$poAmount = addslashes($REQUEST_DATA['amount'][$j]);

						$poTransStatus = $grnManager->updatePOTrans($poId,$poItemCategory,$poItem,$grnId);
						if($poTransStatus == false) {
							echo FAILURE;
							die;
						}

						if(!empty($str)) {
							$str .= ',';
						}
						if(!empty($val)) {
							$val .= ',';
						}
						$str .= "($grnId, $poId, $poItemCategory, $poItem, $grnQuantity, $poRate, $poAmount)";
						$val .= "($poItemCategory, $poItem, '$billDate', $grnQuantity, $type)";
					}
					$grnTransStatus = $grnManager->addGRNTrans($str);
					if($grnTransStatus == false) {
						echo FAILURE;
						die;
					}
					$itemStockAdd = $grnManager->addItemStock($val);
					if($itemStockAdd == false) {
						echo FAILURE;
						die;
					}
					$totalStock = $grnManager->updateTotalStock($grnQuantity,$poItem);
					if($totalStock == false) {
						echo FAILURE;
						die;
					}
				}
			}
			else {
				if(trim(strtolower(ltrim($foundArray[0]['billNo'],'0')))==trim(strtolower($REQUEST_DATA['billNo']))) {
					echo BILL_ALREADY_EXIST;
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