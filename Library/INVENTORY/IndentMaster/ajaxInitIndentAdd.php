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
	$indentNo = $REQUEST_DATA['indentNo'];
	$itemCategoryCount = count($REQUEST_DATA['itemCategoryType']);
	
	$i = 1;

	if($itemCategoryCount == '') {
		echo "Please select a row by Add Rows";
		die;
	}

	for($j=0;$j<$itemCategoryCount;$j++) {
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

		if (!is_numeric($REQUEST_DATA['qty'][$j]) or ($REQUEST_DATA['qty'][$j] < 1) or ($REQUEST_DATA['qty'][$j] > 999999.99) or ($REQUEST_DATA['qty'][$j] == 0)) {
			echo "Invalid Quantity Required at Sr. No.".$i;
			die;
		}

		for($s = $j+1; $s < $itemCategoryCount; $s++) {
			$k=$s+1;
			if($REQUEST_DATA['itemCategoryType'][$j] == $REQUEST_DATA['itemCategoryType'][$s] && $REQUEST_DATA['item'][$j] == $REQUEST_DATA['item'][$s]) {
				echo "Items cannot be duplicated for same item category and item code at Sr. No.".$k;
				die;
			}
		}
		$i++;
	}

    if (trim($errorMessage) == '') {
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
        require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
		$indentManager = IndentManager::getInstance();
		$foundArray = $indentManager->getIndentData(' WHERE LCASE(indentNo)= "'.add_slashes(trim(strtolower($REQUEST_DATA['indentNo']))).'"');
			if(trim($foundArray[0]['indentNo'])=='') {  //DUPLICATE CHECK
				$returnStatus = $indentManager->addIndent();
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
				$indentId=SystemDatabaseManager::getInstance()->lastInsertId();
				
				if($itemCategoryCount > 0 ) {
					for($j = 0; $j < $itemCategoryCount; $j++) {
						$indentItemCategory = $REQUEST_DATA['itemCategoryType'][$j];
						$indentItem = addslashes($REQUEST_DATA['item'][$j]);
						$indentQuantity = addslashes($REQUEST_DATA['qty'][$j]);
						if(!empty($str)) {
							$str .= ',';
						}
						$str .= "($indentId, $indentItemCategory, $indentItem, $indentQuantity)";
					}

					$indentTransStatus = $indentManager->addIndentTrans($str);
					if($indentTransStatus == false) {
						echo FAILURE;
						die;
					}
				}
			}
			else {
				if(trim(strtolower($foundArray[0]['indentNo']))==trim(strtolower($REQUEST_DATA['indentNo']))) {
					echo INDENT_ALREADY_EXIST;
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