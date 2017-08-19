<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEM CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
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
      if ($errorMessage == '' && (!isset($REQUEST_DATA['abbr']) || trim($REQUEST_DATA['abbr']) == '')) {
        $errorMessage .= ENTER_ABBR."\n";    
    }
	  if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ItemCategoryManager.inc.php");
        $foundArray = ItemCategoryManager::getInstance()->getItemCategory(' WHERE (UCASE(categoryName) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['categoryName']))).'" OR UCASE(abbr)="'.add_slashes(strtoupper($REQUEST_DATA['abbr'])).'") AND itemCategoryId!='.$REQUEST_DATA['itemCategoryId']);
                 if(trim($foundArray[0]['abbr'])=='') {  //DUPLICATE CHECK  
                   $returnStatus = ItemCategoryManager::getInstance()->editItemCategory($REQUEST_DATA['itemCategoryId']);
                        if($returnStatus === false) {
                            echo FAILURE;
                        }
                        else {
                            echo SUCCESS;           
                        }
                    }
                    else {
                       if(trim(strtoupper($foundArray[0]['abbr']))==trim(strtoupper($REQUEST_DATA['abbr']))){ 
                           echo ABBR_EXIST;
                         die;
                       }
                       elseif($foundArray[0]['categoryName']==trim($REQUEST_DATA['categoryName'])){ 
                           echo ITEM_CATEGORY_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }

 
?>
