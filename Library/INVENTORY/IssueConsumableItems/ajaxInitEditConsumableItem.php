<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEM CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','IssueConsumableItems');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       /* if ($errorMessage == '' && (!isset($REQUEST_DATA['inventoryDeptName']) || trim($REQUEST_DATA['inventoryDeptName']) == '')) {
        $errorMessage .= ENTER_INVENTORY_DEPT_NAME."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['abbr']) || trim($REQUEST_DATA['abbr']) == '')) {
        $errorMessage .= ENTER_ABBR."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['departmentType']) || trim($REQUEST_DATA['departmentType']) == '')) {
        $errorMessage .= SELECT_DEPT_TYPE."\n";    
    }*/
	 
		
	 $itemCategory = $REQUEST_DATA['itemCategory'];
	 $itemId = $REQUEST_DATA['editItemName'];
	 $editIssuedTo = $REQUEST_DATA['editIssuedTo'];
	 $issuedDate = $REQUEST_DATA['issuedDate1'];
	 $store = $REQUEST_DATA['store'];
	 $commentsTxt = addslashes($REQUEST_DATA['commentsTxt']);
	 $invConsumableIssuedId = $REQUEST_DATA['invConsumableIssuedId'];

	 if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
		$itemsManager = IssueItemsManager::getInstance();

        /*$itemQuantityArray = $itemsManager->getInventoryItemsDetail($itemCategory,$itemId,$store);
		$availableQty = $itemQuantityArray[0]['editItemQuantity']);
		die;*/
		if ($invConsumableIssuedId != '')
		 //if(trim($foundArray[0]['invDepttAbbr'])=='') {  //DUPLICATE CHECK  
		   $returnStatus = $itemsManager->editConsumableIssueItems($invConsumableIssuedId,$editIssuedTo,$issuedDate,$commentsTxt);
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			//}

    }
    else {
        echo $errorMessage;
    }
?>