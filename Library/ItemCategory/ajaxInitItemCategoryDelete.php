<?php
//-------------------------------------------------------
// Purpose: To delete item category detail
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ItemCategoryMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['itemCategoryId']) || trim($REQUEST_DATA['itemCategoryId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ItemCategoryManager.inc.php");
            $itemCategoryManager =  ItemCategoryManager::getInstance();
            $recordArray = $itemCategoryManager->checkInItemsMaster($REQUEST_DATA['itemCategoryId']);
			if ($recordArray[0]['found'] > 0) {
            echo DEPENDENCY_CONSTRAINT; 
            }
            else{
            if($itemCategoryManager->deleteItemCategory($REQUEST_DATA['itemCategoryId'])) {
				echo DELETE;
			}
        }
    }
    else {
        echo $errorMessage;
    }
   
    

?>

