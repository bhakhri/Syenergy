<?php
//-------------------------------------------------------
// Purpose: To delete supplier detail
//
// Author : Gurkeerat Sidhu
// Created on : (06.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SupplierMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(INVENTORY_MODEL_PATH . "/SupplierManager.inc.php");
$supplierManager =  SupplierManager::getInstance();
        

    if (!isset($REQUEST_DATA['supplierId']) || trim($REQUEST_DATA['supplierId']) == '') {
        $errorMessage = 'Invalid Supplier';
    }

    if (trim($errorMessage) == '') {
            $recordArray = $supplierManager->checkInItemSupplier($REQUEST_DATA['supplierId']);
            if ($recordArray[0]['found'] > 0) {
            echo DEPENDENCY_CONSTRAINT; 
            }
            else{
            $recordArray = $supplierManager->checkInInvOrders($REQUEST_DATA['supplierId']);
            if ($recordArray[0]['found'] > 0) {
               echo DEPENDENCY_CONSTRAINT; 
            }
            else{
			if($supplierManager->deleteSupplier($REQUEST_DATA['supplierId'])) {
				echo DELETE;
			}
          }
        }
    }
    else {
        echo $errorMessage;
    }
?>