<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (28.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineCategory');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '')) {
        $errorMessage .= ENTER_DISCIPLINECATEGORY_NAME."\n";    
    }
     if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelDisciplineCatManager.inc.php");
        $foundArray = DisciplineManager::getInstance()->getDisciplineCategory(' WHERE UCASE(categoryName)="'.add_slashes(strtoupper($REQUEST_DATA['categoryName'])).'" AND disciplineCategoryId!='.$REQUEST_DATA['disciplineCategoryId']);
        if(trim($foundArray[0]['categoryName'])=='') {  //DUPLICATE CHECK
            $returnStatus = DisciplineManager::getInstance()->editDisciplineCategory($REQUEST_DATA['disciplineCategoryId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

?>
