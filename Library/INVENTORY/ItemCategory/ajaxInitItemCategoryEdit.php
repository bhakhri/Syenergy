<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEM CATEGORY
//
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ItemCategoryMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '')) {
        $errorMessage .= ENTER_CATEGORY."\n";    
    }
      if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryCode']) || trim($REQUEST_DATA['categoryCode']) == '')) {
        $errorMessage .= ENTER_CATEGORY_CODE."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryType']) || trim($REQUEST_DATA['categoryType']) == '')) {
        $errorMessage .= ENTER_CATEGORY_TYPE."\n";    
    }
	  if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
        $foundArray = ItemCategoryManager::getInstance()->getItemCategory(' WHERE (UCASE(categoryName) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['categoryName']))).'" OR UCASE(categoryCode)="'.add_slashes(strtoupper($REQUEST_DATA['categoryCode'])).'") AND itemCategoryId!='.$REQUEST_DATA['itemCategoryId']);
                 if(trim($foundArray[0]['categoryCode'])=='') {  //DUPLICATE CHECK  
                   $returnStatus = ItemCategoryManager::getInstance()->editItemCategory($REQUEST_DATA['itemCategoryId']);
                        if($returnStatus === false) {
                            echo FAILURE;
                        }
                        else {
                            echo SUCCESS;           
                        }
                    }
                    else {
                       if(trim(strtoupper($foundArray[0]['categoryCode']))==trim(strtoupper($REQUEST_DATA['categoryCode']))){ 
                           echo CATEGORY_CODE_EXIST;
                         die;
                       }
                       elseif(trim(strtoupper($foundArray[0]['categoryName'])) == trim(strtoupper($REQUEST_DATA['categoryName']))) { 
                           echo CATEGORY_NAME_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }
?>