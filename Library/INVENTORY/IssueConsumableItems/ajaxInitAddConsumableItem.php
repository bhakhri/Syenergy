<?php
//-------------------------------------------------------
// Purpose: To add in lecture
// Author : Jaineesh
// Created on : (30.03.2009 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','IssueConsumableItems');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
    
	$errorMessage ='';
    
    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));

	require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();

	$store = $REQUEST_DATA['store'];
	$itemCategory = $REQUEST_DATA['itemCategory'];
	$issuedDate = $REQUEST_DATA['issuedDate'];
	$commentsTxt = addslashes($REQUEST_DATA['commentsTxt']);


	if (trim($errorMessage) == '') {
	
        $totalValues = count($REQUEST_DATA['quantity']);
		
		if($totalValues == 0 ) {
			echo ADD_ROW;
			die;
		}

        // Check Validations
        for($i = 0; $i < $totalValues; $i++) {
			  if($REQUEST_DATA['itemId'][$i] == '' ) {
				  echo SELECT_ITEM_NAME;
                  die;
              }
              if($REQUEST_DATA['quantity'][$i] == '' ) {
				  echo FILL_VALUE;
                  die;
              }
			  if($REQUEST_DATA['issuedTo'][$i] == '' ) {
				  echo SELECT_ISSUED_TO;
                  die;
              }

			  if(trim($REQUEST_DATA['quantity'][$i]) < 0 OR trim($REQUEST_DATA['quantity'][$i]) == 0 ) {
				  echo NOT_LESS_THAN_ZERO;
                  die;
              }
			  
			  if(intval($REQUEST_DATA['quantity'][$i]) != floatval($REQUEST_DATA['quantity'][$i])) {
				  echo INVALID_VALUE;
                  die;
              }

          }

        // Delete all Records
        //$str = ' WHERE subjectTypeId='.$subjectTypeId.' AND timeTableLabelId='.$timeTableLabelId.' AND instituteId = '.$instituteId.' AND degreeId = '.$degreeId;
       
        $str = '';
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if($totalValues != 0) {
				$str = '';
				
				for($j = 0; $j < $totalValues; $j++) {
					$itemId = $REQUEST_DATA['itemId'][$j];
					$orderQuantity = trim($REQUEST_DATA['quantity'][$j]);
					$issuedTo = $REQUEST_DATA['issuedTo'][$j];
					$itemQuantityArray = $itemsManager->getInventoryItemsDetail($itemCategory,$REQUEST_DATA['itemId'][$j],$store);
					if ($itemQuantityArray[0]['availableQty'] < trim($REQUEST_DATA['quantity'][$j])) {
						echo AVAILABLE_QUANTITY_NOT_MORE;
						die;
					}
					else {
						$quantity = $itemQuantityArray[0]['availableQty'] - trim($REQUEST_DATA['quantity'][$j]);
					}
					
					if($orderQuantity > 0) {

						if(!empty($str)) {
							$str .= ',';
						}
						$str .= "($itemId,$store,$issuedTo,$orderQuantity,'$issuedDate','$commentsTxt')";

						$returnConsumableQuantityStatus = $itemsManager->updateConsumableAvailableQuantity($itemId,$quantity,$itemCategory);
							if($returnConsumableQuantityStatus === false) {
								$errorMessage = FAILURE;
							}
						}

						//echo($str);
					}
				
				$returnStatus = $itemsManager->addConsumableItems($str);

				if($returnStatus === false) {
					$errorMessage = FAILURE;
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
		}
		else {
			echo FAILURE;
			die;
		}   
	}
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAddConsumableItem.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:08a
//Created in $/Leap/Source/Library/INVENTORY/IssueConsumableItems
//new files for issue consumable items
//
//
?>