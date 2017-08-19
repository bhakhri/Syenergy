<?php
//
//  This File calls Edit Function used in adding payroll heads
//
// Author :Abhiraj Malhotra
// Created on : 26-April-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
   if (!isset($REQUEST_DATA['headName']) || trim($REQUEST_DATA['headName']) == '') {
        $errorMessage .= ENTER_HEAD_NAME;
    }
    if (!isset($REQUEST_DATA['headAbbr']) || trim($REQUEST_DATA['headAbbr']) == '') {
        $errorMessage .= ENTER_HEAD_ABBR;
    }
    if (!isset($REQUEST_DATA['headType']) || trim($REQUEST_DATA['headType']) == '') {
        $errorMessage .= SELECT_HEAD_TYPE;
    }
    if (trim($REQUEST_DATA['headType']) == 1 && trim($REQUEST_DATA['dedAccountId'])=='') {
        $errorMessage .= SELECT_DEDUCTION_ACCOUNT;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $foundArray = PayrollManager::getInstance()->getHead(' WHERE UCASE(headName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['headName']))).'" AND UCASE(headAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['headAbbr']))).'" AND headId!='.$REQUEST_DATA['headId']);

		if(trim($foundArray[0]['headName'])=='') {    //DUPLICATE head NAME & Abbreviation CHECK
                if(trim($foundArray[0]['headAbbr'])=='')
                {  
				    $returnStatus = PayrollManager::getInstance()->editHead($REQUEST_DATA['headId']);
				    if($returnStatus === false) {
					$errorMessage = FAILURE;
				    }
				    else {
					    echo SUCCESS;
				        }
                }
                else
                {
                    echo HEAD_ABBR_ALREADY_EXIST;
                }
			}
        else {
            echo HEAD_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

?>