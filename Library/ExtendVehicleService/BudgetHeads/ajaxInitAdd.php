<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BudgetHeads');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
        $errorMessage .=  ENTER_BUDGET_HEAD_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['headAmount']) || trim($REQUEST_DATA['headAmount']) == '')) {
        $errorMessage .= ENTER_BUDGET_HEAD_AMT."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['headTypeId']) || trim($REQUEST_DATA['headTypeId']) == '')) {
        $errorMessage .= SELECT_BUDGET_HEAD_TYPES."\n";  
    }
    if (trim($errorMessage) == '') {
        if(!is_numeric(trim($REQUEST_DATA['headAmount']))){
            echo ENTER_DECIMAL_VALUE;
            die;
        }
        if(trim($REQUEST_DATA['headAmount'])<0){
            echo ENTER_POSITIVE_VALUE;
            die;
        }
        require_once(MODEL_PATH . "/BudgetHeadsManager.inc.php");
        $foundArray = BudgetHeadsManager::getInstance()->getBudgetHeads(' WHERE headTypeId='.trim($REQUEST_DATA['headTypeId']).' AND ( UCASE(headName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['headName']))).'")');
        if(trim($foundArray[0]['headName'])=='') {  //DUPLICATE CHECK
            $returnStatus = BudgetHeadsManager::getInstance()->addBudgetHeads();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo BUDGET_HEAD_ALREADY_EXIST;
            die;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>