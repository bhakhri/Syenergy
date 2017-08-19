<?php
//
//  This File calls Edit Function used in adding payroll deduction accounts
//
// Author :Abhiraj Malhotra
// Created on : 15-Apr-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
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
    if (!isset($REQUEST_DATA['accountName']) || trim($REQUEST_DATA['accountName']) == '') {
        $errorMessage .= ENTER_ACCOUNT_NAME;
    }
    if (!isset($REQUEST_DATA['accountNumber']) || trim($REQUEST_DATA['accountNumber']) == '') {
        $errorMessage .= ENTER_ACCOUNT_NUMBER;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PayrollManager.inc.php");
        $foundArrayName = PayrollManager::getInstance()->getDedAccount(' WHERE UCASE(accountName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['accountName']))).'" AND dedAccountId!='.$REQUEST_DATA['dedAccountId']);
        $foundArrayNumber = PayrollManager::getInstance()->getDedAccount(' WHERE UCASE(accountNumber)="'.add_slashes(trim(strtoupper($REQUEST_DATA['accountNumber']))).'" AND dedAccountId!='.$REQUEST_DATA['dedAccountId']);

		if(trim($foundArrayName[0]['accountName'])=='') {  //DUPLICATE CHECK
		    if(trim($foundArrayNumber[0]['accountNumber'])=='')
             {
                $returnStatus = PayrollManager::getInstance()->editAccount($REQUEST_DATA['dedAccountId']);
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                    }
                else 
                {
                    echo SUCCESS;
                }
             }
             else
             {
                echo ACCOUNT_NUMBER_ALREADY_EXISTS; 
             }
			}
        else {
            echo ACCOUNT_NAME_ALREADY_EXISTS;
        }
    }
    else {
        echo $errorMessage;
    }

?>