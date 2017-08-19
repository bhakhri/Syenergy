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
define('MODULE','RequisitionMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
	
	$requisitionNo = $REQUEST_DATA['requisitionNo'];
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
        require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
		$requisitionManager = RequisitionManager::getInstance();
		$foundArray = $requisitionManager->getRequisitionData(' WHERE LCASE(requisitionNo)= "'.add_slashes(trim(strtolower($REQUEST_DATA['requisitionNo']))).'"');
			if(trim($foundArray[0]['requisitionNo'])=='') {  //DUPLICATE CHECK
				$returnStatus = $requisitionManager->addRequisition();
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
				$requisitionId=SystemDatabaseManager::getInstance()->lastInsertId();
				
				if($itemCategoryCount > 0 ) {
					for($j = 0; $j < $itemCategoryCount; $j++) {
						$requisitionItemCategory = $REQUEST_DATA['itemCategoryType'][$j];
						$requisitionItem = addslashes($REQUEST_DATA['item'][$j]);
						$requisitionQuantity = addslashes($REQUEST_DATA['qty'][$j]);
						if(!empty($str)) {
							$str .= ',';
						}
						$str .= "($requisitionId, $requisitionItemCategory, $requisitionItem, $requisitionQuantity)";
					}

					$requisitionTransStatus = $requisitionManager->addRequisitionTrans($str);
					if($requisitionTransStatus == false) {
						echo FAILURE;
						die;
					}
				}
			}
			else {
				if(trim(strtolower($foundArray[0]['requisitionNo']))==trim(strtolower($REQUEST_DATA['requisitionNo']))) {
					echo REQUSITION_ALREADY_EXIST;
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