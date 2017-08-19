<?php
//-------------------------------------------------------
// Purpose: To delete Resource category detail
//
// Author : Gurkeerat Sidhu
// Created on : (20.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ResourceCategory');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['resourceTypeId']) || trim($REQUEST_DATA['resourceTypeId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
        
			$resourceCategoryManager =  ResourceCategoryManager::getInstance();
			$getResourceCategoryArray = $resourceCategoryManager->getResourceCategoryExistance("WHERE cr.resourceTypeId=".$REQUEST_DATA['resourceTypeId']);
			$getResourceCategoryId = $getResourceCategoryArray[0]['totalRecords'];
			if ($getResourceCategoryId > 0) {
				echo DEPENDENCY_CONSTRAINT;
			}
			else {
				if($resourceCategoryManager->deleteResourceCategory($REQUEST_DATA['resourceTypeId'])) {
					echo DELETE;
				}
			}
    }
    else {
        echo $errorMessage;
    }
   
    

?>

