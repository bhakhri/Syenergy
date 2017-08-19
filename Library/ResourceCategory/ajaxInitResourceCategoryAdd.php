<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Resource CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (20.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ResourceCategory');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['resourceName']) || trim($REQUEST_DATA['resourceName']) == '')) {
        $errorMessage .= ENTER_RESOURCECATEGORY_NAME."\n";    
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ResourceCategoryManager.inc.php");
        $foundArray = ResourceCategoryManager::getInstance()->getResourceCategory(' WHERE UCASE(resourceName)="'.addslashes(strtoupper($REQUEST_DATA['resourceName'])).'"');
        if(trim($foundArray[0]['resourceName'])=='') {  //DUPLICATE CHECK
            $returnStatus = ResourceCategoryManager::getInstance()->addResourceCategory();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo RESOURCE_CATEGORY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

 
?>